# 📚 DOKUMENTASI LENGKAP - folder-ryu MVC Project

Panduan lengkap setup, implementasi, dan running aplikasi MVC dengan database yang terstruktur dengan baik.

---

## 📋 Index Dokumentasi

### 1. **QUICK_START.md** ⭐ MULAI DI SINI
   - Panduan cepat untuk memahami struktur
   - Setup database
   - Testing checklist
   - Quick reference untuk query

### 2. **SKEMA_MVC.md**
   - Arsitektur MVC lengkap
   - Struktur folder & penjelasan
   - Flow data lengkap
   - Route registry

### 3. **DATABASE_LOGIKA.md** 🔐
   - **Tabel-tabel database** (users, products, cart)
   - **ID Security** - Mengapa ID disembunyikan
   - **Flow data lengkap** dengan diagram
   - **Best practices** implementasi
   - **Contoh kode** yang benar dan salah

### 4. **database.sql**
   - SQL schema lengkap
   - Sample data untuk testing
   - Query utilities untuk admin

---

## 🚀 QUICK GUIDE

### Setup Awal (5 menit)

1. **Buka phpMyAdmin:**
   ```
   http://localhost/phpmyadmin
   ```

2. **Buat database & import SQL:**
   ```sql
   CREATE DATABASE tradecoin;
   USE tradecoin;
   -- Import database.sql
   ```

3. **Test di browser:**
   ```
   http://localhost:8000/products
   http://localhost:8000/products/Laptop%20Gaming%20ASUS
   ```

---

## 🗂️ FILE YANG SUDAH DIBUAT

### Core Files

| File | Fungsi |
|------|--------|
| [app/core/Database.php](app/core/Database.php) | OOP Database connection dengan prepared statements |
| [app/core/Router.php](app/core/Router.php) | URL routing engine (sudah ada) |

### Models

| File | Fungsi |
|------|--------|
| [app/models/Product.php](app/models/Product.php) | Product CRUD & query methods |
| [app/models/User.php](app/models/User.php) | User auth & management |

### Controllers

| File | Fungsi |
|------|--------|
| [app/controllers/productController.php](app/controllers/productController.php) | Handle /products routes |
| [app/controllers/AdminProductController.php](app/controllers/AdminProductController.php) | Admin product management (perlu di-update) |

### Views

| File | Fungsi |
|------|--------|
| [app/views/products/ProductList.php](app/views/products/ProductList.php) | Display daftar produk |
| [app/views/products/Productdetail.php](app/views/products/Productdetail.php) | Display detail produk |

### Database

| File | Fungsi |
|------|--------|
| [database.sql](database.sql) | SQL schema + sample data |

---

## 🎯 IMPLEMENTASI STEPS

### Step 1: Database Setup
- ✅ File `database.sql` sudah dibuat dengan schema lengkap
- ✅ Tabel `users`, `products`, `cart` sudah defined
- ✅ Sample data sudah ada untuk testing

**Action:**
```sql
-- Copy semua dari database.sql
-- Paste ke phpMyAdmin Query tab
-- Execute
```

---

### Step 2: Database Class
- ✅ File `app/core/Database.php` sudah dibuat
- ✅ Method: select(), selectOne(), insert(), update(), delete()
- ✅ Prepared statements (aman dari SQL injection)

**Test:**
```php
$db = new Database();
$products = $db->select("SELECT * FROM products");
var_dump($products);
```

---

### Step 3: Product Model
- ✅ File `app/models/Product.php` sudah dibuat
- ✅ Method: getAllProducts(), getProductByName(), createProduct(), dll
- ✅ Logika: **ID TIDAK ditampilkan ke customer**

**Test:**
```php
$product = new Product();
$all = $product->getAllProducts();  // Tanpa ID
var_dump($all);
```

---

### Step 4: User Model
- ✅ File `app/models/User.php` sudah dibuat
- ✅ Method: register(), login(), getUserById(), dll
- ✅ Password hashing dengan password_hash()

---

### Step 5: ProductController
- ✅ File `app/controllers/productController.php` sudah di-update
- ✅ Method: index() → daftar produk
- ✅ Method: show($id) → detail produk
- ✅ Feature: **Cari by nama, tidak by ID**

**Routes:**
```php
GET /products          → index()       (daftar)
GET /products/{id}     → show($id)     (detail)
```

---

### Step 6: Views
- ⚠️ Perlu update: ProductList.php & Productdetail.php
- ⚠️ Pastikan TIDAK tampilkan ID
- ⚠️ Gunakan `urlencode()` untuk URL dengan nama produk

**Contoh ProductList.php:**
```php
<?php foreach ($products as $product): ?>
    <h3><?= htmlspecialchars($product['nama_produk']) ?></h3>
    <p>Rp <?= number_format($product['harga']) ?></p>
    <!-- ✓ URL dengan nama, bukan ID -->
    <a href="/products/<?= urlencode($product['nama_produk']) ?>">
        Detail
    </a>
<?php endforeach; ?>
```

---

## 🔐 SECURITY HIGHLIGHTS

### ✅ SQL Injection Prevention
```php
// ✓ Prepared statement
$db->select("SELECT * FROM products WHERE nama_produk = ?", ['Laptop']);

// ❌ JANGAN string interpolation
$query = "SELECT * FROM products WHERE nama_produk = '{$_POST['search']}'";
```

### ✅ ID Hidden from User
```
Database:
id | nama_produk | harga
1  | Laptop      | 5000000
2  | Monitor     | 2000000

Customer lihat:   Laptop, Monitor (AMAN)
Customer NOT see: ID 1, ID 2 (HIDDEN)

URL:
✓ /products/Laptop
✗ /products/1
```

### ✅ XSS Prevention
```php
// ✓ Escape output
<?= htmlspecialchars($product['nama_produk']) ?>

// ❌ JANGAN direct output
<?= $product['nama_produk'] ?>
```

### ✅ Password Security
```php
// ✓ Hash password
password_hash('password123', PASSWORD_BCRYPT)

// ✓ Verify password
password_verify($inputPassword, $hashedPassword)

// ❌ JANGAN plain text
$password = $_POST['password'];  // Berbahaya!
```

---

## 🔄 FLOW DIAGRAM

### Flow 1: Customer Lihat Daftar Produk

```
User → /products
    ↓
Router: GET /products → ProductController@index()
    ↓
ProductController:
  ├─ Model->getAllProducts()
  │  └─ Query: SELECT nama_produk, harga, ... (NO ID!)
  └─ render('ProductList', $products)
    ↓
View: ProductList.php
  ├─ Loop products
  ├─ Tampilkan: nama, harga, gambar
  ├─ Link detail: /products/{nama}
  └─ (NO ID!)
    ↓
Browser: Daftar produk tampil ✓
```

---

### Flow 2: Customer Lihat Detail Produk

```
User → /products/Laptop%20Gaming
    ↓
Router: GET /products/{id} → ProductController@show($id)
    ↓
ProductController:
  ├─ $productName = urldecode($id)
  ├─ Model->getProductByName($productName)
  │  └─ Query: SELECT ... WHERE nama_produk = ?
  └─ render('Productdetail', $product)
    ↓
View: Productdetail.php
  ├─ Tampilkan: nama, harga, deskripsi, gambar, penjual
  └─ (NO ID!)
    ↓
Browser: Detail produk tampil ✓
```

---

## 📊 DATABASE SCHEMA

### Tabel: products (PENTING)

```
Kolom         | Tipe         | Keterangan
              |              |
id            | INT, PK      | HIDDEN dari customer
nama_produk   | VARCHAR      | Nama barang (shown)
harga         | DECIMAL      | Harga (shown)
nama_penjual  | VARCHAR      | Siapa jual (shown)
deskripsi     | TEXT         | Detail (shown)
gambar        | VARCHAR      | Filename (shown)
status        | ENUM         | active/inactive
created_at    | TIMESTAMP    | Waktu dibuat
```

**Data Sample:**
```
id | nama_produk      | harga   | nama_penjual   | status
1  | Laptop Gaming    | 5000000 | Budi Santoso   | active
2  | Monitor 4K       | 2000000 | Budi Santoso   | active
3  | iPhone 14 Pro    | 14000000| Ani Wijaya     | active
```

**Customer view:**
```
Produk 1: Laptop Gaming - Rp 5.000.000 - Budi Santoso
Produk 2: Monitor 4K - Rp 2.000.000 - Budi Santoso
Produk 3: iPhone 14 Pro - Rp 14.000.000 - Ani Wijaya

(ID TIDAK tampil)
```

---

## ✅ CHECKLIST IMPLEMENTASI

### Database Setup
- [x] Database.php - Connection class
- [x] database.sql - Schema & sample data
- [x] Prepared statements implementation
- [x] Foreign keys & constraints

### Models
- [x] Product model - CRUD operations
- [x] User model - Authentication
- [x] Methods terpisah untuk customer vs admin

### Controllers
- [x] ProductController - Customer view
- [ ] AdminProductController - Admin operations (perlu update)
- [x] Routing ke controller methods

### Views
- [ ] ProductList.php - Daftar (perlu update)
- [ ] Productdetail.php - Detail (perlu update)
- [ ] Admin views - Create, edit, index, show

### Security
- [x] Prepared statements (SQL injection safe)
- [x] Password hashing
- [x] ID hidden dari customer
- [ ] Input validation
- [ ] XSS prevention (htmlspecialchars)
- [ ] CSRF protection

---

## 🎓 LEARNING RESOURCES

### Concepts
- **MVC Pattern**: Controller koordinator, Model bisnis, View display
- **Database**: Prepared statements, normalization, indexing
- **Security**: SQL injection, XSS, CSRF, authentication

### Best Practices
- Prepared statements untuk semua query
- Separation of concerns: Model, Controller, View
- Meaningful variable & function names
- Comments & documentation
- Error handling & validation

### Files to Study
1. [Database.php](app/core/Database.php) - Prepared statement examples
2. [Product.php](app/models/Product.php) - Model structure & methods
3. [productController.php](app/controllers/productController.php) - Controller logic
4. [DATABASE_LOGIKA.md](DATABASE_LOGIKA.md) - Detailed flow explanations

---

## 🚀 NEXT DEVELOPMENT

### Priority 1: View Implementation
- [ ] Update ProductList.php dengan styling
- [ ] Update Productdetail.php dengan styling
- [ ] Ensure NO ID ditampilkan

### Priority 2: Admin Features
- [ ] AdminProductController - Create, Update, Delete
- [ ] Admin views untuk CRUD operations
- [ ] Permission checking (admin vs customer)

### Priority 3: User Features
- [ ] Login/Register pages
- [ ] Session management
- [ ] User profile

### Priority 4: Shopping Features
- [ ] Add to cart
- [ ] View cart
- [ ] Checkout process
- [ ] Order management

### Priority 5: Polish
- [ ] Error pages (404, 500)
- [ ] Logging & monitoring
- [ ] Database optimization
- [ ] Image upload & optimization

---

## 📞 TROUBLESHOOTING

### Problem: Database connection error
**Solution:**
- Check credentials di Database.php (host, user, pass, db)
- Verify database sudah dibuat & imported

### Problem: Router tidak match
**Solution:**
- Check routes di public/index.php
- Ensure controller & method name sudah benar

### Problem: View tidak tampil
**Solution:**
- Verify view file path
- Check extract($data) di controller
- Ensure variable names di view match data keys

### Problem: ID masih tampil
**Solution:**
- Check Model query - jangan select ID
- Check View - jangan echo $data['id']
- Check URL - gunakan nama, tidak ID

---

## 📖 DOKUMENTASI REFERENSI

| File | Konten |
|------|--------|
| QUICK_START.md | Panduan cepat start |
| SKEMA_MVC.md | Arsitektur lengkap |
| DATABASE_LOGIKA.md | Database detail & flow |
| database.sql | SQL schema |
| IMPLEMENTASI.md | Ini (overview) |

---

## 🎉 Siap Melanjutkan!

Anda sudah punya:
- ✅ Database class yang aman & proper
- ✅ Model Product & User dengan CRUD
- ✅ Controller untuk handle requests
- ✅ Routing yang terstruktur
- ✅ Security: ID hidden, prepared statements, password hashing
- ✅ Database schema & sample data

**Next:** Update views dan implementasi admin features! 🚀
