# Dokumentasi Teknis - Admin Users CRUD System

## 1. Architecture Overview

```
├── Controllers
│   └── AdminUserController.php
│       ├── index()     → GET /admin/users
│       ├── create()    → GET/POST /admin/users/create
│       ├── show()      → GET /admin/users/show?id=X
│       ├── edit()      → GET/POST /admin/users/edit?id=X
│       └── delete()    → GET/POST /admin/users/delete?id=X
│
├── Models
│   └── User.php (updated with new methods)
│       ├── getAllUsers()
│       ├── updateUser($data)
│       └── deleteUser($id)
│
├── Views
│   └── admin/users/
│       ├── index.php   → List all users
│       ├── create.php  → Create form
│       ├── show.php    → User detail
│       ├── edit.php    → Edit form
│       └── delete.php  → Delete confirmation
│
├── Components
│   └── admin/component/navbar.php
│
└── Routes (in public/index.php)
    ├── GET  /admin/users
    ├── GET  /admin/users/create
    ├── POST /admin/users/create
    ├── GET  /admin/users/show
    ├── GET  /admin/users/edit
    ├── POST /admin/users/edit
    ├── GET  /admin/users/delete
    └── POST /admin/users/delete
```

---

## 2. Controller Flow

### AdminUserController.php

```php
class AdminUserController {
    
    private $userModel;  // User model instance
    
    private function render($view, $data)
        // Helper untuk render view dengan data
        // Extract data ke variables
        // Require view file dari views/admin/users/
    
    public function index()
        // GET /admin/users
        // 1. Get all users dari model
        // 2. Pass ke view: users, totalUsers, pageTitle
        // 3. Render index.php
    
    public function create()
        // GET/POST /admin/users/create
        // GET: Render form create.php
        // POST: 
        //   1. Get POST data
        //   2. Validasi (username, email, password required)
        //   3. Cek unique username/email
        //   4. Call model->register()
        //   5. Redirect to /admin/users if success
        //   6. Show errors if failed
    
    public function show()
        // GET /admin/users/show?id=X
        // 1. Get id dari $_GET
        // 2. Call model->getUserById(id)
        // 3. Render show.php dengan user data
    
    public function edit()
        // GET/POST /admin/users/edit?id=X
        // GET: 
        //   1. Get user dari model
        //   2. Render edit.php dengan form pre-filled
        // POST:
        //   1. Get POST data
        //   2. Validasi (username, email, name required)
        //   3. Cek unique username/email (exclude current user)
        //   4. Password hashing jika diisi
        //   5. Call model->updateUser()
        //   6. Redirect to /admin/users if success
    
    public function delete()
        // GET/POST /admin/users/delete?id=X
        // GET:
        //   1. Get user dari model
        //   2. Render delete.php (confirmation page)
        // POST:
        //   1. Call model->deleteUser(id)
        //   2. Redirect to /admin/users if success
}
```

---

## 3. Model Methods

### User.php - New Methods

```php
/**
 * Get all users
 * @return array
 * Query: SELECT id, username, email, nama_lengkap, role, created_at FROM users ORDER BY created_at DESC
 */
public function getAllUsers()

/**
 * Update user (admin)
 * @param array $data ['id' => int, 'username' => string, ...]
 * @return bool
 * Logic:
 *   1. Extract id dari data
 *   2. Hash password jika ada di data
 *   3. Call db->update() dengan data
 */
public function updateUser($data)

/**
 * Delete user
 * @param int $id
 * @return bool
 * Query: DELETE FROM users WHERE id = ?
 */
public function deleteUser($id)
```

---

## 4. View Structure

### index.php - List Users
```html
<!DOCTYPE html>
<html>
<head>
    - Tailwind CSS + Font Awesome
</head>
<body>
    <nav> - Admin navbar </nav>
    
    <div class="container">
        <!-- Header with add button -->
        <h1>Manajemen Users</h1>
        <a href="/admin/users/create">+ Tambah User</a>
        
        <!-- Success message (from ?success=) -->
        <?php if ($_GET['success']): ?>
            Show green alert
        <?php endif; ?>
        
        <!-- Users Table -->
        <table>
            <thead>
                ID | Username | Email | Nama Lengkap | Role | Terdaftar | Actions
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td> $user['id'] </td>
                    <td> $user['username'] </td>
                    <td> $user['email'] </td>
                    <td> $user['nama_lengkap'] </td>
                    <td>
                        <badge style=role>
                            admin=red, seller=purple, buyer=blue
                        </badge>
                    </td>
                    <td> date format </td>
                    <td>
                        <a href="/admin/users/show?id=X">👁 View</a>
                        <a href="/admin/users/edit?id=X">✏️ Edit</a>
                        <a href="/admin/users/delete?id=X">🗑️ Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Empty state -->
        <?php if (empty($users)): ?>
        <p>Belum ada users</p>
        <?php endif; ?>
    </div>
</body>
</html>
```

### create.php - Create Form
```html
<!DOCTYPE html>
<html>
<body>
    <form method="POST">
        <!-- Error display -->
        <?php if ($errors): ?>
            <div class="alert alert-red">
                <ul> error list </ul>
            </div>
        <?php endif; ?>
        
        <!-- Form fields -->
        <input name="username" required />
        <input name="email" type="email" required />
        <input name="password" type="password" minlength="6" required />
        <input name="nama_lengkap" required />
        <input name="nomor_hp" />
        <textarea name="alamat" />
        
        <select name="role">
            <option>buyer</option>
            <option>seller</option>
            <option>admin</option>
        </select>
        
        <input name="toko_nama" placeholder="Jika seller" />
        
        <button type="submit">Simpan User</button>
        <a href="/admin/users">Batal</a>
    </form>
</body>
</html>
```

### show.php - User Detail
```html
<!DOCTYPE html>
<html>
<body>
    <!-- Display user fields as read-only -->
    <div class="user-card">
        <p>ID: $user['id']</p>
        <p>Username: $user['username']</p>
        <p>Email: $user['email']</p>
        <p>Nama Lengkap: $user['nama_lengkap']</p>
        <p>Nomor HP: $user['nomor_hp']</p>
        <p>Alamat: $user['alamat']</p>
        <p>Role: <badge>$user['role']</badge></p>
        
        <?php if ($user['role'] == 'seller'): ?>
        <p>Toko: $user['toko_nama']</p>
        <?php endif; ?>
        
        <p>Terdaftar: $user['created_at']</p>
    </div>
    
    <!-- Action buttons -->
    <a href="/admin/users/edit?id=X">✏️ Edit</a>
    <a href="/admin/users/delete?id=X">🗑️ Delete</a>
    <a href="/admin/users">Kembali</a>
</body>
</html>
```

### edit.php - Edit Form
```html
<!DOCTYPE html>
<html>
<body>
    <form method="POST">
        <!-- Same fields as create.php -->
        <!-- But:
             - Username & Email pre-filled
             - Password field is OPTIONAL (leave blank to keep old password)
             - All other fields pre-filled
        -->
        
        <input name="username" value="$user['username']" required />
        <input name="email" value="$user['email']" required />
        <input name="password" placeholder="Kosongkan jika tidak diubah" />
        <!-- ... other fields pre-filled ... -->
        
        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
```

### delete.php - Delete Confirmation
```html
<!DOCTYPE html>
<html>
<body>
    <div class="confirmation-card">
        <h1>Hapus User?</h1>
        <p>Tindakan ini tidak dapat dibatalkan</p>
        
        <!-- Display user info for verification -->
        <div class="user-info">
            <p>Username: $user['username']</p>
            <p>Email: $user['email']</p>
            <p>Nama: $user['nama_lengkap']</p>
        </div>
        
        <!-- Warning -->
        <div class="alert alert-yellow">
            Menghapus user akan menghapus semua data terkait
        </div>
        
        <!-- Confirm form -->
        <form method="POST">
            <button type="submit">Ya, Hapus User</button>
            <a href="/admin/users">Batal</a>
        </form>
    </div>
</body>
</html>
```

---

## 5. Data Flow Examples

### CREATE Flow
```
User fills form → POST /admin/users/create
    ↓
AdminUserController->create() receives POST
    ↓
Validate inputs (required fields)
    ↓
Check unique username/email → User model->getUserByUsername/Email()
    ↓
If validation passes → User model->register()
    ↓
register() hashes password & inserts to DB
    ↓
Success → Redirect to /admin/users?success=...
         → View shows success message
    ↓
Error → Re-render create.php with error messages
```

### UPDATE Flow
```
User opens /admin/users/edit?id=1 (GET request)
    ↓
AdminUserController->edit() GET method
    ↓
Get user from DB → User model->getUserById(1)
    ↓
Render edit.php with pre-filled form
    ↓
User edits & submits form (POST)
    ↓
AdminUserController->edit() POST method
    ↓
Validate inputs
    ↓
Check unique username/email (exclude current user)
    ↓
Hash password if provided
    ↓
Call User model->updateUser($data)
    ↓
Update query executed in DB
    ↓
Success → Redirect to /admin/users?success=...
```

### DELETE Flow
```
User clicks delete icon → GET /admin/users/delete?id=1
    ↓
AdminUserController->delete() GET method
    ↓
Get user info from DB
    ↓
Render delete.php confirmation page
    ↓
User confirms & submits form (POST)
    ↓
AdminUserController->delete() POST method
    ↓
Call User model->deleteUser(1)
    ↓
Delete query executed in DB
    ↓
Success → Redirect to /admin/users?success=...
```

---

## 6. Database Queries

```sql
-- Get all users
SELECT id, username, email, nama_lengkap, role, created_at 
FROM users 
ORDER BY created_at DESC

-- Insert new user
INSERT INTO users (username, email, password, nama_lengkap, nomor_hp, alamat, role, toko_nama, created_at) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)

-- Get user by ID
SELECT * FROM users WHERE id = ?

-- Update user
UPDATE users 
SET username=?, email=?, nama_lengkap=?, nomor_hp=?, alamat=?, role=?, toko_nama=? 
WHERE id = ?

-- Delete user
DELETE FROM users WHERE id = ?

-- Get user by username (for unique check)
SELECT * FROM users WHERE username = ?

-- Get user by email (for unique check)
SELECT * FROM users WHERE email = ?
```

---

## 7. Security Implementation

### SQL Injection Prevention
```php
// ✓ SAFE - Using prepared statements
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);

// ✗ UNSAFE - String concatenation
$query = "SELECT * FROM users WHERE id = " . $id;
```

### XSS Prevention
```php
// ✓ SAFE - Using htmlspecialchars()
echo htmlspecialchars($user['username']);

// ✗ UNSAFE - Direct output
echo $user['username'];
```

### Password Security
```php
// ✓ SAFE - Using PASSWORD_BCRYPT
$hashed = password_hash($password, PASSWORD_BCRYPT);

// ✓ SAFE - Verify password
password_verify($plain_password, $hashed);

// ✗ UNSAFE - Storing plain password
$password = $_POST['password'];
```

---

## 8. Error Handling

### Validation Errors
```php
// Username already taken
if (!empty(getUserByUsername($username))) {
    $errors[] = 'Username sudah terdaftar';
}

// Email already taken
if (!empty(getUserByEmail($email))) {
    $errors[] = 'Email sudah terdaftar';
}

// Empty required field
if (empty($username)) {
    $errors[] = 'Username harus diisi';
}
```

### Database Errors
```php
try {
    $result = $userModel->register($data);
    if ($result !== false) {
        // Success
    } else {
        $errors[] = 'Gagal menyimpan user';
    }
} catch (Exception $e) {
    $errors[] = 'Database error: ' . $e->getMessage();
}
```

---

## 9. Testing Checklist

```
✓ Can view list of all users
✓ Can create new user with all fields
✓ Cannot create duplicate username
✓ Cannot create duplicate email
✓ Can view user detail
✓ Can edit user (all fields)
✓ Can skip password field when editing
✓ Can delete user with confirmation
✓ Success messages appear
✓ Error messages appear
✓ Forms validate required fields
✓ Password is hashed correctly
✓ Date formats are correct
✓ Role badges display correctly
✓ Responsive on mobile
```

---

## 10. Troubleshooting

### Issue: Routes not working
Solution:
1. Check routes are added in public/index.php
2. Check Router.php supports query parameters
3. Clear browser cache

### Issue: Password not hashing
Solution:
1. Verify PASSWORD_BCRYPT is available (PHP 5.5+)
2. Check password length requirement (min 6)

### Issue: Cannot create user
Solution:
1. Check database connection
2. Check unique constraint on username/email
3. Check file permissions

### Issue: Views not rendering
Solution:
1. Check folder structure matches `render()` path
2. Check file permissions (readable)
3. Check PHP syntax in view files

---

## 11. Performance Considerations

- Queries are optimized with necessary fields only
- Indexes on username & email (UNIQUE)
- No N+1 query problem (single query per operation)
- Pagination can be added for large user lists

---

## 12. Future Enhancements

- [ ] Pagination for user list
- [ ] Search/filter users
- [ ] Bulk operations (delete multiple)
- [ ] User activity logging
- [ ] Role-based permissions
- [ ] Soft delete (archive instead of delete)
- [ ] Export to CSV/Excel
- [ ] User status (active/inactive)
- [ ] Last login timestamp
