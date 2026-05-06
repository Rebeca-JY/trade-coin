# 🗄️ DATABASE & LOGIKA MVC LENGKAP

Dokumentasi lengkap tentang struktur database, flow data, dan implementasi MVC dengan penjelasan detail tentang bagaimana ID produk disembunyikan dari user.

---

## 📊 STRUKTUR DATABASE

### Tabel 1: `users`

```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(255),
    nomor_hp VARCHAR(20),
    alamat TEXT,
    role ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
    toko_nama VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Penjelasan:**
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | INT, PK | Unique identifier (HIDDEN) |
| `username` | VARCHAR | Username login (UNIQUE) |
| `email` | VARCHAR | Email login (UNIQUE) |
| `password` | VARCHAR | Hash bcrypt (JANGAN plain text!) |
| `nama_lengkap` | VARCHAR | Nama asli user |
| `nomor_hp` | VARCHAR | Kontak user |
| `role` | ENUM | buyer / seller / admin |
| `toko_nama` | VARCHAR | Nama toko jika seller |

---

### Tabel 2: `products` ⭐ PENTING

```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_produk VARCHAR(255) NOT NULL,
    harga DECIMAL(12,2) NOT NULL,
    nama_penjual VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Penjelasan:**
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | INT, PK | **TIDAK ditampilkan ke customer!** |
| `nama_produk` | VARCHAR | Nama barang |
| `harga` | DECIMAL | Harga (bukan INT agar presisi) |
| `nama_penjual` | VARCHAR | Siapa yang jual |
| `deskripsi` | TEXT | Detail produk |
| `gambar` | VARCHAR | Filename di `/public/assets/foto/` |
| `status` | ENUM | active = tampil / inactive = hide |

**⚠️ PENTING TENTANG ID:**
```
Database punya: id=1, id=2, id=3, id=4, dst

Tapi yang DITAMPILKAN ke customer:
- nama_produk
- harga
- nama_penjual
- deskripsi
- gambar
- status

ID HIDDEN! Kenapa?
1. Keamanan: Hacker tidak bisa guess /products/1, /products/2, dst
2. Privacy: Tidak perlu expose struktur database
3. URL Safety: Jika produk dihapus, URL tidak akan 404 predictable

Jika customer klik detail, URL yang dikirim:
❌ JANGAN: /products/1 (expose ID)
✓ BOLEH: /products/Laptop-Gaming-ASUS (encode nama)
```

---

### Tabel 3: `cart`

```sql
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

**Penjelasan:**
- Temporary storage untuk keranjang belanja
- Foreign keys: Jika user/produk dihapus, cart otomatis dihapus
- Quantity: Berapa jumlah produk yang dimasukkan

---

## 🔐 SECURITY: ID PRODUCT

### Problem: Expose ID di URL

Misalkan ada URL seperti ini:
```
http://localhost:8000/products/1
http://localhost:8000/products/2
http://localhost:8000/products/3
```

**Risiko:**
1. **Enumerasi**: Bot bisa scrape semua produk dengan loop `/products/1` sampai `/products/1000`
2. **Predictable**: Hacker tahu struktur database
3. **IDOR**: Insider Related Object Reference vulnerability

Contoh bad request:
```php
// User A coba akses produk milik user B (yang seharusnya tidak boleh?)
GET /api/products/5/edit       ← Bisa modify?
GET /products/5/admin          ← Bisa lihat admin data?
```

---

### Solution: Sembunyikan ID

**Opsi 1: Gunakan Nama Produk (Simple & Effective)**
```
URL: /products/Laptop-Gaming-ASUS
     └─ Parameter: nama produk yang di-encode

Query: SELECT * FROM products WHERE nama_produk = ?
```

**Opsi 2: Gunakan Slug**
```
URL: /products/laptop-gaming-asus-001
     └─ Slug: lowercase, hyphen-separated, unique suffix

Query: SELECT * FROM products WHERE slug = ?
Benefit: URL cantik dan SEO-friendly
```

**Opsi 3: Hash/Encoded ID**
```
URL: /products/a1b2c3d4e5f6g7h8
     └─ ID dienkrypt jadi hash

Query: 
  $id = decrypt('a1b2c3d4e5f6g7h8')
  SELECT * FROM products WHERE id = ?
Benefit: Security through obscurity
```

---

## 🔄 FLOW DATA LENGKAP

### Flow 1️⃣: Customer Lihat Daftar Produk

```
User access: http://localhost:8000/products
                        │
                        ▼
    public/index.php (Entry point)
                        │
                        ▼
    Router.php analyze URL: GET /products
                        │
                        ▼
    Match route: /products → ProductController->index()
                        │
                        ▼
    ProductController->index()
    ┌─────────────────────────────────────┐
    │ $productModel = new Product()       │
    │ $products = $productModel->         │
    │   getAllProducts()                  │
    │                                     │
    │ // Query yang dijalankan:           │
    │ SELECT nama_produk,                 │
    │        harga,                       │
    │        nama_penjual,                │
    │        deskripsi,                   │
    │        gambar,                      │
    │        status                       │
    │ FROM products                       │
    │ // ⭐ PERHATIAN: TIDAK ADA ID!     │
    │                                     │
    │ $data = [                           │
    │   'products' => $products,          │
    │   'totalProducts' => count(...)     │
    │ ]                                   │
    │                                     │
    │ render('products/ProductList', $data)
    └─────────────────────────────────────┘
                        │
                        ▼
    View: app/views/products/ProductList.php
    ┌─────────────────────────────────────┐
    │ <?php foreach($products as $p): ?>  │
    │   <div class="product-card">        │
    │     <h3><?=$p['nama_produk']?></h3> │
    │     <p><?=$p['harga']?></p>         │
    │     <p><?=$p['nama_penjual']?></p>  │
    │     <img src="<?=$p['gambar']?>"/>  │
    │     <!-- TIDAK ada ID! -->          │
    │     <a href="/products/             │
    │        <?=urlencode($p[...])?>">    │
    │        Lihat Detail                 │
    │     </a>                            │
    │   </div>                            │
    │ <?php endforeach; ?>                │
    └─────────────────────────────────────┘
                        │
                        ▼
    Browser render halaman daftar produk
    ✓ Produk tampil dengan harga, penjual, gambar
    ✗ ID TIDAK ada!
```

**Database State:**
```sql
SELECT * FROM products;

id | nama_produk      | harga   | nama_penjual   | ...
1  | Laptop Gaming    | 5000000 | Budi Santoso   | ...
2  | Monitor 4K       | 2000000 | Budi Santoso   | ...
3  | iPhone 14 Pro    | 14000000| Ani Wijaya     | ...

User hanya melihat: Laptop Gaming, Monitor 4K, iPhone 14 Pro
User TIDAK melihat: ID 1, 2, 3
```

---

### Flow 2️⃣: Customer Lihat Detail Produk

```
User klik: "Lihat Detail" di card produk
URL generated: /products/Laptop%20Gaming

Router analyze: GET /products/{id}
Extract: $id = 'Laptop Gaming'
              └─ urldecode('Laptop%20Gaming')
                        │
                        ▼
    ProductController->show($id)
    ┌────────────────────────────────────┐
    │ $productName = urldecode($id)      │
    │ // $productName = 'Laptop Gaming'  │
    │                                    │
    │ $product = $this->productModel     │
    │   ->getProductByName($productName) │
    │                                    │
    │ // Query:                          │
    │ SELECT nama_produk, harga, ...     │
    │ FROM products                      │
    │ WHERE nama_produk = ? → ...        │
    │ // Parameter: 'Laptop Gaming'      │
    │ // TIDAK ada ID!                   │
    │                                    │
    │ if (empty($product))               │
    │   return 404                       │
    │                                    │
    │ render('products/Productdetail',   │
    │        ['product' => $product])    │
    └────────────────────────────────────┘
                        │
                        ▼
    View: Productdetail.php
    ┌────────────────────────────────────┐
    │ <div class="detail">               │
    │   <h1><?=$product['nama_...']?></h1>│
    │   <p><?=$product['harga']?></p>    │
    │   <p><?=$product['desk...']?></p>  │
    │   <img src="<?=$product[......]?>"|
    │   <p>Penjual: <?=...?></p>         │
    │   <!-- NO ID! -->                  │
    │   <button>Add to Cart</button>     │
    │ </div>                             │
    └────────────────────────────────────┘
                        │
                        ▼
    Browser tampilkan detail produk
    ✓ Semua detail lengkap
    ✗ ID TIDAK ada
```

**Keamanan:**
```
URL Aman:
http://localhost:8000/products/Laptop%20Gaming
                              └─ Nama produk

Jika hacker coba:
http://localhost:8000/products/1
                              └─ Raw ID (tidak ada di sistem)
→ 404 Not Found (karena query WHERE nama_produk = '1' tidak match)
```

---

### Flow 3️⃣: Admin Tambah Produk (Backend - Internal ID Use)

```
Admin access: /admin/products/create
                        │
                        ▼
    AdminProductController->create()
    ├─ Check role: if ($_SESSION['role'] != 'admin') redirect
    └─ render form
                        │
                        ▼
    Admin isi form & submit POST /admin/products
    Data:
    {
        'nama_produk': 'Keyboard RGB',
        'harga': 1200000,
        'nama_penjual': 'Toko Budi',
        'deskripsi': 'Mechanical keyboard...',
        'gambar': [file upload] → keyboard.jpg
    }
                        │
                        ▼
    AdminProductController->store()
    ┌────────────────────────────────────────┐
    │ // Validasi input                      │
    │ $data = validate($_POST)               │
    │                                        │
    │ // Upload gambar ke public/assets/foto/│
    │ move_uploaded_file(...)                │
    │                                        │
    │ // Prepare data untuk model            │
    │ $data = [                              │
    │   'nama_produk' => 'Keyboard RGB',     │
    │   'harga' => 1200000,                  │
    │   'nama_penjual' => 'Toko Budi',       │
    │   'deskripsi' => '...',                │
    │   'gambar' => 'keyboard.jpg',          │
    │   'status' => 'active'                 │
    │ ]                                      │
    │                                        │
    │ Product->createProduct($data)          │
    │ ├─ INSERT INTO products                │
    │ │  (nama_produk, harga, ...)           │
    │ │  VALUES (?, ?, ?, ...)               │
    │ │  // Prepared statement - aman!       │
    │ │                                      │
    │ └─ Database auto-generate:             │
    │    id = 7 (next auto increment)        │
    │    (Internal! Admin tidak tahu/peduli) │
    │                                        │
    │ Redirect: /admin/products              │
    │ Show: "Produk berhasil ditambah"       │
    └────────────────────────────────────────┘
                        │
                        ▼
    Database state sekarang:
    ┌──────────────────────────────────────┐
    │ INSERT INTO products VALUES           │
    │ (7, 'Keyboard RGB', 1200000, ...)    │
    └──────────────────────────────────────┘
                        │
                        ▼
    Admin view list di /admin/products
    ┌──────────────────────────────────────┐
    │ Admin BISA lihat:                    │
    │ ├─ ID (untuk edit/delete operations) │
    │ ├─ Keyboard RGB                      │
    │ ├─ Rp 1.200.000                      │
    │ ├─ [Edit] [Delete]                   │
    │ └─ ...                               │
    │                                      │
    │ Customer TIDAK akan lihat ID         │
    │ ketika akses /products               │
    └──────────────────────────────────────┘
```

---

### Flow 4️⃣: Query dengan Prepared Statement (Security)

**✅ AMAN (Prepared Statement):**
```php
$db->select(
    "SELECT * FROM products WHERE nama_produk = ?",
    ['Laptop; DROP TABLE products; --']
);

Apa yang terjadi:
1. Database prepare statement: "WHERE nama_produk = ?"
2. Bind parameter: ['Laptop; DROP TABLE products; --']
3. Database treat parameter sebagai DATA, bukan SQL command
4. Query jalankan: WHERE nama_produk = 'Laptop; DROP TABLE products; --'
5. Result: Cari produk dengan nama itu (literal string)
6. Table tidak terhapus! ✓

Hasil: 0 rows (tidak ada produk dengan nama yang aneh)
```

**❌ TIDAK AMAN (String Interpolation):**
```php
$search = $_GET['search'];
$query = "SELECT * FROM products WHERE nama_produk = '{$search}'";
$result = mysqli_query($conn, $query);

Jika $search = "Laptop'; DROP TABLE products; --"
Query menjadi:
SELECT * FROM products WHERE nama_produk = 'Laptop'; DROP TABLE products; --'

Database execute:
- Pertama: SELECT ... WHERE nama_produk = 'Laptop'
- Kedua: DROP TABLE products ← TERHAPUS!
- Ketiga: (comment) --'

Hasil: Table products hilang! ☠️
```

---

## 📋 DATABASE LOGIC PRODUK

### Product Lifecycle

```
┌─ CREATE (Tambah Produk)
│  Input: Form dari admin/penjual
│  Process:
│  ├─ Validasi: nama, harga, deskripsi
│  ├─ Upload: Gambar ke folder
│  ├─ Insert: Data ke tabel products
│  │  id auto-generate (internal)
│  └─ Status: Produk aktif & bisa dilihat customer
│
├─ READ (Baca/Lihat Produk)
│  Customer view:
│  ├─ List: /products → ambil TANPA ID
│  ├─ Detail: /products/nama-produk → cari by nama
│  └─ Tampil: nama, harga, penjual, deskripsi, gambar
│
│  Admin view:
│  ├─ List: /admin/products → ambil WITH ID (untuk edit/delete)
│  └─ Detail: /admin/products/5 → ambil by ID
│
├─ UPDATE (Edit Produk)
│  Only admin:
│  ├─ Access: /admin/products/5/edit
│  ├─ Load: Produk dengan ID 5
│  ├─ Form: Pre-fill dengan data lama
│  ├─ Submit: UPDATE products SET ... WHERE id = ?
│  └─ Redirect: /admin/products/{id}
│
├─ DELETE (Hapus Produk)
│  Only admin:
│  ├─ Request: DELETE /admin/products/5
│  ├─ Query: DELETE FROM products WHERE id = ?
│  ├─ Terhapus: Semua data produk
│  └─ Note: Cart items juga terhapus (FK cascade)
│
└─ SOFT DELETE (Recommended)
   ├─ Bukan DELETE, UPDATE status = 'inactive'
   ├─ Keuntungan:
   │  ├─ Data preserved (audit trail)
   │  ├─ FK tidak broken
   │  ├─ Bisa restore: UPDATE status = 'active'
   │  └─ Cart/history tetap valid
   └─ Query: UPDATE products SET status = 'inactive' WHERE id = ?
```

---

## 🎯 BEST PRACTICES

### ✅ Model yang Benar

```php
class Product
{
    // ✓ Hanya return field yang diperlukan (TANPA ID untuk customer)
    public function getAllProducts()
    {
        $query = "SELECT nama_produk, harga, nama_penjual, 
                         deskripsi, gambar, status 
                  FROM products 
                  WHERE status = 'active'";
        return $this->db->select($query);
    }

    // ✓ Cari by NAMA, tidak by ID
    public function getProductByName($namaProduk)
    {
        $query = "SELECT nama_produk, harga, nama_penjual, 
                         deskripsi, gambar, status 
                  FROM products 
                  WHERE nama_produk = ?";
        return $this->db->selectOne($query, [$namaProduk]);
    }

    // ✓ Internal method: ambil dengan ID (untuk admin/backend)
    private function getProductById($id)
    {
        $query = "SELECT * FROM products WHERE id = ?";
        return $this->db->selectOne($query, [$id]);
    }

    // ✓ Prepared statement dengan parameter binding
    public function createProduct($data)
    {
        return $this->db->insert('products', [
            'nama_produk' => $data['nama_produk'],
            'harga' => $data['harga'],
            'nama_penjual' => $data['nama_penjual'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'gambar' => $data['gambar'] ?? null,
            'status' => 'active'
        ]);
    }
}
```

### ✅ Controller yang Benar

```php
class ProductController
{
    public function index()
    {
        // ✓ Ambil dari model
        $products = $this->productModel->getAllProducts();
        
        // ✓ Pass ke view
        $data = ['products' => $products];
        $this->render('products/ProductList', $data);
        
        // ✗ JANGAN query di sini!
        // ✗ JANGAN echo/print langsung!
    }

    public function show($id)
    {
        // ✓ Decode URL parameter
        $productName = urldecode($id);
        
        // ✓ Cari by nama
        $product = $this->productModel->getProductByName($productName);
        
        // ✓ Handle not found
        if (empty($product)) {
            http_response_code(404);
            $this->render('errors/404');
            return;
        }
        
        // ✓ Pass ke view
        $this->render('products/Productdetail', ['product' => $product]);
    }
}
```

### ✅ View yang Benar

```php
<!-- ✓ Loop products -->
<?php foreach ($products as $product): ?>
    <div class="product-card">
        <!-- ✓ Escape untuk XSS prevention -->
        <h3><?= htmlspecialchars($product['nama_produk']) ?></h3>
        
        <!-- ✓ Harga dengan formatting -->
        <p>Rp <?= number_format($product['harga']) ?></p>
        
        <!-- ✓ Gambar dengan path yang benar -->
        <img src="/assets/foto/<?= htmlspecialchars($product['gambar']) ?>" />
        
        <!-- ✓ URL dengan encode nama (AMAN - TIDAK ada ID) -->
        <a href="/products/<?= urlencode($product['nama_produk']) ?>">
            Lihat Detail
        </a>
    </div>
<?php endforeach; ?>

<!-- ✗ JANGAN lakukan query di view -->
<!-- ✗ JANGAN echo $product['id'] -->
<!-- ✗ JANGAN hardcode nama tabel atau kolom -->
```

### ❌ Anti-Patterns

```php
// ❌ Query di Controller
public function index()
{
    $query = "SELECT * FROM products";
    $result = mysqli_query($conn, $query);
}

// ❌ Query di View
<?php
    $products = mysqli_query($conn, "SELECT * FROM products");
?>

// ❌ Expose ID di URL
<a href="/products/<?= $product['id'] ?>">  <!-- JANGAN! -->

// ❌ SQL Injection vulnerable
$query = "SELECT * FROM products WHERE name = '{$_POST['search']}'";

// ❌ XSS vulnerable
<?= $product['nama_produk'] ?>  <!-- Tanpa htmlspecialchars() -->
```

---

## 🚀 Implementation Checklist

✅ = Sudah dibuat

- [x] Database.php - OOP connection class
- [x] Product model - CRUD operations
- [x] User model - Authentication
- [x] ProductController - Customer view
- [x] ID tidak di-expose di URL
- [x] Prepared statements (SQL injection safe)
- [x] Password hashing (password_hash)
- [x] Session management
- [ ] AdminProductController
- [ ] Admin views (create, edit, index, show)
- [ ] Image upload (validation & security)
- [ ] Error handling (try-catch)
- [ ] Input validation
- [ ] Permission checks (admin vs customer)
- [ ] Logging & monitoring

---

## 📖 File Referensi

- [Database.php](app/core/Database.php) - Database connection
- [Product.php](app/models/Product.php) - Product model
- [User.php](app/models/User.php) - User model
- [ProductController.php](app/controllers/productController.php) - Controller
- [database.sql](database.sql) - SQL schema

---

**Setiap bagian dirancang untuk keamanan, maintainability, dan scalability! ✓**
