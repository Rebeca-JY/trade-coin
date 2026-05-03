<?php

namespace App\models;

use App\Core\Database;

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
    private $table = 'product';

    /**
     * Constructor - Initialize Database connection
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Ambil SEMUA produk (untuk listing di halaman produk)
     * 
     * PENTING: Method ini NOT menampilkan ID ke user
     * Hanya ambil: nama_produk, harga, nama_penjual, deskripsi, gambar, status
     * 
     * @param string $status Filter status (default: semua)
     * @return array Array berisi daftar produk
     */
    public function getAllProducts($status = '')
    {
        $query = "SELECT id, product_name as nama_produk, price as harga, seller as nama_penjual 
                  FROM {$this->table}";

        return $this->db->select($query);
    }

    /**
     * Ambil SATU produk berdasarkan nama produk (untuk detail page)
     * Kenapa pakai nama_produk? Karena ID tidak boleh di-expose ke user.
     * Nama produk diasumsikan unik atau digabung dengan penjual.
     * 
     * Alternatif: Bisa juga pakai kombinasi nama_produk + nama_penjual
     * 
     * @param string $namaProduk Nama produk yang dicari
     * @return array Data produk (tanpa ID), atau array kosong jika tidak ada
     */
    public function getProductByName($namaProduk)
    {
        $query = "SELECT id, product_name as nama_produk, price as harga, seller as nama_penjual 
                  FROM {$this->table} 
                  WHERE product_name = ?";
        
        return $this->db->selectOne($query, [$namaProduk]);
    }

    /**
     * Ambil produk berdasarkan ID (Internal use - untuk admin/backend operations)
     * 
     * Method ini HANYA digunakan di backend (Controller)
     * Ketika ada operasi admin seperti edit/delete
     * 
     * @param int $id ID produk
     * @return array Data produk lengkap termasuk ID
     */
    public function getProductById($id)
    {
        $query = "SELECT id, product_name as nama_produk, price as harga, seller as nama_penjual FROM {$this->table} WHERE id = ?";
        return $this->db->selectOne($query, [$id]);
    }

    /**
     * Ambil produk berdasarkan nama penjual
     * Gunakan untuk menampilkan semua produk dari satu penjual
     * 
     * @param string $namaPenjual Nama penjual
     * @return array Array berisi semua produk dari penjual tersebut
     */
    public function getProductsByPenjual($namaPenjual)
    {
        $query = "SELECT id, product_name as nama_produk, price as harga, seller as nama_penjual 
                  FROM {$this->table} 
                  WHERE seller = ?";
        
        return $this->db->select($query, [$namaPenjual]);
    }

    /**
     * Cari produk berdasarkan keyword
  
     */
    public function searchProducts($keyword)
    {
        $keyword = "%{$keyword}%"; // Tambah wildcard untuk LIKE
        
        $query = "SELECT id, product_name as nama_produk, price as harga, seller as nama_penjual 
                  FROM {$this->table} 
                  WHERE product_name LIKE ?
                  ORDER BY id DESC";
        
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
        // Validasi field wajib
        $required = ['nama_produk', 'harga', 'nama_penjual'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        // Set default values
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->insert($this->table, $data);
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
        return $this->db->update($this->table, $data, ['id' => $id]);
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
