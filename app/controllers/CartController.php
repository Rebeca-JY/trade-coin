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
     * Display checkout preview - konfirmasi sebelum pembayaran
     * Menampilkan produk yang dipilih dari cart
     */
    public function checkoutPreviewView()
    {
        $this->requireLogin();

        // Ambil semua item di cart
        $cartItems = $this->cartModel->getCartItems($this->userId);

        // Jika request ke /checkout via POST, simpan selected IDs ke session.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestedIds = $_POST['selected_ids'] ?? [];
            if (!is_array($requestedIds)) {
                $requestedIds = $requestedIds !== '' ? [(int) $requestedIds] : [];
            }
            $requestedIds = array_values(array_unique(array_filter(array_map('intval', $requestedIds))));
            $_SESSION['checkout_selected_ids'] = $requestedIds;
        }

        // Ambil selected IDs dari session
        $selectedIds = $_SESSION['checkout_selected_ids'] ?? [];

        // Filter hanya item yang selected
        $selectedItems = [];
        $byId = [];
        foreach ($cartItems as $item) {
            $byId[(int) $item['cart_item_id']] = $item;
        }

        foreach ($selectedIds as $itemId) {
            if (isset($byId[$itemId])) {
                $selectedItems[] = $byId[$itemId];
            }
        }

        // Ambil saldo user
        $points = new UserPoint();
        $userCoins = (int) $points->getUserPoints($this->userId);

        require_once __DIR__ . '/../views/checkout.php';
    }

    /**
     * Display checkout history - riwayat pembelian user
     */
    public function checkoutHistoryView()
    {
        $this->requireLogin();

        $checkoutItems = $this->cartModel->getCheckoutItems($this->userId);
        
        $checkoutFlash = $_SESSION['flash_checkout'] ?? null;
        unset($_SESSION['flash_checkout']);

        require_once __DIR__ . '/../views/history.php';
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

        // Store selected IDs untuk checkout preview
        $_SESSION['checkout_selected_ids'] = $requestedIds;

        // Redirect ke checkout preview
        header('Location: ' . url_for('checkout'));
        exit;
    }

    /**
     * Confirm dan process payment dari checkout preview
     */
    public function checkoutConfirm()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url_for('cart'));
            exit;
        }

        $this->requireLogin();

        $cartItems = $this->cartModel->getCartItems($this->userId);
        $requestedIds = $_POST['selected_ids'] ?? $_SESSION['checkout_selected_ids'] ?? [];

        if (!is_array($requestedIds)) {
            $requestedIds = $requestedIds !== '' ? [(int) $requestedIds] : [];
        }
        $requestedIds = array_values(array_unique(array_filter(array_map('intval', $requestedIds))));

        if (empty($requestedIds)) {
            $_SESSION['flash_checkout'] = [
                'type' => 'error',
                'message' => 'Item checkout tidak ditemukan. Coba lagi dari cart.',
            ];
            header('Location: ' . url_for('cart'));
            exit;
        }

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
                'message' => 'Item atau total tidak valid. Coba lagi.',
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
            
            // Move selected items dari cart ke checkout table
            $this->cartModel->moveItemsToCheckout($this->userId, $requestedIds);
            
            unset($_SESSION['checkout_selected_ids']);
            $_SESSION['user']['coins'] = (int) $points->getUserPoints($this->userId);
            $_SESSION['flash_checkout'] = [
                'type' => 'success',
                'message' => "Pembelian berhasil! {$total} coin telah digunakan.",
            ];
            // Redirect ke halaman history (riwayat pembelian)
            header('Location: ' . url_for('history'));
            exit;
        } catch (\Throwable $e) {
            $_SESSION['flash_checkout'] = [
                'type' => 'error',
                'message' => 'Pembayaran gagal. Coba lagi atau cek saldo coin.',
            ];
            header('Location: ' . url_for('cart'));
            exit;
        }
    }

    public function addItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url_for('/products'));
            exit;
        }

        $this->requireLogin();

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        // Pastikan product_id valid
        if ($productId <= 0) {
            $_SESSION['flash_cart_notice'] = [
                'type' => 'error',
                'message' => 'Product ID tidak valid. Coba lagi.',
            ];
            header('Location: ' . url_for('/products'));
            exit;
        }

        // Tambah ke cart
        $success = $this->cartModel->addItem($this->userId, $productId, $quantity);

        // Jika AJAX request, return JSON
        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'product_id' => $productId,
                'quantity' => $quantity,
                'user_id' => $this->userId,
            ]);
            exit;
        }

        // Get redirect URL dari form
        $redirect = $this->safeInternalRedirect((string) ($_POST['next'] ?? ''));

        // Set flash message
        if ($success) {
            $_SESSION['flash_cart_notice'] = [
                'type' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
            ];

            // Jika direct ke checkout, pilih item baru dalam session checkout
            if ($redirect === '/checkout') {
                $cartItemId = $this->cartModel->getCartItemIdByProduct($this->userId, $productId);
                if ($cartItemId !== null) {
                    $_SESSION['checkout_selected_ids'] = [$cartItemId];
                }
            }

            // Jika redirect adalah halaman produk, langsung ke cart
            if (app_path_is_under_products($redirect)) {
                header('Location: ' . url_for('/cart'));
                exit;
            }
        } else {
            $_SESSION['flash_cart_notice'] = [
                'type' => 'error',
                'message' => 'Gagal menambahkan ke keranjang. Pastikan Anda sudah login dan coba lagi.',
            ];
        }

        // Redirect
        $finalUrl = $success ? (app_path_is_under_products($redirect) ? url_for('/cart') : $redirect) : url_for('/products');
        header('Location: ' . $finalUrl);
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