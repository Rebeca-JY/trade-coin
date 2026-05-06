# Admin Users CRUD - Quick Reference

## 🎯 Quick Start

```
URL: http://localhost/admin/users
```

---

## 📍 Routes

| Method | Route | Action |
|--------|-------|--------|
| GET | `/admin/users` | List users |
| GET | `/admin/users/create` | Show create form |
| POST | `/admin/users/create` | Save new user |
| GET | `/admin/users/show?id=1` | Show user detail |
| GET | `/admin/users/edit?id=1` | Show edit form |
| POST | `/admin/users/edit?id=1` | Update user |
| GET | `/admin/users/delete?id=1` | Show delete confirmation |
| POST | `/admin/users/delete?id=1` | Delete user |

---

## 📁 Files Created/Modified

```
NEW FILES:
✓ app/controllers/AdminUserController.php (NEW)
✓ app/views/admin/users/index.php (NEW)
✓ app/views/admin/users/create.php (NEW)
✓ app/views/admin/users/show.php (NEW)
✓ app/views/admin/users/edit.php (NEW)
✓ app/views/admin/users/delete.php (NEW)
✓ app/views/admin/component/navbar.php (NEW)
✓ public/test-admin-users.php (NEW)
✓ ADMIN_USERS_CRUD.md (NEW)
✓ CRUD_USERS_SUMMARY.md (NEW)
✓ TECHNICAL_DOCUMENTATION.md (NEW)

MODIFIED FILES:
✓ app/models/User.php (Added: getAllUsers, updateUser, deleteUser)
✓ public/index.php (Added: 8 admin user routes)
```

---

## 🔧 Form Fields

### Create/Edit Form
```
Username*         - Text input, unique, required
Email*            - Email input, unique, required
Password*         - Password input (6+ chars), required on create, optional on edit
Nama Lengkap*     - Text input, required
Nomor HP          - Tel input, optional
Alamat            - Textarea, optional
Role*             - Select: buyer, seller, admin
Toko Nama         - Text input, optional (for seller)
```

---

## 🎨 UI Elements

### List View
- Table with 7 columns: ID, Username, Email, Name, Role, Registered, Actions
- Action buttons: View👁, Edit✏️, Delete🗑️
- Role badges: Admin(Red), Seller(Purple), Buyer(Blue)
- Success message bar with close button

### Forms
- Grid layout (2 columns on desktop, 1 on mobile)
- Red error messages with icon
- Tailwind styling with hover effects
- Submit & Cancel buttons

### Detail View
- Cards showing user information
- Action buttons: Edit, Delete, Back
- No password displayed

### Delete Confirmation
- Warning message
- User info display for verification
- Confirm & Cancel buttons

---

## 🔍 Key Methods

### Controller Methods
```php
$controller->index()        // List users
$controller->create()       // Create form & process
$controller->show()         // Show detail
$controller->edit()         // Edit form & process
$controller->delete()       // Delete confirmation & process
```

### Model Methods
```php
$user->getAllUsers()        // Get all users
$user->updateUser($data)    // Update user
$user->deleteUser($id)      // Delete user
$user->getUserById($id)     // Get by ID
$user->getUserByUsername()  // Get by username
$user->getUserByEmail()     // Get by email
$user->register()           // Register new user
$user->login()              // Login user
```

---

## ✅ Validation Rules

| Field | Rules |
|-------|-------|
| username | Required, unique, max 100 chars |
| email | Required, unique, valid email format |
| password | Required (create), min 6 chars, bcrypt hashed |
| nama_lengkap | Required, max 255 chars |
| nomor_hp | Optional, max 20 chars |
| alamat | Optional |
| role | Required, enum: buyer, seller, admin |
| toko_nama | Optional, max 255 chars (for seller) |

---

## 🔐 Security

```
✓ SQL Injection: Prepared statements (PDO)
✓ XSS: htmlspecialchars() on all outputs
✓ Password: bcrypt hashing + password_verify()
✓ Unique constraint: username & email
✓ Delete confirmation: Prevents accidents
```

---

## 🧪 Test Commands

```bash
# Run test file
php public/test-admin-users.php

# Test a specific route
curl http://localhost/admin/users

# Test with parameters
curl "http://localhost/admin/users/show?id=1"
```

---

## 🐛 Debugging

### Check if routes registered
```php
// In public/index.php, look for:
$router->add('GET', '/admin/users', 'AdminUserController', 'index');
```

### Check if views exist
```bash
ls -la app/views/admin/users/
# Should show: index.php create.php show.php edit.php delete.php
```

### Check if controller exists
```bash
ls -la app/controllers/AdminUserController.php
# File should exist
```

### Test database connection
```bash
# In public/test-admin-users.php
php public/test-admin-users.php
```

---

## 💡 Common Tasks

### Add new user programmatically
```php
$user = new User();
$result = $user->register([
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'password' => 'MyPassword123',
    'nama_lengkap' => 'John Doe',
    'role' => 'seller'
]);
```

### Get user by ID
```php
$user = new User();
$userData = $user->getUserById(1);
```

### Update user
```php
$user = new User();
$user->updateUser([
    'id' => 1,
    'username' => 'new_username',
    'email' => 'new@example.com'
]);
```

### Delete user
```php
$user = new User();
$user->deleteUser(1);
```

---

## 📊 Database Schema

```sql
users table:
- id (INT, PK, AUTO_INCREMENT)
- username (VARCHAR 100, UNIQUE)
- email (VARCHAR 100, UNIQUE)
- password (VARCHAR 255)
- nama_lengkap (VARCHAR 255)
- nomor_hp (VARCHAR 20)
- alamat (TEXT)
- role (ENUM: buyer, seller, admin)
- toko_nama (VARCHAR 255)
- created_at (TIMESTAMP)
```

---

## 🎓 Learning Path

1. **Understanding the system**
   - Read CRUD_USERS_SUMMARY.md
   
2. **Detailed technical info**
   - Read TECHNICAL_DOCUMENTATION.md
   
3. **Hands-on testing**
   - Run: php public/test-admin-users.php
   - Open: http://localhost/admin/users
   
4. **Customization**
   - Modify views for styling
   - Add new fields to forms
   - Update validation rules

---

## 🚀 Quick Deployment Checklist

```
✓ Copy all files to server
✓ Update database.php with server credentials
✓ Create users table in database
✓ Run test file: php public/test-admin-users.php
✓ Test in browser: http://yoursite.com/admin/users
✓ Create first admin user
✓ Test all CRUD operations
✓ Deploy to production
```

---

## 📞 Support

For issues or questions, refer to:
- CRUD_USERS_SUMMARY.md - Overview & features
- TECHNICAL_DOCUMENTATION.md - Architecture & implementation
- public/test-admin-users.php - Testing & validation
- app/controllers/AdminUserController.php - Main logic

---

## 🎯 Next Steps

1. Access: `http://localhost/admin/users`
2. Create first admin user
3. Test all operations (Create, Read, Update, Delete)
4. Customize styling if needed
5. Add to your workflow
6. Consider enhancements (search, pagination, etc.)

---

**Status: ✅ PRODUCTION READY**
