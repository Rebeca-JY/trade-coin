<?php

namespace App\Core;

/**
 * Database Class - Menangani koneksi dan query ke database
 * Menggunakan Prepared Statements untuk keamanan
 * Flow:
 * 1. Koneksi ke database MySQL menggunakan mysqli
 * 2. Semua query menggunakan prepared statements (secure terhadap SQL injection)
 * 3. Method umum: select(), selectOne(), insert(), update(), delete()
 * 4. Menggunakan bindParam() untuk parameter binding yang aman
 */
class Database
{
    private $host;
    private $user;
    private $pass;
    private $db;
    private $conn;
    private $result;
    private $stmt;

    /**
     * Constructor - Inisialisasi koneksi database
     * @param string $host Host database (default: localhost)
     * @param string $user Username database (default: root)
     * @param string $pass Password database (default: "")
     * @param string $db Nama database (default: tradecoin)
     */
    public function __construct($host = 'localhost', $user = 'root', $pass = '', $db = 'tradecoin')
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;

        // Buat koneksi
        $this->connect();
    }

    /**
     * Membuat koneksi ke database
     * Jika gagal, akan menampilkan error
     */
    private function connect()
    {
        $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);

        if (!$this->conn) {
            die('Koneksi Database Gagal: ' . mysqli_connect_error());
        }

        // Set charset UTF8
        mysqli_set_charset($this->conn, 'utf8mb4');
    }

    /**
     * Menjalankan query SELECT dengan prepared statement
     * Contoh:
     * $db->select('SELECT * FROM products WHERE status = ?', ['active']);
     * 
     * @param string $query Query SQL dengan placeholder (?)
     * @param array $params Parameter yang akan di-bind (opsional)
     * @return array Array berisi semua hasil query
     */
    public function select($query, $params = [])
    {
        $this->stmt = $this->conn->prepare($query);

        if ($params) {
            // Bind parameter
            $types = $this->getParamTypes($params);
            $this->stmt->bind_param($types, ...$params);
        }

        $this->stmt->execute();
        $this->result = $this->stmt->get_result();

        $data = [];
        while ($row = $this->result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Menjalankan query SELECT yang hanya return 1 baris
     * Contoh:
     * $product = $db->selectOne('SELECT * FROM products WHERE id = ?', [5]);
     * 
     * @param string $query Query SQL dengan placeholder (?)
     * @param array $params Parameter yang akan di-bind (opsional)
     * @return array Single row hasil query, atau empty array jika tidak ada data
     */
    public function selectOne($query, $params = [])
    {
        $this->stmt = $this->conn->prepare($query);

        if ($params) {
            $types = $this->getParamTypes($params);
            $this->stmt->bind_param($types, ...$params);
        }

        $this->stmt->execute();
        $this->result = $this->stmt->get_result();

        return $this->result->fetch_assoc() ?? [];
    }

    /**
     * INSERT data ke database
     * Contoh:
     * $db->insert('products', [
     *     'nama_produk' => 'Laptop',
     *     'harga' => 5000000,
     *     'penjual_id' => 1
     * ]);
     * 
     * @param string $table Nama tabel
     * @param array $data Array berisi [kolom => nilai]
     * @return bool True jika insert berhasil
     */
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->stmt = $this->conn->prepare($query);

        if (!$this->stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }

        $types = $this->getParamTypes(array_values($data));
        $this->stmt->bind_param($types, ...array_values($data));

        return $this->stmt->execute();
    }

    /**
     * UPDATE data di database
     * Contoh:
     * $db->update('products', 
     *     ['nama_produk' => 'Laptop Gaming', 'harga' => 6000000],
     *     ['id' => 5]
     * );
     * 
     * @param string $table Nama tabel
     * @param array $data Array berisi [kolom => nilai] yang akan diupdate
     * @param array $where Array berisi [kolom => nilai] untuk kondisi WHERE
     * @return bool True jika update berhasil
     */
    public function update($table, $data, $where)
    {
        $setClause = implode(', ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($data)));

        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($where)));

        $query = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
        $this->stmt = $this->conn->prepare($query);

        if (!$this->stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }

        $values = array_merge(array_values($data), array_values($where));
        $types = $this->getParamTypes($values);
        $this->stmt->bind_param($types, ...$values);

        return $this->stmt->execute();
    }

    /**
     * DELETE data dari database
     * Contoh:
     * $db->delete('products', ['id' => 5]);
     * 
     * @param string $table Nama tabel
     * @param array $where Array berisi [kolom => nilai] untuk kondisi WHERE
     * @return bool True jika delete berhasil
     */
    public function delete($table, $where)
    {
        $whereClause = implode(' AND ', array_map(function ($key) {
            return "{$key} = ?";
        }, array_keys($where)));

        $query = "DELETE FROM {$table} WHERE {$whereClause}";
        $this->stmt = $this->conn->prepare($query);

        if (!$this->stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }

        $types = $this->getParamTypes(array_values($where));
        $this->stmt->bind_param($types, ...array_values($where));

        return $this->stmt->execute();
    }

    /**
     * Helper: Tentukan tipe parameter untuk bind_param
     * 's' = string, 'i' = integer, 'd' = double, 'b' = blob
     * @param array $params Array parameter
     * @return string String tipe parameter (contoh: "sis")
     */
    private function getParamTypes($params)
    {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 's'; // default string
            }
        }
        return $types;
    }

    /**
     * Mendapatkan ID dari insert terakhir
     * @return int ID yang di-generate
     */
    public function lastInsertId()
    {
        return $this->conn->insert_id;
    }

    /**
     * Mendapatkan jumlah baris yang affected dari query terakhir
     * @return int Jumlah baris
     */
    public function affectedRows()
    {
        return $this->stmt->affected_rows;
    }

    /**
     * Close koneksi database
     */
    public function closeConnection()
    {
        $this->conn->close();
    }
}
?>
