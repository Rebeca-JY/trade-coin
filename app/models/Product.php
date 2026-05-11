<?php

namespace App\models;

/**
 * Product Model - Menangani semua operasi data produk
 
 * Logika Database Produk:
 * ├─ Tabel: products
 * ├─ Fields:
 * │  ├─ id (INT, PRIMARY KEY, AUTO INCREMENT) - Unique identifier, TIDAK ditampilkan ke user
 * │  ├─ nama_produk (VARCHAR 255) - Nama barang yang dijual
 * │  ├─ harga (DECIMAL 10,2) - Harga jual produk
 * │  ├─ nama_penjual (VARCHAR 255) - Nama orang yang menjual
 * │  ├─ deskripsi (TEXT) - Deskripsi detail produk
 * │  ├─ gambar (VARCHAR 255) - Path ke file gambar produk
 * │  ├─ status (ENUM) - active/inactive
 * │  └─ created_at (TIMESTAMP) - Waktu produk dibuat
 * 
 * Flow Data Product:
 * 1. Ketika ambil data produk, ID disimpan di database tapi TIDAK dikirim ke View
 * 2. Frontend menggunakan nama_produk atau field lain sebagai identifier unik
 * 3. Ketika ada operasi yang butuh ID (edit/delete), ambil dari routing atau session
 */
class Product
{
    private $db;
    private $table = 'products';
    private $columns = [];

    /**
     * Constructor - Initialize Database connection
     */
    public function __construct()
    {
        $this->db = db();
        $this->resolveTable();
        $this->resolveColumns();
    }

    private function resolveTable()
    {
        $tables = $this->db->select("SHOW TABLES LIKE 'products'");
        if (!empty($tables)) {
            $this->table = 'products';
            return;
        }

        $tables = $this->db->select("SHOW TABLES LIKE 'product'");
        if (!empty($tables)) {
            $this->table = 'product';
            return;
        }

        die('Tabel produk tidak ditemukan di database.');
    }

    private function resolveColumns()
    {
        $columns = array_column($this->db->select("SHOW COLUMNS FROM {$this->table}"), 'Field');

        $this->columns = [
            'id' => $this->findColumn(['id'], $columns),
            'name' => $this->findColumn(['nama_produk', 'product_name', 'product', 'name'], $columns),
            'price' => $this->findColumn(['harga', 'price'], $columns),
            'stock' => $this->findColumn(['stock', 'jumlah', 'stok'], $columns),
            'seller' => $this->findColumn(['nama_penjual', 'seller', 'seller_name'], $columns),
            'description' => $this->findColumn(['deskripsi', 'product_desc', 'description'], $columns),
            'image' => $this->findColumn(['gambar', 'product_image', 'image'], $columns),
            'status' => $this->findColumn(['status', 'is_active'], $columns),
            'category' => $this->findColumn(['category', 'kategori'], $columns),
            'material' => $this->findColumn(['material', 'bahan'], $columns),
        ];
    }

    private function findColumn(array $candidates, array $columns)
    {
        foreach ($candidates as $candidate) {
            if (in_array($candidate, $columns, true)) {
                return $candidate;
            }
        }

        return null;
    }

    private function selectFields()
    {
        $fields = [
            $this->columns['id'] ? "{$this->columns['id']} AS id" : "NULL AS id",
            $this->columns['name'] ? "{$this->columns['name']} AS nama_produk" : "NULL AS nama_produk",
            $this->columns['price'] ? "{$this->columns['price']} AS harga" : "NULL AS harga",
            $this->columns['stock'] ? "{$this->columns['stock']} AS stock" : "NULL AS stock",
            $this->columns['seller'] ? "{$this->columns['seller']} AS nama_penjual" : "NULL AS nama_penjual",
            $this->columns['description'] ? "{$this->columns['description']} AS deskripsi" : "NULL AS deskripsi",
            $this->columns['image'] ? "{$this->columns['image']} AS gambar" : "NULL AS gambar",
            $this->columns['status'] ? "{$this->columns['status']} AS status" : "NULL AS status",
            $this->columns['category'] ? "{$this->columns['category']} AS category" : "NULL AS category",
            $this->columns['material'] ? "{$this->columns['material']} AS material" : "NULL AS material",
        ];

        return implode(', ', $fields);
    }

    private function keyToColumn(string $key)
    {
        $mapping = [
            'nama_produk' => $this->columns['name'],
            'product_name' => $this->columns['name'],
            'product' => $this->columns['name'],
            'name' => $this->columns['name'],
            'harga' => $this->columns['price'],
            'price' => $this->columns['price'],
            'stock' => $this->columns['stock'],
            'jumlah' => $this->columns['stock'],
            'stok' => $this->columns['stock'],
            'nama_penjual' => $this->columns['seller'],
            'seller' => $this->columns['seller'],
            'seller_name' => $this->columns['seller'],
            'deskripsi' => $this->columns['description'],
            'product_desc' => $this->columns['description'],
            'description' => $this->columns['description'],
            'gambar' => $this->columns['image'],
            'product_image' => $this->columns['image'],
            'image' => $this->columns['image'],
            'status' => $this->columns['status'],
            'is_active' => $this->columns['status'],
            'category' => $this->columns['category'],
            'kategori' => $this->columns['category'],
            'material' => $this->columns['material'],
            'bahan' => $this->columns['material'],
        ];

        return $mapping[$key] ?? null;
    }

    private function mapDataKeys(array $data)
    {
        $mapped = [];

        foreach ($data as $key => $value) {
            $column = $this->keyToColumn($key);
            if ($column !== null) {
                $mapped[$column] = $value;
            }
        }

        return $mapped;
    }

    public function getAllProducts($status = '')
    {
        $query = "SELECT {$this->selectFields()} FROM {$this->table}";
        return $this->db->select($query);
    }

    public function getProductByName($namaProduk)
    {
        $nameColumn = $this->columns['name'];
        if (!$nameColumn) {
            return [];
        }

        $query = "SELECT {$this->selectFields()} FROM {$this->table} WHERE {$nameColumn} = ?";
        return $this->db->selectOne($query, [$namaProduk]);
    }

    public function getProductById($id)
    {
        $idColumn = $this->columns['id'];
        if (!$idColumn) {
            return [];
        }

        $query = "SELECT {$this->selectFields()} FROM {$this->table} WHERE {$idColumn} = ?";
        return $this->db->selectOne($query, [$id]);
    }

    public function getProductsByPenjual($namaPenjual)
    {
        $sellerColumn = $this->columns['seller'];
        if (!$sellerColumn) {
            return [];
        }

        $query = "SELECT {$this->selectFields()} FROM {$this->table} WHERE {$sellerColumn} = ?";
        return $this->db->select($query, [$namaPenjual]);
    }

    public function searchProducts($keyword)
    {
        $nameColumn = $this->columns['name'];
        if (!$nameColumn) {
            return [];
        }

        $keyword = "%{$keyword}%";
        $query = "SELECT {$this->selectFields()} FROM {$this->table} WHERE {$nameColumn} LIKE ? ORDER BY {$this->columns['id']} DESC";
        return $this->db->select($query, [$keyword]);
    }

    /**
     * Tambah produk baru (INSERT) 
     * Flow:
     * 1. Admin form mengisi: nama_produk, harga, nama_penjual, deskripsi, gambar
     * 2. Data dikirim ke Controller
     * 3. Controller call method ini
     * 4. Database auto-generate ID
     * 5. Return TRUE/FALSE status
     * 
     * @param array $data Data produk baru [
     *     'nama_produk' => 'Laptop',
     *     'harga' => 5000000,
     *     'nama_penjual' => 'Toko ABC',
     *     'deskripsi' => '...',
     *     'gambar' => 'laptop.jpg',
     *     'status' => 'active'
     * ]
     * @return bool True jika berhasil, False jika gagal
     */
    public function createProduct($data)
    {
        $mappedData = $this->mapDataKeys($data);

        // Hapus kolom ID karena AUTO_INCREMENT
        $idColumn = $this->columns['id'];
        if ($idColumn && isset($mappedData[$idColumn])) {
            unset($mappedData[$idColumn]);
        }

        // Form jual barang tidak selalu mengirim stok — kalau kolom ada, isi default
        if ($this->columns['stock'] !== null) {
            $stockCol = $this->columns['stock'];
            if (!isset($mappedData[$stockCol]) || $mappedData[$stockCol] === '' || $mappedData[$stockCol] === null) {
                $mappedData[$stockCol] = 1;
            }
        }

        // Gambar boleh kosong string bila kolom wajib NOT NULL tanpa default
        if ($this->columns['image'] !== null) {
            $imgCol = $this->columns['image'];
            if (!isset($mappedData[$imgCol]) || $mappedData[$imgCol] === null) {
                $mappedData[$imgCol] = '';
            }
        }

        // Validasi field wajib berdasarkan mapping schema
        $required = ['nama_produk', 'harga'];
        if ($this->columns['seller'] !== null) {
            $required[] = 'nama_penjual';
        }

        foreach ($required as $field) {
            $column = $this->keyToColumn($field);
            if ($column === null || !isset($mappedData[$column]) || $mappedData[$column] === '') {
                return false;
            }
        }

        if ($this->columns['status'] !== null && !isset($mappedData[$this->columns['status']])) {
            $mappedData[$this->columns['status']] = 'active';
        }

        return $this->db->insert($this->table, $mappedData);
    }

    /**
     * Update produk (UPDATE)
     * Flow:
     * 1. Admin form mengisi data yang ingin diubah
     * 2. Controller menemukan produk berdasarkan ID
     * 3. Controller call method ini dengan ID dan data baru
     * 4. Database update field yang berubah
     * 5. Return TRUE/FALSE status
     * 
     * @param int $id ID produk yang mau diupdate
     * @param array $data Data yang diupdate [
     *     'nama_produk' => 'Laptop Gaming',
     *     'harga' => 6000000
     * ]
     * @return bool True jika berhasil
     */
    public function updateProduct($id, $data)
    {
        $mappedData = $this->mapDataKeys($data);
        $idColumn = $this->columns['id'] ?? 'id';

        return $this->db->update($this->table, $mappedData, [$idColumn => $id]);
    }

    /**
     * Hapus produk (DELETE)
     * Flow:
     * 1. Admin klik tombol delete di list produk
     * 2. Controller kirim request dengan ID produk
     * 3. Controller call method ini
     * 4. Database hapus row dengan ID tersebut
     * 5. Redirect ke halaman list produk
     * 
     * CATATAN: Beberapa sistem lebih baik soft delete (ubah status jadi inactive)
     * daripada hard delete untuk preservasi data history.
     * 
     * @param int $id ID produk yang mau dihapus
     * @return bool True jika berhasil
     */
    public function deleteProduct($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    /**
     * Soft delete - Set status menjadi inactive, bukan benar-benar hapus
     * Ini lebih aman karena:
     * - Data masih ada di database untuk audit trail
     * - Bisa di-restore jika perlu
     * - Tidak ada data orphaned di tabel lain (foreign keys)
     * 
     * @param int $id ID produk
     * @return bool True jika berhasil
     */
    public function softDeleteProduct($id)
    {
        return $this->updateProduct($id, ['status' => 'inactive']);
    }

    /**
     * Hitung total produk aktif
     * Berguna untuk dashboard admin
     * 
     * @return int Jumlah produk
     */
    public function countActiveProducts()
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'active'";
        $result = $this->db->selectOne($query);
        return $result['total'] ?? 0;
    }

    /**
     * Hitung total penjualan per penjual
     * Berguna untuk statistik penjual
     * 
     * @param string $namaPenjual
     * @return int Jumlah produk
     */
    public function countProductsByPenjual($namaPenjual)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE nama_penjual = ?";
        $result = $this->db->selectOne($query, [$namaPenjual]);
        return $result['total'] ?? 0;
    }

    /**
     * Ambil produk terbaru (untuk "New Products" section)
     * @param int $limit Berapa banyak produk (default: 6)
     * @return array Array berisi produk terbaru
     */
    public function getLatestProducts($limit = 6)
    {
        $query = "SELECT nama_produk, harga, nama_penjual, deskripsi, gambar 
                  FROM {$this->table} 
                  WHERE status = 'active'
                  ORDER BY created_at DESC 
                  LIMIT ?";
        
        return $this->db->select($query, [(int)$limit]);
    }

    /**
     * Ambil produk dengan harga termurah (untuk "Best Deal" section)
     * @param int $limit Berapa banyak produk
     * @return array Array produk dengan harga terendah
     */
    public function getCheapestProducts($limit = 6)
    {
        $query = "SELECT nama_produk, harga, nama_penjual, deskripsi, gambar 
                  FROM {$this->table} 
                  WHERE status = 'active'
                  ORDER BY harga ASC 
                  LIMIT ?";
        
        return $this->db->select($query, [(int)$limit]);
    }
}
?>
