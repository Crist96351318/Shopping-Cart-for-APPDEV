<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            max-width: 720px;
            margin: 60px auto;
            padding: 0 24px;
            min-height: 60vh;
        }
        .profile-box {
            background: var(--cream);
            padding: 40px;
            border: 1px solid var(--sand);
        }
        .profile-title {
            font-family: var(--serif);
            font-size: 34px;
            font-weight: 300;
            margin-bottom: 12px;
        }
        .profile-subtitle {
            margin-bottom: 32px;
            color: var(--text);
            line-height: 1.6;
        }
        .form-section {
            margin-bottom: 32px;
        }
        .form-section-title {
            font-family: var(--serif);
            font-size: 20px;
            margin-bottom: 18px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 11px;
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
        }
        .profile-note {
            margin-bottom: 24px;
            color: var(--text);
            font-size: 14px;
            line-height: 1.7;
        }
        .save-btn {
            width: 100%;
            margin-top: 12px;
        }
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
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

<main class="profile-container">
    <div class="profile-box">
        <h2 class="profile-title">Profile</h2>
        <p class="profile-subtitle">Update your shipping address and payment details so checkout is fast and seamless.</p>

        <form id="profileForm">
            <div class="form-section">
                <h3 class="form-section-title">Personal Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" readonly>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Shipping Address</h3>
                <div class="form-group">
                    <label>Street Address</label>
                    <input type="text" name="address" placeholder="123 Main Street" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" required>
                    </div>
                    <div class="form-group">
                        <label>Postal Code</label>
                        <input type="text" name="postal_code" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Payment Information</h3>
                <div class="form-group">
                    <label>Card Number</label>
                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <input type="text" name="expiry" placeholder="MM/YY">
                    </div>
                </div>
                <p class="profile-note">Only the last 4 digits of your card are stored here for checkout convenience.</p>
            </div>

            <button type="submit" class="btn-primary save-btn">Save Profile</button>
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
