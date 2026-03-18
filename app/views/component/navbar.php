<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            Trade<span>Coin</span>
        </div>
    
        <ul class="menu">
            <li><a href="/products">Search Item & Service</a></li>
            <li><a href="#">Sell Item & Service</a></li>
            <li><a href="#">Guide</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    
        <div class="right">
            <div class="cart">
            <a href="/cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">5</span>
            </a>
            </div>
    
       
            <div class="user">
                <div class="avatar" id="tcUserIcon">
                    <i class="fas fa-user-circle"></i>
                </div>
            </div>
    
            <div class="dropdown" id="tcDropdown">
    
                <p class="balance">
                        Points : <b>500</b>
                </p>
    
                <a href="#">
                    <i class="fa fa-user"></i>
                        Account Profile
                </a>
    
                <a href="#">
                    <i class="fa fa-gear"></i>
                        Account Setting
                    </a>
    
                <a href="#">
                    <i class="fa fa-clock"></i>
                        Transaction History
                    </a>
    
                <a href="#">
                    <i class="fa fa-right-from-bracket"></i>
                        Logout
                    </a>
    
                </div>
            </div>
    
    </nav>
    
    <script defer src="../js/navbar.js"></script>
</body>
</html>
