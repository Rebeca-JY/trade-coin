# Sistem CRUD Admin Users

## Deskripsi
Sistem CRUD lengkap untuk manajemen users di halaman admin menggunakan route `/admin/users`.

## Routes
```
GET     /admin/users              - Tampilkan daftar semua users
GET     /admin/users/create       - Tampilkan form tambah user
POST    /admin/users/create       - Proses tambah user baru
GET     /admin/users/show         - Tampilkan detail user (dengan parameter id)
GET     /admin/users/edit         - Tampilkan form edit user (dengan parameter id)
POST    /admin/users/edit         - Proses edit user
GET     /admin/users/delete       - Tampilkan form delete user (dengan parameter id)
POST    /admin/users/delete       - Proses hapus user
```

## File yang Dibuat

### Controllers
- `app/controllers/AdminUserController.php` - Logic untuk CRUD users

### Models
- `app/models/User.php` - Ditambahkan method:
  - `getAllUsers()` - Ambil semua users
  - `updateUser($data)` - Update user data
  - `deleteUser($id)` - Hapus user

### Views
- `app/views/admin/users/index.php` - Daftar semua users dengan action buttons
- `app/views/admin/users/create.php` - Form tambah user baru
- `app/views/admin/users/show.php` - Detail user
- `app/views/admin/users/edit.php` - Form edit user
- `app/views/admin/users/delete.php` - Konfirmasi hapus user

### Navbar
- `app/views/admin/component/navbar.php` - Admin navbar dengan menu Users

## Fitur Utama

### 1. List Users (READ)
- Menampilkan daftar semua users dalam format tabel
- Menampilkan: ID, Username, Email, Nama Lengkap, Role, Tanggal Terdaftar
- Action buttons: View, Edit, Delete
- Responsive dan mobile-friendly

### 2. Tambah User (CREATE)
- Form lengkap dengan field:
  - Username (unique, required)
  - Email (unique, required)
  - Password (required, min 6 char)
  - Nama Lengkap (required)
  - Nomor HP (optional)
  - Alamat (optional)
  - Role (buyer/seller/admin)
  - Toko Nama (untuk seller)
- Validasi di backend:
  - Check username/email belum terdaftar
  - Hash password dengan bcrypt
  - Redirect ke list users jika sukses

### 3. Detail User (READ)
- Menampilkan semua informasi user
- Tidak ada password yang ditampilkan
- Button untuk Edit dan Delete
- Button kembali ke list

### 4. Edit User (UPDATE)
- Form pre-filled dengan data user yang ada
- Field password optional (hanya jika ingin mengubah)
- Validasi tidak boleh ada username/email yang duplicate
- Password di-hash jika ada perubahan
- Success message setelah update

### 5. Hapus User (DELETE)
- Confirmation page dengan info user
- Warning message tentang penghapusan data terkait
- Button Ya/Tidak untuk konfirmasi
- Success message setelah hapus

## Security Features
✅ Password hashing dengan bcrypt
✅ SQL Injection protection via prepared statements
✅ XSS protection via htmlspecialchars()
✅ Unique validation untuk username/email
✅ Confirmation untuk operasi delete
✅ Input validation di backend

## Database Schema (users table)
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    nomor_hp VARCHAR(20),
    alamat TEXT,
    role ENUM('buyer', 'seller', 'admin') DEFAULT 'buyer',
    toko_nama VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Cara Menggunakan

1. **Akses List Users**
   - URL: http://localhost/admin/users
   - Tampilkan daftar semua users

2. **Tambah User Baru**
   - Klik tombol "Tambah User"
   - Isi form lengkap
   - Klik "Simpan User"

3. **Lihat Detail User**
   - Klik icon mata di daftar users
   - Atau akses: http://localhost/admin/users/show?id=1

4. **Edit User**
   - Klik icon pensil di daftar users
   - Atau akses: http://localhost/admin/users/edit?id=1
   - Update data dan klik "Simpan Perubahan"

5. **Hapus User**
   - Klik icon trash di daftar users
   - Atau akses: http://localhost/admin/users/delete?id=1
   - Konfirmasi dengan klik "Ya, Hapus User"

## Error Handling
- Username sudah terdaftar
- Email sudah terdaftar
- Password tidak sesuai standar
- User tidak ditemukan (saat edit/delete)
- Database errors

## Design
- Menggunakan Tailwind CSS untuk styling
- Font Awesome icons untuk visual
- Responsive layout (mobile, tablet, desktop)
- Color-coded roles (red=admin, purple=seller, blue=buyer)
- Success/Error messages dengan styling konsisten

## Testing Checklist
- [ ] List users menampilkan semua users dengan benar
- [ ] Tambah user baru dengan semua field
- [ ] Validasi unique username/email berfungsi
- [ ] Edit user - update fields dan password
- [ ] Delete user dengan konfirmasi
- [ ] Success/Error messages muncul
- [ ] Responsive di mobile device
- [ ] Password tidak ditampilkan di list/detail
