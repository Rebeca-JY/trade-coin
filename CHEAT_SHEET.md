# 🎯 CHEAT SHEET - MVC + DATABASE

Referensi cepat untuk common operations.

---

## 📚 Dokumentasi & File

| Dokumen | Gunakan untuk |
|---------|--------------|
| [QUICK_START.md](QUICK_START.md) | ⭐ Mulai di sini |
| [DATABASE_LOGIKA.md](DATABASE_LOGIKA.md) | Flow data & detail |
| [SKEMA_MVC.md](SKEMA_MVC.md) | Arsitektur lengkap |
| [IMPLEMENTASI.md](IMPLEMENTASI.md) | Checklist & overview |

---

## 🗂️ FILE PENTING

```
app/
├── core/
│   └── Database.php          ← Database connection class
├── models/
│   ├── Product.php           ← Product CRUD
│   └── User.php              ← User auth
├── controllers/
│   ├── productController.php ← Handle /products
│   └── AdminProductController.php ← Admin operations
└── views/
    └── products/
        ├── ProductList.php   ← Daftar produk
        └── Productdetail.php ← Detail produk

database.sql                  ← SQL schema & sample data
```

---

## 💾 DATABASE OPERATIONS

### Koneksi Database
```php
use App\Core\Database;
$db = new Database();
```

### SELECT (Banyak row)
```php
$products = $db->select(
    "SELECT * FROM products WHERE status = ?",
    ['active']
);
```

### SELECT (Satu row)
```php
$product = $db->selectOne(
    "SELECT * FROM products WHERE id = ?",
    [1]
);
```

### INSERT
```php
$db->insert('products', [
    'nama_produk' => 'Laptop',
    'harga' => 5000000,
    'status' => 'active'
]);
```

### UPDATE
```php
$db->update(
    'products',
    ['harga' => 6000000],
    ['id' => 1]
);
```

### DELETE
```php
$db->delete('products', ['id' => 1]);
```

---

## 🏢 MODEL USAGE

### Product Model

```php
use App\Models\Product;
$product = new Product();

// Untuk CUSTOMER (tanpa ID)
$all = $product->getAllProducts();
$detail = $product->getProductByName('Laptop');

// Untuk ADMIN (dengan ID)
$item = $product->getProductById(1);

// CRUD
$product->createProduct($data);
$product->updateProduct($id, $data);
$product->deleteProduct($id);
```

### User Model

```php
use App\Models\User;
$user = new User();

// Register
$user->register([...]);

// Login
$user->login('username', 'password');

// Get user
$user->getUserById(1);
$user->getUserByEmail('email@example.com');
```

---

## 🎮 CONTROLLER PATTERN

### Index Method (List)
```php
public function index()
{
    $data = $this->model->getAll();
    $this->render('views/list', ['items' => $data]);
}
```

### Show Method (Detail)
```php
public function show($id)
{
    $data = $this->model->find($id);
    if (empty($data)) {
        http_response_code(404);
        return;
    }
    $this->render('views/detail', ['item' => $data]);
}
```

### Render Method
```php
private function render($view, $data = [])
{
    extract($data);  // Konversi array ke variabel
    include $view . '.php';
}
```

---

## 🎨 VIEW PATTERN

### Loop Data
```php
<?php foreach ($items as $item): ?>
    <div>
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <!-- Tampilkan data -->
    </div>
<?php endforeach; ?>
```

### URL dengan Parameter
```php
<!-- ✓ AMAN: Gunakan nama -->
<a href="/products/<?= urlencode($product['nama_produk']) ?>">
    Detail
</a>

<!-- ❌ JANGAN: Gunakan ID -->
<!-- <a href="/products/<?= $product['id'] ?>">Detail</a> -->
```

### Escape Output (XSS Prevention)
```php
<!-- ✓ BENAR -->
<?= htmlspecialchars($product['nama_produk']) ?>

<!-- ❌ SALAH -->
<?= $product['nama_produk'] ?>
```

---

## 🔐 SECURITY PATTERNS

### Prepared Statement (✓ AMAN)
```php
$db->select(
    "SELECT * FROM users WHERE email = ?",
    ['user@example.com']
);
```

### String Interpolation (❌ BERBAHAYA)
```php
// JANGAN LAKUKAN INI!
$query = "SELECT * FROM users WHERE email = '{$_POST['email']}'";
```

### Password Hashing
```php
// Hash saat register
$hash = password_hash($password, PASSWORD_BCRYPT);

// Verify saat login
if (password_verify($input, $hash)) {
    // Password cocok
}
```

### Hide Database ID
```php
// Database PUNYA ID
id | nama_produk | harga
1  | Laptop      | 5000000

// Tapi JANGAN di-expose ke URL
❌ /products/1        (expose ID)
✓ /products/Laptop    (encode nama)

// Query BY NAMA
SELECT * FROM products WHERE nama_produk = ?
                         └─ Bukan: WHERE id = ?
```

---

## 🔄 COMMON FLOWS

### Flow: Customer Lihat Produk
```
1. User → /products
2. Router match → ProductController@index()
3. Controller:
   - $products = Model->getAllProducts()  ← No ID!
   - render('ProductList', $products)
4. View:
   - foreach ($products)
   - echo $product['nama_produk']
   - echo $product['harga']
   - link: /products/{nama}
5. Browser display ✓
```

### Flow: Admin Create Produk
```
1. Admin → /admin/products/create
2. Form display
3. Submit POST /admin/products
4. Controller->store():
   - validate input
   - Model->create($data)
   - Query: INSERT INTO products (...)
   - Database: auto-generate ID
5. Redirect → /admin/products ✓
```

### Flow: Login User
```
1. User submit form
2. LoginController->authenticate()
3. User = Model->login(username, password)
4. password_verify($input, $hash)
5. if (match):
   - $_SESSION['user_id'] = $user['id']
   - redirect dashboard
6. else:
   - show error
```

---

## 📊 DATABASE SCHEMA

### Tabel: products

| Kolom | Tipe | Catatan |
|-------|------|--------|
| id | INT, PK | **HIDDEN** dari customer |
| nama_produk | VARCHAR | Shown |
| harga | DECIMAL | Shown |
| nama_penjual | VARCHAR | Shown |
| deskripsi | TEXT | Shown |
| gambar | VARCHAR | Shown |
| status | ENUM | shown |

### Tabel: users

| Kolom | Tipe | Catatan |
|-------|------|--------|
| id | INT, PK | Hidden |
| username | VARCHAR | Unique |
| email | VARCHAR | Unique |
| password | VARCHAR | **Hashed** |
| role | ENUM | buyer/seller/admin |

### Tabel: cart

| Kolom | Tipe | Catatan |
|-------|------|--------|
| id | INT, PK | - |
| user_id | INT, FK | Reference users |
| product_id | INT, FK | Reference products |
| quantity | INT | Jumlah |

---

## ⚡ QUICK COMMANDS

### Database
```sql
-- Import SQL
-- Copy database.sql ke phpMyAdmin → Query → Execute

-- Lihat semua produk
SELECT * FROM products;

-- Count produk
SELECT COUNT(*) FROM products;

-- Delete produk
DELETE FROM products WHERE id = 1;

-- Update status
UPDATE products SET status = 'inactive' WHERE id = 1;
```

### PHP Artisan-like Commands
```bash
# Jalankan server
php -S localhost:8000 -t public/

# Test database
php test_db.php

# Check errors
php -l app/models/Product.php
```

---

## 🧪 TEST CHECKLIST

- [ ] Database connection works
- [ ] Model queries return correct data
- [ ] Controller methods execute
- [ ] Views render without errors
- [ ] URL tidak tampilkan ID
- [ ] Password di-hash (verify di login)
- [ ] Prepared statements digunakan
- [ ] No PHP warnings/errors
- [ ] CSS & JS loading correctly
- [ ] Responsive design works

---

## ❌ COMMON MISTAKES

| Kesalahan | Solusi |
|-----------|--------|
| Query di Controller | Move ke Model |
| Query di View | Move ke Model/Controller |
| ID di URL | Gunakan nama atau slug |
| Plain text password | Gunakan password_hash() |
| No prepared statements | Gunakan ? placeholder |
| No output escape | Gunakan htmlspecialchars() |
| Direct array access | Use isset() or isset() check first |
| Include full path | Use namespace or require_once |

---

## 📖 FUNCTIONS CHEAT SHEET

```php
// URL encoding
urlencode('Laptop Gaming')      // 'Laptop%20Gaming'
urldecode('Laptop%20Gaming')    // 'Laptop Gaming'

// String functions
htmlspecialchars($string)       // Escape untuk HTML
strip_tags($string)             // Remove HTML tags
trim($string)                   // Remove whitespace
strtolower($string)             // Lowercase

// Array functions
array_keys($array)              // Get keys
array_values($array)            // Get values
count($array)                   // Count items
isset($array['key'])            // Check if key exists

// Type checking
is_array()
is_string()
is_numeric()
is_null()
empty($var)

// Number formatting
number_format(5000000)          // "5,000,000"
number_format(5000000, 2)       // "5,000,000.00"
```

---

## 🚀 NEXT ACTIONS

1. **Update Views** - ProductList & Productdetail dengan styling
2. **Setup Admin** - AdminProductController methods
3. **Add Authentication** - Login/Register pages
4. **Shopping Cart** - Add/remove items logic
5. **Checkout** - Payment integration

---

**Keep this sheet handy untuk quick reference! 📌**
