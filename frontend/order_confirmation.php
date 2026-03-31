<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .confirmation-container { max-width: 800px; margin: 60px auto; padding: 0 24px; min-height: 60vh; text-align: center; }
        .confirmation-title { font-family: var(--serif); font-size: 42px; font-weight: 300; margin-bottom: 20px; }
        .confirmation-subtitle { font-size: 18px; color: var(--text); margin-bottom: 40px; }
        .order-details { background: var(--cream); padding: 40px; border: 1px solid var(--sand); margin-bottom: 40px; text-align: left; }
        .order-info { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-group { }
        .info-label { font-size: 10.5px; letter-spacing: 0.15em; text-transform: uppercase; color: var(--brown); margin-bottom: 4px; }
        .info-value { font-family: var(--serif); font-size: 16px; color: var(--dark); }
        .order-items { margin-top: 30px; }
        .items-title { font-family: var(--serif); font-size: 20px; margin-bottom: 20px; }
        .item-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--sand); }
        .item-name { font-family: var(--serif); color: var(--dark); }
        .item-details { color: var(--text); font-size: 14px; }
        .item-price { font-family: var(--serif); color: var(--dark); }
        .order-total { font-family: var(--serif); font-size: 24px; color: var(--dark); text-align: right; margin-top: 20px; }
        .confirmation-actions { margin-top: 40px; }
        .confirmation-actions a { margin: 0 10px; }
    </style>
</head>
<body>

<div class="announcement">
  <div class="marquee-track" id="marquee">
    <span>Complimentary Shipping Over $100</span>
    <span>Discover Your Signature Scent</span>
    <span>Free Sample Vials With Every Order</span>
    <span>Artisan & Long-Lasting Fragrances</span>
  </div>
</div>

<header>
  <div class="header-inner">
    <nav>
      <a href="index.php">Home</a>
      <a href="fragrances.php">Fragrances</a>
    </nav>
    <div class="logo">le parfum</div>
    <div class="header-actions">
  <button class="search-btn">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="11" cy="11" r="8"></circle>
      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
    Search
  </button>

  <button class="cart-btn" onclick="window.location.href='cart.php'">
  Cart
  <span class="cart-count">0</span>
</button>

  <div class="account-dropdown-wrapper">
    <button id="accountBtn" style="font-size: 11.5px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text);">Account</button>

    <div id="accountDropdown" class="account-dropdown-menu">
      <div class="dropdown-header">
        <span class="dropdown-name">Guest</span>
      </div>
      <div class="dropdown-links">
        <a href="login.php" class="dropdown-item">Login</a>
        <a href="register.php" class="dropdown-item">Register</a>
        <hr class="dropdown-divider">
        <a href="#" class="dropdown-item" onclick="return false;">Logout</a>
      </div>
    </div>
  </div>
</div>
  </div>
</header>

<main class="confirmation-container">
    <h2 class="confirmation-title">Thank You for Your Order!</h2>
    <p class="confirmation-subtitle">Your order has been successfully placed and is being processed.</p>
    
    <div class="order-details" id="orderDetails">
        <!-- Order details will be loaded here -->
    </div>
    
    <div class="confirmation-actions">
        <a href="index.php" class="btn-primary">Continue Shopping</a>
        <a href="order_history.php" class="btn-outline">View Order History</a>
    </div>
</main>

<footer>
  <div class="footer-bottom" style="justify-content: center;">
    <p style="text-align: center; color: black; font-size: 14px; width: 100%;">© 2026 Le Parfum. All rights reserved.</p>
  </div>
</footer>

<button id="scrollToTopBtn" class="scroll-to-top" title="Go to top">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <polyline points="18,15 12,9 6,15"></polyline>
  </svg>
</button>

<script src="script.js"></script>

</body>
</html>