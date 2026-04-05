<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .cart-container { max-width: 1000px; margin: 60px auto; padding: 0 24px; min-height: 60vh; }
        .cart-title { font-family: var(--serif); font-size: 42px; font-weight: 300; margin-bottom: 40px; text-align: center; }
        .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .cart-table th { text-align: left; padding: 20px; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; color: var(--brown); border-bottom: 1px solid var(--sand); }
        .cart-table td { padding: 24px 20px; border-bottom: 1px solid var(--sand); vertical-align: middle; }
        .cart-table td:last-child { text-align: center; }
        .cart-table td:last-child .remove-btn { display: inline-flex; justify-content: center; }
        .cart-item-info { display: flex; align-items: center; gap: 20px; }
        .cart-item-img { width: 80px; height: 100px; background: var(--cream); object-fit: contain; }
        .cart-item-name { font-family: var(--serif); font-size: 18px; color: var(--dark); display: block; }
        .cart-item-tag { font-size: 10px; color: var(--brown); text-transform: uppercase; }
        .qty-input { width: 50px; padding: 8px; border: 1px solid var(--taupe); text-align: center; font-family: var(--sans); }
        .remove-btn { font-size: 10px; text-transform: uppercase; color: var(--taupe); cursor: pointer; border: none; background: none; transition: color 0.2s; }
        .remove-btn:hover { color: #cc0000; }
        .cart-summary { background: var(--cream); padding: 40px; display: flex; flex-direction: column; align-items: flex-end; gap: 16px; }
        .summary-row { display: flex; justify-content: space-between; width: 300px; font-size: 14px; }
        .total-row { font-family: var(--serif); font-size: 24px; color: var(--dark); margin-top: 10px; border-top: 1px solid var(--taupe); padding-top: 16px; }
    </style>
</head>
<body>

<div class="announcement">
  <div class="marquee-track" id="marquee">
    <span>Complimentary Shipping Over $100</span>
    <span>Discover Your Signature Scent</span>
    <span>Free Sample Vials With Every Order</span>
    <span>Artisan & Long-Lasting Fragrances</span>
    <span>Complimentary Shipping Over $100</span>
    <span>Discover Your Signature Scent</span>
    <span>Free Sample Vials With Every Order</span>
    <span>Artisan & Long-Lasting Fragrances</span>
  </div>
</div>

<header>
  </header>
  
<header>
  <div class="header-inner">
    <nav>
      <a href="index.php">Home</a>
      <a href="fragrances.php">Fragrances</a>
    </nav>
    <div class="logo">le parfum</div>
    <div class="header-actions">
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
        <a href="#" class="dropdown-item logout-link" onclick="return false;">Logout</a>
      </div>
    </div>
  </div>
</div>
  </div>
</header>

<main class="cart-container">
    <h2 class="cart-title">Shopping Cart</h2>
    
    <table class="cart-table">
        <thead>
            <tr>
                <th style="width: 40px;"></th> <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th style="text-align: right;">
                    <label for="selectAllItems" style="cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 11px; text-transform: uppercase; color: var(--brown);">
                        Select All <input type="checkbox" id="selectAllItems" checked onchange="toggleAllCartItems(this)">
                    </label>
                </th>
            </tr>
        </thead>
        <tbody id="cartItems">
            <!-- Cart items will be populated here -->
        </tbody>
    </table>

    <div class="cart-summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span id="subtotal">$0.00</span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span>Free Shipping</span>
        </div>
        <div class="summary-row total-row">
            <strong>Total</strong>
            <strong id="total">$0.00</strong>
        </div>
        <button class="btn-primary" id="checkoutBtn" style="width: 300px; margin-top: 20px;">Proceed to Checkout</button>
        <a href="fragrances.php" class="toggle-link" style="margin-top: 15px; width: 300px; text-align: center;">Continue Shopping</a>
    </div>
</main>

<footer>
  <div class="footer-bottom" style="justify-content: center;">
    <p style="text-align: center; color: black; font-size: 14px; width: 100%;">© 2026 Le Parfum. All rights reserved.</p>
</footer>

<button id="scrollToTopBtn" class="scroll-to-top" title="Go to top">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <line x1="12" y1="19" x2="12" y2="5"></line>
    <polyline points="5 12 12 5 19 12"></polyline>
  </svg>
</button>

<script src="script.js"></script>
</body>
</html>