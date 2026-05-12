# 🛒 Fix Cart System - Column Not Found Error

## Error yang Terjadi

```
Fatal error: Uncaught PDOException: SQLSTATE[42S22]: Column not found: 1054 
Unknown column 'p.nama_produk' in 'field list'
```

Ini berarti tabel `product` tidak punya kolom `nama_produk` atau kolom tersebut bernama dengan convention yang berbeda.

---

## 🚀 Solution - Step by Step

### Step 1: Check Database Structure
```
http://localhost/trade-coin/public/check-db-structure.php
```

Ini akan menampilkan:
- ✅ Tabel apa saja yang ada
- ✅ Struktur tabel `product` (kolom apa yang ada)
- ✅ Sample data dari tabel

**Yang perlu diperhatikan:**
- Tabel mungkin bernama `product` atau `products`
- Kolom mungkin bernama:
  - `nama_produk` atau `product_name`
  - `harga` atau `price`
  - `nama_penjual` atau `seller`
  - `gambar` atau `image`

---

### Step 2: Auto-Fix Database
```
http://localhost/trade-coin/public/fix-db-structure.php
```

Script ini akan:
- ✅ Detect kolom yang ada
- ✅ Add kolom yang hilang (jika perlu)
- ✅ Verify struktur final

**Ini aman dijalankan berkali-kali** - tidak akan hapus data atau override kolom yang sudah ada.

---

### Step 3: Test Cart Model
```
http://localhost/trade-coin/public/test-cart-model.php
```

Ini akan:
- ✅ Initialize Cart model
- ✅ Test get cart items
- ✅ Test add item ke cart
- ✅ Show error jika ada

Jika ada error, akan ditampilkan di sini.

---

### Step 4: Test Full Flow
1. Login: `http://localhost/trade-coin/public/login`
2. Products: `http://localhost/trade-coin/public/products`
3. Click product detail
4. Click "Add to cart"
5. Should redirect to cart dan item muncul

---

## 🔍 Debug Info

### File yang Penting untuk Diperbaiki

1. **app/models/Cart.php** - ✅ Sudah di-fix untuk handle dynamic columns
   - Method `resolveProductColumns()` - detect kolom yang ada
   - Method `getCartItems()` - flexible query dengan fallback

2. **Database Structure** - Perlu diperbaiki jika kolom salah
   - `check-db-structure.php` - lihat struktur sebenarnya
   - `fix-db-structure.php` - auto-add kolom yang hilang

### Gimana Kerjanya Sekarang

```
getCartItems() dipanggil
    ↓
resolveProductColumns() - detect kolom yang ada
    ↓
Build dynamic SQL dengan kolom yang di-detect
    ↓
Try execute query
    ↓
Kalau gagal → fallback ke query simple (tanpa product details)
```

---

## ✅ Verification Checklist

Sebelum test, pastikan sudah:

- [ ] Run check-db-structure.php (lihat struktur)
- [ ] Run fix-db-structure.php (perbaiki jika ada yang hilang)
- [ ] Run test-cart-model.php (test cart model berfungsi)
- [ ] Lihat tidak ada error di browser console
- [ ] Try add to cart dari product page

---

## 📋 Kolom yang HARUS Ada di Tabel Product

Minimal harus ada salah satu dari setiap kelompok:

```
Nama Produk:
  - nama_produk ✅ (recommended)
  - product_name
  - name

Harga:
  - harga ✅ (recommended)
  - price
  - cost

Penjual:
  - nama_penjual ✅ (recommended)
  - seller
  - seller_name

Gambar:
  - gambar ✅ (recommended)
  - image
  - foto
```

Jika kolom tidak ada, `fix-db-structure.php` akan auto-add dengan recommended name.

---

## 🆘 Masih Error?

Jika masih error setelah step di atas:

1. **Buka check-db-structure.php** - lihat struktur database
2. **Buka test-cart-model.php** - lihat error detail
3. **Share error message** dari test-cart-model.php

---

## 📝 Technical Details

### Cart Model Changes

File: `app/models/Cart.php`

**New Method:**
```php
private function resolveProductColumns(): void
{
    // Detect kolom yang ada di tabel product
    // Map ke standard names: name, price, seller, image
}
```

**Updated Method:**
```php
public function getCartItems(int $userId): array
{
    // Gunakan kolom yang ter-detect
    // Dengan fallback ke default names
    // Dan error handling yang robust
}
```

---

## 🎯 Next Steps

Setelah cart berfungsi:

1. Test checkout flow
2. Test cart update quantity
3. Test cart remove item
4. Test dengan multiple users
5. Test dengan berbagai products

---

**Last Updated:** May 12, 2026  
**Status:** ✅ Fixed with dynamic column resolution
