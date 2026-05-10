<?php
namespace App\controllers;

use App\models\Cart;
use App\models\UserPoint;

class CartController
{
    private $cartModel;
    private $userId;
    private $userPoints = 0;

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->userId = isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
        $this->userPoints = (int) ($_SESSION['user']['coins'] ?? 0);
        $this->cartModel = new Cart();
    }

    public function cartView()
    {
        $this->requireLogin();

        $cartItems = $this->cartModel->getCartItems($this->userId);
        $cartCount = count($cartItems);
        $cartTotalPoints = 0;
        foreach ($cartItems as $item) {
            $cartTotalPoints += ($item['quantity'] * ($item['harga'] ?? 0));
        }

        $checkoutFlash = $_SESSION['flash_checkout'] ?? null;
        unset($_SESSION['flash_checkout']);

        require_once __DIR__ . '/../views/cart.php';
    }

    /**
     * Alur pembelian: cek saldo coin → kurangi poin → kosongkan keranjang.
     * Jika saldo kurang → redirect ke /topup (top up dulu).
     */
    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url_for('cart'));
            exit;
        }

        $this->requireLogin();

        $cartItems = $this->cartModel->getCartItems($this->userId);
        $requestedIds = $_POST['selected_ids'] ?? [];
        if (!is_array($requestedIds)) {
            $requestedIds = $requestedIds !== '' ? [(int) $requestedIds] : [];
        }
        $requestedIds = array_values(array_unique(array_filter(array_map('intval', $requestedIds))));

        $byId = [];
        foreach ($cartItems as $item) {
            $byId[(int) $item['cart_item_id']] = $item;
        }

        $selectedLines = [];
        foreach ($requestedIds as $cid) {
            if (isset($byId[$cid])) {
                $selectedLines[] = $byId[$cid];
            }
        }

        $total = 0;
        foreach ($selectedLines as $item) {
            $total += ($item['quantity'] * (float) ($item['harga'] ?? 0));
        }
        $total = (int) round($total);

        if ($total <= 0 || empty($selectedLines)) {
            $_SESSION['flash_checkout'] = [
                'type' => 'error',
                'message' => 'Centang minimal satu produk untuk checkout, atau keranjang tidak valid.',
            ];
            header('Location: ' . url_for('cart'));
            exit;
        }

        $points = new UserPoint();
        $balance = (int) $points->getUserPoints($this->userId);

        if ($balance < $total) {
            $need = $total - $balance;
            $_SESSION['flash_topup'] = [
                'message' => "Coin kamu kurang {$need}. Top up dulu untuk menyelesaikan pembelian (total checkout: {$total} coin).",
                'need_coins' => $need,
                'cart_total' => $total,
            ];
            header('Location: ' . url_for('topup'));
            exit;
        }

        try {
            $points->ensureWallet($this->userId);
            $points->deductPoints($this->userId, $total, 'Checkout pembelian');
            foreach ($selectedLines as $item) {
                $this->cartModel->removeItem($this->userId, (int) $item['cart_item_id']);
            }
            $_SESSION['user']['coins'] = (int) $points->getUserPoints($this->userId);
            $_SESSION['flash_checkout'] = [
                'type' => 'success',
                'message' => "Pembelian berhasil! {$total} coin telah digunakan.",
            ];
        } catch (\Throwable $e) {
            $_SESSION['flash_checkout'] = [
                'type' => 'error',
                'message' => 'Pembayaran gagal. Coba lagi atau cek saldo coin.',
            ];
        }

        header('Location: ' . url_for('cart'));
        exit;
    }

    public function addItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url_for('products'));
            exit;
        }

        $this->requireLogin();

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        if ($productId > 0) {
            $success = $this->cartModel->addItem($this->userId, $productId, $quantity);
        } else {
            $success = false;
        }

        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            exit;
        }

        $redirect = $this->safeInternalRedirect((string) ($_POST['next'] ?? ''));

        if (!$success && app_path_is_under_products($redirect)) {
            $_SESSION['flash_cart_notice'] = [
                'type' => 'error',
                'message' => 'Gagal menambahkan ke keranjang. Login dulu atau coba lagi.',
            ];
        }

        $cartUrl = url_for('cart');
        if ($success && app_path_is_under_products($redirect) && $redirect !== $cartUrl) {
            $_SESSION['flash_cart_notice'] = [
                'type' => 'success',
                'message' => 'Produk ditambahkan ke keranjang.',
            ];
        }

        header('Location: ' . $redirect);
        exit;
    }

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function updateItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url_for('cart'));
            exit;
        }

        $this->requireLogin();

        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if ($cartItemId > 0) {
            $this->cartModel->updateQuantity($this->userId, $cartItemId, $quantity);
        }

        header('Location: ' . url_for('cart'));
        exit;
    }

    public function removeItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url_for('cart'));
            exit;
        }

        $this->requireLogin();

        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);

        if ($cartItemId > 0) {
            $this->cartModel->removeItem($this->userId, $cartItemId);
        }

        header('Location: ' . url_for('cart'));
        exit;
    }

    /**
     * Redirect aman dalam site (cek path dalam), default /cart.
     */
    private function safeInternalRedirect(string $next): string
    {
        $next = trim($next);
        $default = url_for('cart');
        if ($next === '') {
            return $default;
        }
        if ($next[0] !== '/' || strpos($next, '//') !== false || preg_match('#\s#', $next)) {
            return $default;
        }
        return $next;
    }

    private function requireLogin(): void
    {
        if ($this->userId === null) {
            if ($this->isAjaxRequest()) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
                exit;
            }

            header('Location: ' . url_for('login'));
            exit;
        }
    }
}

?>