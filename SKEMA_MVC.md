# Skema MVC - folder-ryu Project

## 📋 Gambaran Umum
Proyek ini menggunakan pola **Model-View-Controller (MVC)** dengan routing berbasis URL. Sistem dirancang untuk memisahkan logika bisnis, presentasi, dan akses data secara jelas.

---

## 🏗️ Arsitektur Sistem

```
HTTP Request (Client/Browser)
         ↓
    public/index.php
         ↓
    Router.php (Menganalisis URL & Method)
         ↓
    Controller yang sesuai
    ├─→ Model (Ambil/Simpan Data)
    ├─→ Business Logic
    └─→ Pass Data ke View
         ↓
    View (Template HTML)
         ↓
    HTTP Response (HTML dikirim ke Browser)
```

---

## 📁 Struktur Folder & Penjelasan

### 1. **public/** - Entry Point & Assets
```
public/
├── index.php          ← Entry point utama aplikasi
├── detail-barang.php  ← Legacy file (bisa di-cleanup)
└── assets/
    ├── css/
    │   └── output.css ← CSS yang dikompilasi dari Tailwind
    ├── foto/          ← Folder penyimpanan gambar produk
    └── js/
        ├── cart.js    ← Logic keranjang belanja
        ├── daftarbarang.js
        ├── detail.js
        └── navbar.js  ← Logic navbar
```

**Peran:** Folder public adalah satu-satunya folder yang bisa diakses langsung dari browser. Ini adalah entry point aplikasi.

---

### 2. **app/core/** - Core Application Logic
```
app/core/
└── Router.php
    ├── add(method, uri, controller, function)
    │   └─ Mendaftarkan route baru
    └── run()
        ├─ Parse URL dari request
        ├─ Cocokkan dengan pattern route
        ├─ Load controller yang sesuai
        ├─ Jalankan function di controller
        └─ Tangani 404 jika tidak ada route cocok
```

**Peran:** Router adalah "traffic controller" yang mengarahkan setiap request HTTP ke controller yang tepat.

**Alur Kerja Router:**
```
User Request: GET /products/5
    ↓
Router.run()
    ↓
Cek pattern: /products/{id} === /products/5 ✓
    ↓
Extract id: 5
    ↓
Load: ProductController
    ↓
Call: ProductController->show(5)
```

---

### 3. **app/controllers/** - Request Handler
```
app/controllers/
├── ProductController.php
│   ├── index()           ← GET /products → Tampilkan daftar produk
│   └── show($id)         ← GET /products/{id} → Tampilkan detail produk
│
├── AdminProductController.php
│   ├── index()           ← GET /admin/products → Daftar produk admin
│   ├── create()          ← GET /admin/products/create → Form tambah
│   ├── store()           ← POST /admin/products → Simpan produk
│   ├── edit($id)         ← GET /admin/products/{id}/edit → Form edit
│   ├── update($id)       ← POST /admin/products/{id} → Update produk
│   └── show($id)         ← GET /admin/products/{id} → Detail produk
│
├── CartController.php
│   └── cartView()        ← GET /cart → Tampilkan keranjang
│
├── LoginController.php
│   └── loginView()       ← GET /login → Tampilkan form login
│
└── LandingController.php
    └── landingView()     ← GET / → Tampilkan halaman utama
```

**Peran:** Controller menerima request, memproses logika, berkomunikasi dengan Model, dan melewatkan data ke View.

**Tanggung Jawab Controller:**
1. Terima parameter dari Router
2. Validasi input
3. Panggil method Model jika diperlukan
4. Persiapkan data untuk View
5. Load dan render View

**Contoh Alur:**
```
GET /products/5
    ↓
Router → ProductController->show(5)
    ↓
ProductController:
  ├─ Call: $product = Product->find(5)
  ├─ Prepare: $data = ['product' => $product]
  └─ Render: require_once 'views/products/Productdetail.php'
    ↓
View menerima $data dan render HTML
```

---

### 4. **app/models/** - Business Logic & Database
```
app/models/
├── Product.php
│   ├── getAll()          ← Ambil semua produk
│   ├── find($id)         ← Cari produk berdasar ID
│   ├── create($data)     ← Buat produk baru
│   ├── update($id, $data) ← Update produk
│   ├── delete($id)       ← Hapus produk
│   └── [Database Query Methods]
│
└── User.php
    ├── findByEmail($email)
    ├── create($data)
    ├── authenticate($email, $password)
    └── [Database Query Methods]
```

**Peran:** Model adalah penjembatan antara aplikasi dan database. Menangani semua logika bisnis dan query database.

**Tanggung Jawab Model:**
1. Validasi data
2. Query database
3. Transform data
4. Business logic
5. Error handling

**Contoh Method Model:**
```php
class Product {
    public function find($id) {
        // Query: SELECT * FROM products WHERE id = ?
        // Return: Product object atau null
    }
    
    public function create($data) {
        // Validasi: nama, harga, stok, dll
        // Query: INSERT INTO products (...)
        // Return: New Product object
    }
}
```

---

### 5. **app/views/** - Template HTML
```
app/views/
├── landing.php              ← Home page
├── login.php                ← Login form
├── cart.php                 ← Shopping cart
├── sellpage.php             ← Page untuk jual
│
├── component/
│   └── navbar.php           ← Navbar component (reusable)
│
└── products/
    ├── ProductList.php      ← Daftar produk
    ├── Productdetail.php    ← Detail satu produk
    └── create.php           ← Form tambah produk
```

**Peran:** View hanya bertanggung jawab untuk menampilkan data. Tidak ada database query di view!

**Tanggung Jawab View:**
1. Terima data dari Controller
2. Format data untuk HTML
3. Render UI
4. Include components yang diperlukan

**Contoh View:**
```php
<?php
// Menerima $data dari Controller
$product = $data['product']; // Atau: $product passed langsung
?>

<div class="product">
    <h1><?php echo $product->name; ?></h1>
    <p>Harga: Rp <?php echo number_format($product->price); ?></p>
</div>
```

---

## 🔄 Alur Request Lengkap

### Contoh 1: User mengakses `/products`

```
1. User ketik URL: http://localhost:8000/products
2. Browser kirim: GET /products
   ↓
3. Server load: public/index.php
   ↓
4. Router.run() dipanggil
   ├─ Method: GET
   ├─ URI: /products
   ↓
5. Router cocokkan dengan route registry:
   'GET' /products → ProductController->index()
   ↓
6. Load file: app/controllers/ProductController.php
   ↓
7. Instantiate: $controller = new ProductController()
   ↓
8. Call function: $controller->index()
   ↓
9. ProductController->index():
   ├─ $products = Product->getAll()
   ├─ $data = ['products' => $products]
   ├─ require 'views/products/ProductList.php'
   ↓
10. View (ProductList.php) render:
    ├─ Terima $products
    ├─ Loop: foreach($products as $product)
    ├─ Output: <div class="product">...</div>
    ↓
11. Browser terima HTML, render halaman
```

### Contoh 2: User mengakses `/products/5`

```
1. User ketik: http://localhost:8000/products/5
2. Browser kirim: GET /products/5
   ↓
3. Router.run():
   ├─ Pattern: /products/{id} → /products/([0-9]+)
   ├─ Match dengan: /products/5 ✓
   ├─ Extract: $id = 5
   ↓
4. Load: app/controllers/ProductController.php
   ↓
5. Call: ProductController->show(5)
   ↓
6. ProductController->show($id):
   ├─ $product = Product->find($id)
   ├─ if ($product == null) throw 404
   ├─ require 'views/products/Productdetail.php'
   ↓
7. View render detail produk dengan data $product
```

---

## 🔌 Route Registry

### Saat Ini (Dari `public/index.php`):

```php
// Landing Page
$router->add('GET', '/', 'LandingController', 'landingView');

// Keranjang Belanja
$router->add('GET', '/cart', 'CartController', 'cartView');

// Produk - Frontend
$router->add('GET', '/products', 'ProductController', 'index');
$router->add('GET', '/products/{id}', 'ProductController', 'show');

// Produk - Admin
$router->add('GET', '/admin/products', 'AdminProductController', 'index');
$router->add('GET', '/admin/products/create', 'AdminProductController', 'create');
$router->add('POST', '/admin/products', 'AdminProductController', 'store');
$router->add('GET', '/admin/products/{id}/edit', 'AdminProductController', 'edit');
$router->add('POST', '/admin/products/{id}', 'AdminProductController', 'update');
$router->add('DELETE', '/admin/products/{id}', 'AdminProductController', 'destroy');
$router->add('GET', '/admin/products/{id}', 'AdminProductController', 'show');

// Authentication
$router->add('GET', '/login', 'LoginController', 'loginView');
$router->add('POST', '/login', 'LoginController', 'authenticate');
$router->add('GET', '/logout', 'LoginController', 'logout');
```

---

## 📊 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                        BROWSER/CLIENT                             │
│  (User mengklik link, submit form, atau mengetik URL)            │
└────────────────────────────┬────────────────────────────────────┘
                             │
                             ↓ HTTP Request
        ┌────────────────────────────────────────┐
        │      public/index.php                   │
        │  (Entry point tunggal aplikasi)        │
        └────────────────┬───────────────────────┘
                         │
                         ↓
        ┌────────────────────────────────────────┐
        │  app/core/Router.php                    │
        │  ├─ Parse URL & Method                 │
        │  ├─ Cocokkan dengan route pattern      │
        │  └─ Extract parameter (id, slug, dll)  │
        └────────────────┬───────────────────────┘
                         │
            ┌────────────┼────────────┐
            ↓            ↓            ↓
    ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
    │ Controller   │ │ Controller   │ │ Controller   │
    │ (Product)   │ │ (Cart)       │ │ (Login)      │
    └──────┬───────┘ └──────┬───────┘ └──────┬───────┘
           │                │                │
           ├────────────────┴────────────────┤
           │                                  │
           ↓ (Query/Command)                  ↓
    ┌────────────────────────────────────────────────┐
    │  app/models/                                    │
    │  ├─ Product.php (Business Logic + DB Query)   │
    │  ├─ User.php                                   │
    │  └─ Other models                               │
    └────────────────┬───────────────────────────────┘
                     │
                     ↓ (SQL Queries)
            ┌─────────────────────┐
            │   DATABASE          │
            │   (MySQL/etc)       │
            └────────┬────────────┘
                     │
                     ↓ (Data Result)
    ┌────────────────────────────────────────────────┐
    │  Model return data object/array                │
    └────────────────┬───────────────────────────────┘
                     │
           ┌─────────┴──────────┐
           ↓                    ↓
    ┌────────────────┐   ┌────────────────┐
    │ Controller:    │   │ Prepare $data  │
    │ Process logic  │   │ for View       │
    └────────┬───────┘   └────────┬───────┘
             │                    │
             └────────┬───────────┘
                      │
                      ↓
        ┌─────────────────────────────────────┐
        │  app/views/                          │
        │  ├─ templates/*.php                 │
        │  └─ components/*.php                │
        │                                      │
        │  (Terima $data, render HTML)        │
        └──────────────┬──────────────────────┘
                       │
                       ↓ HTML String
    ┌────────────────────────────────────────┐
    │  public/assets/                         │
    │  ├─ css/output.css (Styling)           │
    │  └─ js/*.js (Interactivity)            │
    └────────────────┬───────────────────────┘
                     │
                     ↓ HTTP Response
    ┌────────────────────────────────────────┐
    │      BROWSER RENDER PAGE               │
    │  (Display HTML + apply CSS + run JS)   │
    └────────────────────────────────────────┘
```

---

## 🎯 Alur CRUD Produk (Lengkap)

### CREATE (Tambah Produk)
```
1. GET /admin/products/create
   → AdminProductController->create()
   → require views/products/create.php
   → User lihat form

2. User isi form & submit
   
3. POST /admin/products
   → AdminProductController->store()
   ├─ Validasi: $data = validate($_POST)
   ├─ Query: Product->create($data)
   ├─ Model insert ke DB
   ├─ Redirect: /admin/products
   ├─ Show: "Produk berhasil ditambah"
```

### READ (Baca Produk)
```
1. GET /products
   → ProductController->index()
   ├─ Query: $products = Product->getAll()
   ├─ require views/products/ProductList.php
   → User lihat daftar

2. GET /products/5
   → ProductController->show(5)
   ├─ Query: $product = Product->find(5)
   ├─ require views/products/Productdetail.php
   → User lihat detail
```

### UPDATE (Edit Produk)
```
1. GET /admin/products/5/edit
   → AdminProductController->edit(5)
   ├─ Query: $product = Product->find(5)
   ├─ require views/products/create.php
   → User lihat form dengan data lama

2. POST /admin/products/5
   → AdminProductController->update(5)
   ├─ Validasi: $data = validate($_POST)
   ├─ Query: Product->update(5, $data)
   ├─ Model update di DB
   ├─ Redirect: /admin/products/5
   ├─ Show: "Produk berhasil diupdate"
```

### DELETE (Hapus Produk)
```
1. DELETE /admin/products/5
   → AdminProductController->destroy(5)
   ├─ Query: Product->delete(5)
   ├─ Model hapus dari DB
   ├─ Redirect: /admin/products
   ├─ Show: "Produk berhasil dihapus"
```

---

## ✅ Best Practices dalam MVC

### 1. **Separation of Concerns**
   - ✓ Model: Hanya database & business logic
   - ✓ Controller: Hanya request handling & koordinasi
   - ✓ View: Hanya rendering HTML
   - ✗ JANGAN: Query di Controller atau View

### 2. **Data Flow**
   ```
   View ← Controller ← Model ← Database
   
   ✓ Controller request data ke Model
   ✓ Model return data terstruktur
   ✓ Controller pass ke View
   ✓ View render data
   
   ✗ JANGAN: View langsung query database
   ✗ JANGAN: Model return raw array
   ```

### 3. **Naming Conventions**
   ```
   Controller:  CamelCase + "Controller"
   Model:       CamelCase
   View:        snake_case atau CamelCase
   Function:    camelCase
   Route:       kebab-case
   
   ✓ ProductController.php
   ✓ Product.php
   ✓ public function show($id)
   ✓ /admin/products/5
   ```

### 4. **URL Parameter Handling**
   ```
   Route pattern:    /products/{id}
   Regex pattern:    /products/([0-9]+)
   Extracted value:  $id = 5
   Method signature: function show($id)
   
   Router automatically pass $id ke method
   ```

---

## 🔧 Implementation Checklist

### Model Implementation
```php
<?php
namespace App\Models;

class Product {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function getAll() {
        $query = "SELECT * FROM products";
        $result = $this->db->query($query);
        return $result->fetchAll();
    }
    
    public function find($id) {
        $query = "SELECT * FROM products WHERE id = ?";
        $result = $this->db->query($query, [$id]);
        return $result->fetch();
    }
    
    public function create($data) {
        $query = "INSERT INTO products (name, price, stock) VALUES (?, ?, ?)";
        return $this->db->query($query, [$data['name'], $data['price'], $data['stock']]);
    }
}
```

### Controller Implementation
```php
<?php
namespace App\Controllers;
require_once '../app/models/Product.php';

use App\Models\Product;

class ProductController {
    private $product;
    
    public function __construct() {
        $this->product = new Product();
    }
    
    public function index() {
        $products = $this->product->getAll();
        require_once '../app/views/products/ProductList.php';
    }
    
    public function show($id) {
        $product = $this->product->find($id);
        if (!$product) {
            http_response_code(404);
            echo '404 - Product not found';
            return;
        }
        require_once '../app/views/products/Productdetail.php';
    }
}
```

### View Implementation
```php
<?php
// Menerima $products dari controller
?>
<div class="products-list">
    <?php foreach($products as $product): ?>
        <div class="product-card">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p>Harga: Rp <?php echo number_format($product['price']); ?></p>
            <a href="/products/<?php echo $product['id']; ?>">Lihat Detail</a>
        </div>
    <?php endforeach; ?>
</div>
```

---

## 📞 Koneksi Antar Komponen

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                   │
│  ROUTER mengarahkan request ke CONTROLLER yang tepat             │
│  ↓                                                                │
│  CONTROLLER meminta data dari MODEL                              │
│  ↓                                                                │
│  MODEL query DATABASE dan return data                            │
│  ↓                                                                │
│  CONTROLLER prepare data dan load VIEW                           │
│  ↓                                                                │
│  VIEW render data menjadi HTML                                   │
│  ↓                                                                │
│  HTML dikirim ke BROWSER dan ditampilkan                         │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘

Setiap komponen punya tanggung jawab spesifik dan berkomunikasi
melalui interface yang jelas dan terdefinisi.
```

---

## 🚀 Next Steps

1. **Implementasi Model Base Class** - Untuk reduce code duplication
2. **Database Connection Layer** - Centralized DB connection
3. **Middleware** - For authentication & authorization
4. **Error Handling** - Try-catch di Router & Controllers
5. **Logging** - Track all important events
6. **Validation** - Input validation di Controller atau Model
7. **Repository Pattern** - Abstract database access layer

---

## 📝 Catatan Penting

- **Semua route dimulai dari Router** - Jangan hardcode path di View
- **Controller adalah koordinator** - Bukan tempat business logic
- **Model adalah aset berharga** - Reusable di berbagai tempat
- **View itu dumb** - Hanya receive data dan render
- **Database query hanya di Model** - Jangan di Controller atau View
- **Gunakan include/require untuk Views** - Bisa pass variable sebagai $data

---

Skema ini memastikan kode yang:
✓ Maintainable (mudah dirawat)
✓ Scalable (mudah diperluas)
✓ Testable (mudah ditest)
✓ Reusable (komponen bisa digunakan ulang)
