<?php
namespace App\controllers;

use App\models\Cart;

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

        $this->userId = $_SESSION['user']['id'] ?? 1;
        $this->userPoints = $_SESSION['user']['coins'] ?? 500;
        $this->cartModel = new Cart();
    }

    public function cartView()
    {
        $cartItems = $this->cartModel->getCartItems($this->userId);
        $cartCount = count($cartItems);
        $cartTotalPoints = 0;
        foreach ($cartItems as $item) {
            $cartTotalPoints += ($item['quantity'] * ($item['harga'] ?? 0));
        }

        require_once __DIR__ . '/../views/cart.php';
    }

    public function addItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products');
            exit;
        }

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

        header('Location: /cart');
        exit;
    }

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function updateItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            exit;
        }

        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if ($cartItemId > 0) {
            $this->cartModel->updateQuantity($this->userId, $cartItemId, $quantity);
        }

        header('Location: /cart');
        exit;
    }

    public function removeItem()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            exit;
        }

        $cartItemId = (int) ($_POST['cart_item_id'] ?? 0);

        if ($cartItemId > 0) {
            $this->cartModel->removeItem($this->userId, $cartItemId);
        }

        header('Location: /cart');
        exit;
    }
}

?>