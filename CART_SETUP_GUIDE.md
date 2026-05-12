# 🛒 Cart System Setup Guide

Panduan lengkap untuk setup dan testing cart system Trade Coin.

## Quick Start (Recommended)

### 1. Setup Database
Buka di browser:
```
http://localhost/trade-coin/public/setup-cart-final.php
```

Ini akan:
- ✅ Create tabel `cart` (jika belum ada)
- ✅ Verify struktur database
- ✅ Test insert ke cart
- ✅ Show cart contents

### 2. Test Add to Cart
Buka di browser:
```
http://localhost/trade-coin/public/products
```

- Pilih salah satu produk
- Klik "Add to cart"
- Seharusnya redirect ke `/cart` dan item muncul

### 3. Verify Cart Contents
Buka di browser:
```
http://localhost/trade-coin/public/cart
```

Seharusnya menampilkan semua items yang di-add.

---

## Troubleshooting

### Problem: Add to cart tidak berfungsi

**Solution 1: Run setup again**
```
http://localhost/trade-coin/public/setup-cart-final.php
```

**Solution 2: Check database status**
```
http://localhost/trade-coin/public/cart-diagnostic.php
```

Ini akan show:
- Database connection status
- Table existence check
- Cart table structure
- Current user session
- Product data
- Test add to cart result

### Problem: Tidak bisa login

Pastikan user sudah di-create dan password benar.

### Problem: Cart kosong

Cek di diagnostic:
```
http://localhost/trade-coin/public/setup-cart-final.php
```

### Problem: Error PDO / Database

Pastikan:
- MySQL server running
- Database `tradecoin` ada
- User `root` (password kosong)

---

## File References

| File | Purpose |
|------|---------|
| `setup-cart-final.php` | **Main setup** - Mulai dari sini |
| `cart-diagnostic.php` | Debug & verify cart system |
| `cart-setup.php` | Detailed setup dengan drop table |
| `test-cart.php` | Test insert ke cart |
| `auto-setup-cart.php` | JSON API untuk setup |

---

## Flow Diagram

```
Product Page (/products/{id})
    ↓
User klik "Add to cart"
    ↓
POST /cart/add
    ↓
CartController::addItem()
    ↓
CartModel::addItem() → INSERT INTO cart
    ↓
Redirect ke /cart
    ↓
CartController::cartView() → GET cart items
    ↓
Display cart.php dengan items
```

---

## Database Structure

### Cart Table
```sql
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_product (product_id)
);
```

---

## Testing Steps

### Step 1: Login
```
http://localhost/trade-coin/public/login
```

### Step 2: Go to products
```
http://localhost/trade-coin/public/products
```

### Step 3: Click product (e.g., /products/8)
```
http://localhost/trade-coin/public/products/8
```

### Step 4: Click "Add to cart"
- Should redirect to `/cart`
- Item should appear

### Step 5: Verify in database
```
http://localhost/trade-coin/public/setup-cart-final.php
```

---

## Common Issues & Fixes

| Issue | Cause | Fix |
|-------|-------|-----|
| Cart table not found | Table not created | Run setup-cart-final.php |
| Insert failed | Foreign key error | Check user & product exist |
| Empty cart | No items added | Add item from product page |
| Session not found | Not logged in | Login first |
| 404 on /cart | Route not registered | Check public/index.php routes |

---

## Auto-Initialize

CartModel now automatically creates the cart table if it doesn't exist. 

Lihat di: `app/models/Cart.php` - method `ensureCartTableExists()`

---

## Support

Jika masih ada masalah:

1. Jalankan setup: `http://localhost/trade-coin/public/setup-cart-final.php`
2. Check diagnostic: `http://localhost/trade-coin/public/cart-diagnostic.php`
3. Lihat database directly di phpMyAdmin
4. Check browser console untuk JavaScript errors

---

Last Updated: May 12, 2026
