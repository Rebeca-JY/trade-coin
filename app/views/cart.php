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

<?php include '../app/views/component/navbar.php'; ?>

<main class="cart-container">

    <div class="cart-item">

        <input type="checkbox" class="item-check" checked>

        <div class="product-info">
            <img src="./foto/pulpen.png" alt="Pulpen Elitis" class="product-image">

            <div class="product-details">
                <h3>Pulpen Elitis</h3>
                <p class="shop-name">toko bahagia</p>
                <p class="unit-price"> 27 Points</p>

                <div class="controls">
                    <button class="qty-btn">-</button>
                    <span class="qty-num">3</span>
                    <button class="qty-btn">+</button>
                    <button class="delete-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

            </div>
        </div>

        <div class="item-total"> 81 Points</div>

    </div>


    <div class="cart-item">

        <input type="checkbox" class="item-check" checked>

        <div class="product-info">
            <img src="./foto/penpink.png" alt="Pulpen Clingy" class="product-image">

            <div class="product-details">
                <h3>Pulpen Clingy</h3>
                <p class="shop-name">toko barbie</p>
                <p class="unit-price"> 99 Points</p>

                <div class="controls">
                    <button class="qty-btn">-</button>
                    <span class="qty-num">1</span>
                    <button class="qty-btn">+</button>
                    <button class="delete-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

            </div>
        </div>

        <div class="item-total"> 99 Points</div>

    </div>


    <div class="cart-item">

        <input type="checkbox" class="item-check" checked>

        <div class="product-info">
            <img src="./foto/cutter.png" alt="Cater Cat" class="product-image">

            <div class="product-details">
                <h3>Cater Cat</h3>
                <p class="shop-name">toko kucing</p>
                <p class="unit-price"> 20 Points</p>

                <div class="controls">
                    <button class="qty-btn">-</button>
                    <span class="qty-num">1</span>
                    <button class="qty-btn">+</button>
                    <button class="delete-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

            </div>
        </div>

        <div class="item-total"> 20 Points</div>

    </div>

</main>


<footer class="checkout-footer">

    <h2 class="grand-total">Total : 0 Points</h2>

    <button class="btn-checkout">
        Check out
    </button>

</footer>

<script src="js/cart.js"></script>

</body>
</html>