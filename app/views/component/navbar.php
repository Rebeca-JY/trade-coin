<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeCoin Navbar</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <nav class="navbar">
    <div class="logo"><img src="/foto/logo.png" alt="TradeCoin Logo"></div>

    <ul class="menu">
        <li><a href="/products">Search Item & Service</a></li>
        <li><a href="#">Sell Item & Service</a></li>
        <li><a href="#">Guide</a></li>
        <li><a href="#">Contact Us</a></li>
    </ul>

    <div class="right">
        <a href="/messages" class="icon-link">
            <i class="fas fa-envelope"></i>
            <span class="message-count">3</span>
        </a>
        <a href="/cart" class="icon-link">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">5</span>
        </a>

        <div class="user-container">
            <div class="avatar" id="tcUserIcon">
                <i class="fas fa-user-circle"></i>
            </div>
            
            <div class="dropdown" id="tcDropdown">
                <p class="balance">Points : <b>500</b></p>
                <hr>
                <a href="#"><i class="fa-regular fa-user"></i> Account Profile</a>
                <a href="#"><i class="fa-solid fa-gear"></i> Account Setting</a>
                <a href="#"><i class="fa-solid fa-clock-rotate-left"></i> Transaction History</a>
                <a href="#" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>
    
    <script defer src="../js/navbar.js"></script>
</body>
</html>