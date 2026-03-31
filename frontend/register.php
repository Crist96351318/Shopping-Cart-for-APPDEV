<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .account-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 0 24px;
            min-height: 50vh;
        }
        .account-box {
            background: var(--cream);
            padding: 48px;
            border: 1px solid var(--sand);
        }
        .account-title {
            font-family: var(--serif);
            font-size: 32px;
            color: var(--dark);
            font-weight: 300;
            margin-bottom: 24px;
            text-align: center;
        }
        .account-title em { font-style: italic; }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            font-size: 10.5px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--brown);
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: var(--warm-white);
            border: 1px solid var(--taupe);
            font-family: var(--sans);
            font-size: 13px;
            color: var(--text);
            outline: none;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: var(--dark);
        }
        .form-actions {
            margin-top: 32px;
        }
        .toggle-link {
            display: block;
            text-align: center;
            margin-top: 28px;
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--brown);
            cursor: pointer;
            transition: color 0.2s;
            text-decoration: none;
        }
        .toggle-link:hover {
            color: var(--dark);
        }
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
  <div class="header-inner">
    <nav>
      <a href="index.php">Home</a>
      <a href="fragrances.php">Fragrances</a>
      <a href="collections.php">Collections</a>
      <a href="gifts.php">Gifts</a>
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
        <a href="#" class="dropdown-item logout-link" onclick="return false;">Logout</a>
      </div>
    </div>
  </div>
</div>
  </div>
</header>

<main class="account-container">
    <div class="account-box">
        <h2 class="account-title">Create <em>Account</em></h2>
        <p style="font-size: 15px; color: black; margin-bottom: 32px; text-align: center;">Join Le Parfum and Be Part of Our Family.</p>
        <form id="registerForm">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label>Contact No.</label>
                <input type="text" name="contact_no" placeholder="Enter your contact number">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary" style="width: 100%;">Sign Up</button>
            </div>
        </form>
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