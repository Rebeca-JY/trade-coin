<?php
namespace App\models;

use App\core\Database;

class Cart
{
    private $db;
    private $productTable;
    private $cartTable;
    private $cartHasAddedAt = false;
    private $cartHasCreatedAt = false;
    private $cartHasUpdatedAt = false;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->productTable = $this->resolveProductTable();
        $this->cartTable = $this->resolveCartTable();
        if ($this->cartTable !== null) {
            $this->resolveCartColumns();
        }
    }

    private function resolveProductTable()
    {
        $table = $this->db->selectOne("SHOW TABLES LIKE 'products'");
        if (!empty($table)) {
            return 'products';
        }

        $table = $this->db->selectOne("SHOW TABLES LIKE 'product'");
        if (!empty($table)) {
            return 'product';
        }

        return 'product';
    }

    private function resolveCartTable()
    {
        $table = $this->db->selectOne("SHOW TABLES LIKE 'cart_items'");
        if (!empty($table)) {
            return 'cart_items';
        }

        $table = $this->db->selectOne("SHOW TABLES LIKE 'cart'");
        if (!empty($table)) {
            return 'cart';
        }

        return null;
    }

    private function resolveCartColumns(): void
    {
        if ($this->cartTable === null) {
            return;
        }

        $columns = $this->db->select("SHOW COLUMNS FROM {$this->cartTable}");
        foreach ($columns as $column) {
            $name = $column['Field'] ?? $column['field'] ?? '';
            if ($name === 'added_at') {
                $this->cartHasAddedAt = true;
            }
            if ($name === 'created_at') {
                $this->cartHasCreatedAt = true;
            }
            if ($name === 'updated_at') {
                $this->cartHasUpdatedAt = true;
            }
        }
    }

    public function getCartItems(int $userId): array
    {
        if ($this->cartTable === null) {
            return [];
        }

        $sql = "SELECT ci.id AS cart_item_id,
                       ci.product_id,
                       ci.quantity,
                       COALESCE(p.nama_produk, p.product_name) AS nama_produk,
                       COALESCE(p.harga, p.price, 0) AS harga,
                       COALESCE(p.nama_penjual, p.seller, '') AS nama_penjual,
                       COALESCE(p.gambar, p.product_image, '') AS gambar
                FROM {$this->cartTable} ci
                LEFT JOIN {$this->productTable} p ON ci.product_id = p.id
                WHERE ci.user_id = ?";

        return $this->db->select($sql, [$userId]);
    }

    public function addItem(int $userId, int $productId, int $quantity): bool
    {
        if ($quantity < 1) {
            return false;
        }

        if ($this->cartTable === null) {
            return false;
        }

        $existing = $this->db->selectOne(
            "SELECT id, quantity FROM {$this->cartTable} WHERE user_id = ? AND product_id = ?",
            [$userId, $productId]
        );

        if (!empty($existing)) {
            $newQuantity = $existing['quantity'] + $quantity;
            $sql = "UPDATE {$this->cartTable} SET quantity = ?";
            if ($this->cartHasUpdatedAt) {
                $sql .= ", updated_at = NOW()";
            }
            $sql .= " WHERE id = ?";

            return $this->db->execute($sql, [$newQuantity, $existing['id']]);
        }

        $columns = ['user_id', 'product_id', 'quantity'];
        $values = [$userId, $productId, $quantity];

        if ($this->cartHasCreatedAt && $this->cartHasUpdatedAt) {
            $columns[] = 'created_at';
            $columns[] = 'updated_at';
            $values[] = date('Y-m-d H:i:s');
            $values[] = date('Y-m-d H:i:s');
        } elseif ($this->cartHasAddedAt) {
            $columns[] = 'added_at';
            $values[] = date('Y-m-d H:i:s');
        }

        $columnList = implode(', ', $columns);
        $placeholderList = implode(', ', array_fill(0, count($columns), '?'));

        return $this->db->execute(
            "INSERT INTO {$this->cartTable} ({$columnList}) VALUES ({$placeholderList})",
            $values
        );
    }

    public function updateQuantity(int $userId, int $cartItemId, int $quantity): bool
    {
        if ($this->cartTable === null) {
            return false;
        }

        if ($quantity <= 0) {
            return $this->removeItem($userId, $cartItemId);
        }

        $sql = "UPDATE {$this->cartTable} SET quantity = ?";
        if ($this->cartHasUpdatedAt) {
            $sql .= ", updated_at = NOW()";
        }
        $sql .= " WHERE id = ? AND user_id = ?";

        return $this->db->execute($sql, [$quantity, $cartItemId, $userId]);
    }

    public function removeItem(int $userId, int $cartItemId): bool
    {
        if ($this->cartTable === null) {
            return false;
        }

        return $this->db->execute(
            "DELETE FROM {$this->cartTable} WHERE id = ? AND user_id = ?",
            [$cartItemId, $userId]
        );
    }
}
