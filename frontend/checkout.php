<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .checkout-container { max-width: 1200px; margin: 60px auto; padding: 0 24px; min-height: 60vh; }
        .checkout-title { font-family: var(--serif); font-size: 42px; font-weight: 300; margin-bottom: 40px; text-align: center; }
        .checkout-grid { display: grid; grid-template-columns: 1fr 400px; gap: 40px; }
        .checkout-form { background: var(--cream); padding: 40px; border: 1px solid var(--sand); }
        .form-section { margin-bottom: 32px; }
        .form-section-title { font-family: var(--serif); font-size: 24px; margin-bottom: 16px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 10.5px; letter-spacing: 0.15em; text-transform: uppercase; color: var(--brown); margin-bottom: 8px; }
        .form-group input { width: 100%; padding: 12px 16px; background: var(--warm-white); border: 1px solid var(--taupe); font-family: var(--sans); }
        .checkout-summary { background: var(--cream); padding: 40px; border: 1px solid var(--sand); height: fit-content; }
        .summary-title { font-family: var(--serif); font-size: 24px; margin-bottom: 24px; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .summary-total { font-family: var(--serif); font-size: 18px; border-top: 1px solid var(--taupe); padding-top: 16px; margin-top: 16px; }
        .checkout-btn { width: 100%; margin-top: 24px; }
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

<main class="checkout-container">
    <h2 class="checkout-title">Checkout</h2>
    
    <div class="checkout-grid">
        <div class="checkout-form">
            <div class="profile-note" style="margin-bottom: 32px; font-size: 14px; color: var(--text); line-height: 1.6;">
                Your shipping and payment details are now saved on your <a href="profile.php">Profile</a>. Please update your profile before placing an order.
            </div>
            <form id="checkoutForm">
                <button type="submit" class="btn-primary checkout-btn">Place Order</button>
            </form>
        </div>
        
        <div class="checkout-summary">
            <h3 class="summary-title">Order Summary</h3>
            <div id="orderItems">
                <!-- Order items will be loaded here -->
            </div>
            <div class="summary-total">
                <strong>Total: <span id="orderTotal">$0.00</span></strong>
            </div>
        </div>
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