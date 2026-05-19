<?php
namespace App\models;

use App\Core\Database;

class Cart
{
    private $db;
    private $productTable;
    private $cartTable;
    private $checkoutTable;
    private $productColumns = [];
    private $cartHasAddedAt = false;
    private $cartHasCreatedAt = false;
    private $cartHasUpdatedAt = false;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->productTable = $this->resolveProductTable();
        $this->ensureCartTableExists();
        $this->ensureCheckoutTableExists();
        $this->cartTable = $this->resolveCartTable();
        if ($this->cartTable !== null) {
            $this->resolveCartColumns();
        }
        if ($this->productTable !== null) {
            $this->resolveProductColumns();
        }
    }

    private function resolveProductColumns(): void
    {
        if ($this->productTable === null) {
            return;
        }

        try {
            $columns = $this->db->select("SHOW COLUMNS FROM {$this->productTable}");
            $columnNames = [];
            foreach ($columns as $col) {
                $columnNames[] = strtolower($col['Field'] ?? '');
            }

            // Map nama kolom produk dengan berbagai convention
            $this->productColumns = [
                'id' => $this->findColumn(['id'], $columnNames),
                'name' => $this->findColumn(['nama_produk', 'product_name', 'name', 'product'], $columnNames),
                'price' => $this->findColumn(['harga', 'price', 'cost'], $columnNames),
                'seller' => $this->findColumn(['nama_penjual', 'seller', 'seller_name', 'penjual'], $columnNames),
                'image' => $this->findColumn(['gambar', 'image', 'foto', 'product_image'], $columnNames),
            ];
        } catch (\Exception $e) {
            error_log('Error resolving product columns: ' . $e->getMessage());
        }
    }

    private function findColumn(array $candidates, array $available): ?string
    {
        foreach ($candidates as $candidate) {
            if (in_array(strtolower($candidate), $available, true)) {
                return $candidate;
            }
        }
        return null;
    }

    private function ensureCartTableExists(): void
    {
        try {
            // Cek apakah tabel cart ada
            $result = $this->db->select("SHOW TABLES LIKE 'cart'");
            if (!empty($result)) {
                return; // Tabel sudah ada
            }

            // Tabel tidak ada, buat sekarang
            $sql = "CREATE TABLE IF NOT EXISTS cart (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_user (user_id),
                INDEX idx_product (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            $this->db->execute($sql);
        } catch (\Exception $e) {
            error_log('Cart table creation error: ' . $e->getMessage());
        }
    }

    private function ensureCheckoutTableExists(): void
    {
        try {
            // Cek apakah tabel checkout ada
            $result = $this->db->select("SHOW TABLES LIKE 'checkout'");
            if (!empty($result)) {
                return; // Tabel sudah ada
            }

            // Tabel tidak ada, buat sekarang
            $sql = "CREATE TABLE IF NOT EXISTS checkout (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL DEFAULT 1,
                checked_out_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_user (user_id),
                INDEX idx_product (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            $this->db->execute($sql);
        } catch (\Exception $e) {
            error_log('Checkout table creation error: ' . $e->getMessage());
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
        // Utamakan `cart` (skema setup-db proyek) agar tidak tertimpa tabel `cart_items` lain yang beda struktur.
        foreach (['cart', 'cart_items'] as $name) {
            $row = $this->db->selectOne("SHOW TABLES LIKE '{$name}'");
            if (!empty($row) && $this->cartTableHasCoreColumns($name)) {
                return $name;
            }
        }

        return null;
    }

    private function cartTableHasCoreColumns(string $table): bool
    {
        $cols = $this->db->select("SHOW COLUMNS FROM `{$table}`");
        $names = [];
        foreach ($cols as $c) {
            $names[] = strtolower((string) ($c['Field'] ?? $c['field'] ?? ''));
        }

        return in_array('user_id', $names, true)
            && in_array('product_id', $names, true)
            && in_array('quantity', $names, true);
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

        // Default columns jika tidak ada yang di-resolve
        $nameCol = $this->productColumns['name'];
        $priceCol = $this->productColumns['price'];
        $sellerCol = $this->productColumns['seller'];
        $imageCol = $this->productColumns['image'];

        // Fallback ke default jika tidak ditemukan
        if (!$nameCol) {
            $nameCol = 'nama_produk';
        }
        if (!$priceCol) {
            $priceCol = 'harga';
        }
        if (!$sellerCol) {
            $sellerCol = 'nama_penjual';
        }
        if (!$imageCol) {
            $imageCol = 'gambar';
        }

        try {
            $sql = "SELECT ci.id AS cart_item_id,
                           ci.product_id,
                           ci.quantity,
                           COALESCE(p.{$nameCol}, 'Unknown') AS nama_produk,
                           COALESCE(p.{$priceCol}, 0) AS harga,
                           COALESCE(p.{$sellerCol}, '') AS nama_penjual,
                           COALESCE(p.{$imageCol}, '') AS gambar
                    FROM {$this->cartTable} ci
                    LEFT JOIN {$this->productTable} p ON ci.product_id = p.id
                    WHERE ci.user_id = ?";

            return $this->db->select($sql, [$userId]);
        } catch (\Exception $e) {
            error_log('Error getting cart items: ' . $e->getMessage());
            // Return simple fallback query without product details
            try {
                $sql = "SELECT ci.id AS cart_item_id,
                               ci.product_id,
                               ci.quantity,
                               'Unknown' AS nama_produk,
                               0 AS harga,
                               '' AS nama_penjual,
                               '' AS gambar
                        FROM {$this->cartTable} ci
                        WHERE ci.user_id = ?";
                return $this->db->select($sql, [$userId]);
            } catch (\Exception $e2) {
                error_log('Fallback query failed: ' . $e2->getMessage());
                return [];
            }
        }
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

    public function getCartItemIdByProduct(int $userId, int $productId): ?int
    {
        $row = $this->db->selectOne(
            "SELECT id FROM {$this->cartTable} WHERE user_id = ? AND product_id = ? LIMIT 1",
            [$userId, $productId]
        );

        return !empty($row) ? (int) $row['id'] : null;
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

    public function clearCart(int $userId): bool
    {
        if ($this->cartTable === null) {
            return false;
        }

        return $this->db->execute(
            "DELETE FROM {$this->cartTable} WHERE user_id = ?",
            [$userId]
        );
    }

    /**
     * Move selected items dari cart ke checkout table
     * @param int $userId User ID
     * @param array $cartItemIds Array dari cart item IDs yang akan di-checkout
     * @return bool
     */
    public function moveItemsToCheckout(int $userId, array $cartItemIds): bool
    {
        if ($this->cartTable === null || empty($cartItemIds)) {
            return false;
        }

        try {
            // Ambil data item dari cart
            $placeholders = implode(',', array_fill(0, count($cartItemIds), '?'));
            $sql = "SELECT product_id, quantity FROM {$this->cartTable} 
                    WHERE user_id = ? AND id IN ({$placeholders})";
            
            $params = [$userId];
            $params = array_merge($params, $cartItemIds);
            
            $cartItems = $this->db->select($sql, $params);

            // Insert ke checkout table
            foreach ($cartItems as $item) {
                $insertSql = "INSERT INTO checkout (user_id, product_id, quantity) 
                              VALUES (?, ?, ?)";
                $this->db->execute($insertSql, [
                    $userId,
                    $item['product_id'],
                    $item['quantity']
                ]);
            }

            // Hapus dari cart
            $deleteSql = "DELETE FROM {$this->cartTable} 
                          WHERE user_id = ? AND id IN ({$placeholders})";
            return $this->db->execute($deleteSql, $params);

        } catch (\Exception $e) {
            error_log('Error moving items to checkout: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get checkout items untuk user tertentu
     */
    public function getCheckoutItems(int $userId): array
    {
        try {
            $nameCol = $this->productColumns['name'] ?? 'nama_produk';
            $priceCol = $this->productColumns['price'] ?? 'harga';
            $sellerCol = $this->productColumns['seller'] ?? 'nama_penjual';
            $imageCol = $this->productColumns['image'] ?? 'gambar';

            $sql = "SELECT co.id AS checkout_item_id,
                           co.product_id,
                           co.quantity,
                           COALESCE(p.{$nameCol}, 'Unknown') AS nama_produk,
                           COALESCE(p.{$priceCol}, 0) AS harga,
                           COALESCE(p.{$sellerCol}, '') AS nama_penjual,
                           COALESCE(p.{$imageCol}, '') AS gambar,
                           co.checked_out_at
                    FROM checkout co
                    LEFT JOIN {$this->productTable} p ON co.product_id = p.id
                    WHERE co.user_id = ?
                    ORDER BY co.checked_out_at DESC";

            return $this->db->select($sql, [$userId]);
        } catch (\Exception $e) {
            error_log('Error getting checkout items: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clear checkout items untuk user tertentu
     */
    public function clearCheckout(int $userId): bool
    {
        try {
            return $this->db->execute(
                "DELETE FROM checkout WHERE user_id = ?",
                [$userId]
            );
        } catch (\Exception $e) {
            error_log('Error clearing checkout: ' . $e->getMessage());
            return false;
        }
    }
}
