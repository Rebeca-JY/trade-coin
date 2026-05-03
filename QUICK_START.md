# 🎯 QUICK START GUIDE - IMPLEMENTASI MVC + DATABASE

Panduan cepat untuk memahami dan menggunakan sistem MVC yang sudah dibuat.

---

## 📦 Apa yang Sudah Dibuat

### 1️⃣ Database Layer ([app/core/Database.php](app/core/Database.php))

**Fungsi:**
- Mengelola koneksi ke MySQL
- Menjalankan query dengan aman (prepared statements)
- Prevent SQL injection

**Metode Utama:**
```php
$db = new Database();

// SELECT banyak row
$products = $db->select("SELECT * FROM products WHERE status = ?", ['active']);

// SELECT satu row
$product = $db->selectOne("SELECT * FROM products WHERE id = ?", [1]);

// INSERT
$db->insert('products', [
    'nama_produk' => 'Laptop',
    'harga' => 5000000,
    'status' => 'active'
]);

// UPDATE
$db->update('products', ['harga' => 6000000], ['id' => 1]);

// DELETE
$db->delete('products', ['id' => 1]);
```

---

### 2️⃣ Models

#### Product Model ([app/models/Product.php](app/models/Product.php))

```php
$product = new Product();

// Daftar produk TANPA ID (untuk customer)
$products = $product->getAllProducts();

// Detail produk by nama (untuk customer)
$detail = $product->getProductByName('Laptop Gaming ASUS');

// Internal: Ambil dengan ID (untuk admin)
$item = $product->getProductById(5);

// CRUD
$product->createProduct($data);
$product->updateProduct($id, $data);
$product->deleteProduct($id);
```

#### User Model ([app/models/User.php](app/models/User.php))

```php
$user = new User();

// Register
$user->register([
    'username' => 'budi',
    'email' => 'budi@email.com',
    'password' => 'pass123',
    'nama_lengkap' => 'Budi Santoso',
    'role' => 'seller'
]);

// Login
$loggedUser = $user->login('budi', 'pass123');

// Ambil user
$userData = $user->getUserById(1);
```

---

### 3️⃣ Controllers

#### ProductController ([app/controllers/productController.php](app/controllers/productController.php))

Menangani request `/products`

```php
// GET /products → Daftar semua produk
$controller->index();
└─ Query: SELECT nama_produk, harga, ... (TANPA ID)
└─ View: ProductList.php

// GET /products/laptop-gaming → Detail produk
$controller->show('laptop-gaming');
└─ Query: SELECT ... WHERE nama_produk = ?
└─ View: Productdetail.php
```

---

### 4️⃣ Database Schema

**Tabel Products** (yang paling penting)

```sql
┌─────────────────────────────────────────────┐
│ products                                    │
├─────────────────────────────────────────────┤
│ id              (INT) ← HIDDEN FROM CUSTOMER │
│ nama_produk     (VARCHAR)                   │
│ harga           (DECIMAL)                   │
│ nama_penjual    (VARCHAR)                   │
│ deskripsi       (TEXT)                      │
│ gambar          (VARCHAR)                   │
│ status          (ENUM)                      │
│ created_at      (TIMESTAMP)                 │
└─────────────────────────────────────────────┘

Sample Data:
id | nama_produk      | harga   | deskripsi | gambar
1  | Laptop Gaming    | 5000000 | Intel i7  | laptop.jpg
2  | Monitor 4K       | 2000000 | LG 27"    | monitor.jpg
3  | Keyboard RGB     | 1200000 | Cherry MX | keyboard.jpg

Customer hanya lihat: nama_produk, harga, deskripsi, gambar
Customer TIDAK lihat: id
```

---

## 🔄 FLOW DATA DIAGRAM

### Scenario: Customer Lihat Daftar Produk

```
┌──────────────────────────────────────────────────────────┐
│ 1. Customer buka: http://localhost:8000/products         │
└──────────┬───────────────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────────────────────┐
│ 2. public/index.php → Router.php                         │
│    Router.run() analyze GET /products                    │
└──────────┬───────────────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────────────────────┐
│ 3. Router match: /products → ProductController@index()  │
└──────────┬───────────────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────────────────────┐
│ 4. ProductController->index()                            │
│    ├─ $product = new Product()                           │
│    ├─ $products = $product->getAllProducts()             │
│    │  └─ DB Query:                                       │
│    │     SELECT nama_produk, harga, ... FROM products    │
│    │     ❌ TIDAK ada ID di query!                       │
│    ├─ $data = ['products' => $products]                  │
│    └─ render('products/ProductList', $data)             │
└──────────┬───────────────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────────────────────┐
│ 5. View: ProductList.php                                │
│    <?php foreach($products as $p): ?>                   │
│        <h3><?= $p['nama_produk'] ?></h3>               │
│        <p>Rp <?= $p['harga'] ?></p>                     │
│        <img src="<?= $p['gambar'] ?>" />                │
│        <!-- ❌ NO $p['id']! -->                         │
│        <a href="/products/<?= $p['nama_produk'] ?>">    │
│            Detail                                        │
│        </a>                                              │
│    <?php endforeach; ?>                                 │
└──────────┬───────────────────────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────────────────────┐
│ 6. Browser tampilkan halaman dengan daftar produk       │
│    ✓ Produk ditampilkan lengkap (nama, harga, gambar)   │
│    ❌ ID TIDAK ditampilkan (AMAN)                       │
└──────────────────────────────────────────────────────────┘
```

---

### Scenario: Customer Lihat Detail Produk

```
User klik "Detail" → /products/Laptop%20Gaming
                              │
                              ▼
ProductController->show('Laptop Gaming')
│
├─ $productName = urldecode('Laptop Gaming')
│
├─ $product = Model->getProductByName('Laptop Gaming')
│  └─ Query:
│     SELECT nama_produk, harga, deskripsi, gambar, ...
│     FROM products
│     WHERE nama_produk = ? 
│     └─ Parameter: ['Laptop Gaming']
│     └─ ❌ TIDAK ambil ID!
│
├─ render('products/Productdetail', ['product' => $product])
│
└─ View render detail produk
   ✓ Semua data lengkap (nama, harga, deskripsi, penjual, gambar)
   ❌ ID tidak ada!
```

---

## 🔐 ID SECURITY VISUAL

### ❌ JANGAN - Expose ID:

```
Database:
id | nama_produk        | harga
1  | Laptop Gaming ASUS | 5000000
2  | Monitor 4K LG      | 2000000
3  | Keyboard Mekanik   | 1200000

URL di browser:
http://localhost:8000/products/1  ← Hacker bisa guess
http://localhost:8000/products/2  ← Bisa scrape semua
http://localhost:8000/products/3  ← Tahu struktur DB

Risiko:
- Hacker enumerate: /products/1, /products/2, ... /products/1000
- Bot scrape: Ambil semua produk otomatis
- IDOR attack: Akses data yang seharusnya tidak boleh
```

### ✅ BOLEH - Sembunyikan ID:

```
Database:
id | nama_produk        | harga
1  | Laptop Gaming ASUS | 5000000
2  | Monitor 4K LG      | 2000000
3  | Keyboard Mekanik   | 1200000

URL di browser:
http://localhost:8000/products/Laptop%20Gaming%20ASUS ← Pakai nama
http://localhost:8000/products/Monitor%204K%20LG      ← Encode
http://localhost:8000/products/Keyboard%20Mekanik     ← Produk

Query di backend:
SELECT * FROM products WHERE nama_produk = 'Laptop Gaming ASUS'
                              └─ Search by NAMA, bukan ID!

Keamanan:
✓ Hacker tidak bisa guess
✓ URL predictable tapi SAFE
✓ ID tetap jadi internal identifier
```

---

## 📝 STEP-BY-STEP IMPLEMENTASI

### Step 1: Setup Database

Buka phpMyAdmin:
```
http://localhost/phpmyadmin
```

1. Buat database baru: `tradecoin`
2. Import `database.sql` atau copy-paste queries

```sql
-- Execute all queries dari database.sql
CREATE TABLE users (...)
CREATE TABLE products (...)
CREATE TABLE cart (...)
INSERT INTO users VALUES (...)
INSERT INTO products VALUES (...)
```

**Result:** Database siap dengan sample data

---

### Step 2: Test ProductController

```php
// Test route di public/index.php sudah terdaftar:
$router->add('GET', '/products', 'ProductController', 'index');
$router->add('GET', '/products/{id}', 'ProductController', 'show');
```

Test di browser:
```
http://localhost:8000/products
→ Harus tampil daftar produk

http://localhost:8000/products/Laptop%20Gaming%20ASUS
→ Harus tampil detail produk
```

---

### Step 3: View Implementation

Buat atau update [app/views/products/ProductList.php](app/views/products/ProductList.php):

```php
<?php foreach ($products as $product): ?>
    <div class="product-item">
        <h3><?= htmlspecialchars($product['nama_produk']) ?></h3>
        <p>Rp <?= number_format($product['harga']) ?></p>
        <p><?= htmlspecialchars($product['nama_penjual']) ?></p>
        <img src="/assets/foto/<?= htmlspecialchars($product['gambar']) ?>" alt=""/>
        
        <!-- URL yang AMAN - gunakan nama, bukan ID -->
        <a href="/products/<?= urlencode($product['nama_produk']) ?>">
            Lihat Detail
        </a>
    </div>
<?php endforeach; ?>
```

---

### Step 4: Update [app/views/products/Productdetail.php](app/views/products/Productdetail.php):

```php
<div class="product-detail">
    <h1><?= htmlspecialchars($product['nama_produk']) ?></h1>
    <p>Harga: Rp <?= number_format($product['harga']) ?></p>
    <p>Penjual: <?= htmlspecialchars($product['nama_penjual']) ?></p>
    <p>Deskripsi: <?= htmlspecialchars($product['deskripsi']) ?></p>
    <img src="/assets/foto/<?= htmlspecialchars($product['gambar']) ?>" alt=""/>
    
    <button class="btn-add-cart">Add to Cart</button>
</div>
```

---

## 🧪 TESTING CHECKLIST

- [ ] Database terhubung (test di Database.php)
- [ ] ProductModel berhasil query
- [ ] ProductController->index() tampil list
- [ ] ProductController->show() tampil detail
- [ ] URL TIDAK menampilkan ID
- [ ] View tampil dengan CSS styling
- [ ] Tidak ada error di PHP
- [ ] Database query aman (prepared statement)

---

## 📊 QUICK REFERENCE

### Prepared Statement (AMAN):
```php
// ✓ Aman dari SQL injection
$db->select("SELECT * FROM products WHERE nama_produk = ?", ['Laptop']);
```

### String Interpolation (BERBAHAYA):
```php
// ❌ Rentan SQL injection
$query = "SELECT * FROM products WHERE nama_produk = '{$_POST['search']}'";
```

### Query di Model (BENAR):
```php
class Product {
    public function getAllProducts() {
        return $this->db->select("SELECT ... FROM products");
    }
}
```

### Query di Controller (SALAH):
```php
// ❌ JANGAN lakukan ini!
class ProductController {
    public function index() {
        $result = $this->db->select("SELECT * FROM products");
    }
}
```

---

## 🚀 NEXT STEPS

1. **Admin CRUD:**
   - [ ] Create: AdminProductController->create() & store()
   - [ ] Update: AdminProductController->edit() & update()
   - [ ] Delete: AdminProductController->destroy()

2. **User Authentication:**
   - [ ] Login form dan validation
   - [ ] Register form
   - [ ] Session management

3. **Shopping Cart:**
   - [ ] Add to cart logic
   - [ ] View cart
   - [ ] Checkout process

4. **Optimization:**
   - [ ] Pagination untuk daftar produk
   - [ ] Search & filter
   - [ ] Image optimization
   - [ ] Database indexes

---

## 🎓 LEARNING NOTES

### MVC Flow:
```
Browser Request → Router → Controller → Model → Database
                                          ↓
Browser Response ← View ← Controller Data ←
```

### Separation of Concerns:
```
Model = Database operations & business logic
Controller = Request handling & coordination
View = Display & user interface
```

### Security Principles:
```
✓ Prepared statements (no SQL injection)
✓ Hide database IDs (no enumeration)
✓ Escape output (no XSS)
✓ Hash passwords (no plain text)
✓ Validate input (no malformed data)
```

---

**Selamat! Anda sudah punya foundation yang solid untuk MVC application! 🎉**

Untuk pertanyaan atau debugging, cek file:
- [DATABASE_LOGIKA.md](DATABASE_LOGIKA.md) - Detail flow & logic
- [SKEMA_MVC.md](SKEMA_MVC.md) - Architecture overview
- [database.sql](database.sql) - Schema & sample data
