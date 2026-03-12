<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin - Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="cart-container">

    <main class="cart-container">
        <div class="cart-item">
            <div class="product-info">
                <img src="https://via.placeholder.com/120" alt="Pulpen Elitis" class="product-image">
                <div class="product-details">
                    <h3>Pulpen Elitis</h3>
                    <p class="shop-name">toko bahagia</p>
                    <p class="unit-price">Rp. 27.900</p>
                    <div class="controls">
                        <button class="qty-btn">-</button>
                        <span class="qty-num">3</span>
                        <button class="qty-btn">+</button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="item-total">Rp. 81.000</div>
        </div>

        <div class="cart-item">
            <div class="product-info">
                <img src="https://via.placeholder.com/120" alt="Pulpen Clingy" class="product-image">
                <div class="product-details">
                    <h3>Pulpen Clingy</h3>
                    <p class="shop-name">toko barbie</p>
                    <p class="unit-price">Rp. 99.000</p>
                    <div class="controls">
                        <button class="qty-btn">-</button>
                        <span class="qty-num">1</span>
                        <button class="qty-btn">+</button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="item-total">Rp. 99.000</div>
        </div>

        <div class="cart-item">
            <div class="product-info">
                <img src="https://via.placeholder.com/120" alt="Cater Cat" class="product-image">
                <div class="product-details">
                    <h3>Cater Cat</h3>
                    <p class="shop-name">toko kucing</p>
                    <p class="unit-price">Rp. 20.000</p>
                    <div class="controls">
                        <button class="qty-btn">-</button>
                        <span class="qty-num">1</span>
                        <button class="qty-btn">+</button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="item-total">Rp. 20.000,00</div>
        </div>
    </main>

    <footer class="checkout-footer">
        <h2 class="grand-total">Total : Rp 200.000</h2>
        <button class="btn-checkout">Check out</button>
    </footer>

    <script src="js/cart.js"></script>
</body>
</html>