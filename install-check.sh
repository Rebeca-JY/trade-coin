#!/bin/bash
# Admin Users CRUD System - Installation & Verification Script

echo "=================================="
echo "Admin Users CRUD - Installation Check"
echo "=================================="
echo ""

# Check controller
echo "[1/12] Checking AdminUserController.php..."
if [ -f "app/controllers/AdminUserController.php" ]; then
    echo "✅ AdminUserController.php exists"
else
    echo "❌ AdminUserController.php NOT FOUND"
fi

# Check views
echo ""
echo "[2-6/12] Checking view files..."
views=("index" "create" "show" "edit" "delete")
for view in "${views[@]}"; do
    if [ -f "app/views/admin/users/${view}.php" ]; then
        echo "✅ views/admin/users/${view}.php exists"
    else
        echo "❌ views/admin/users/${view}.php NOT FOUND"
    fi
done

# Check navbar
echo ""
echo "[7/12] Checking admin navbar..."
if [ -f "app/views/admin/component/navbar.php" ]; then
    echo "✅ navbar.php exists"
else
    echo "❌ navbar.php NOT FOUND"
fi

# Check if User.php has new methods
echo ""
echo "[8/12] Checking User.php for new methods..."
if grep -q "public function getAllUsers" app/models/User.php; then
    echo "✅ getAllUsers() method exists"
else
    echo "❌ getAllUsers() method NOT FOUND"
fi

if grep -q "public function updateUser" app/models/User.php; then
    echo "✅ updateUser() method exists"
else
    echo "❌ updateUser() method NOT FOUND"
fi

if grep -q "public function deleteUser" app/models/User.php; then
    echo "✅ deleteUser() method exists"
else
    echo "❌ deleteUser() method NOT FOUND"
fi

# Check if routes are registered
echo ""
echo "[9/12] Checking routes registration..."
if grep -q "/admin/users" public/index.php; then
    echo "✅ Admin user routes registered"
    route_count=$(grep -c "/admin/users" public/index.php)
    echo "   Found $route_count route definitions"
else
    echo "❌ Admin user routes NOT FOUND"
fi

# Check test file
echo ""
echo "[10/12] Checking test file..."
if [ -f "public/test-admin-users.php" ]; then
    echo "✅ test-admin-users.php exists"
else
    echo "❌ test-admin-users.php NOT FOUND"
fi

# Check documentation files
echo ""
echo "[11-12/12] Checking documentation..."
docs=("ADMIN_USERS_CRUD.md" "CRUD_USERS_SUMMARY.md" "TECHNICAL_DOCUMENTATION.md" "QUICK_REFERENCE.md" "IMPLEMENTATION_COMPLETE.md")
for doc in "${docs[@]}"; do
    if [ -f "$doc" ]; then
        echo "✅ $doc exists"
    else
        echo "❌ $doc NOT FOUND"
    fi
done

echo ""
echo "=================================="
echo "Installation Check Complete!"
echo "=================================="
echo ""
echo "Next steps:"
echo "1. Verify database has 'users' table"
echo "2. Run: php public/test-admin-users.php"
echo "3. Visit: http://localhost/admin/users"
echo ""
echo "For more information:"
echo "- Read: QUICK_REFERENCE.md (quick start)"
echo "- Read: CRUD_USERS_SUMMARY.md (overview)"
echo "- Read: TECHNICAL_DOCUMENTATION.md (details)"
echo ""
