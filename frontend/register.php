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
    <div></div>

    <div class="logo">le parfum</div>

    <div class="header-actions"></div>
  </div>
</header>

<main class="account-container">
    <div class="account-box">
        <h2 class="account-title">Create <em>Account</em></h2>
        <p style="font-size: 15px; color: black; margin-bottom: 32px; text-align: center;">Join Le Parfum and Be Part of Our Family.</p>
        <form action="#" method="POST">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" placeholder="Enter your first name">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" placeholder="Enter your last name">
            </div>
            <div class="form-group">
                <label>Contact No.</label>
                <input type="text" placeholder="Enter your contact number">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" placeholder="Create a password">
            </div>
            <div class="form-actions">
                <button type="button" class="btn-primary" style="width: 100%;" onclick="window.location.href='index.php'">Sign Up</button>
            </div>
        </form>
    </div>
</main>

<footer>
  <div class="footer-bottom" style="justify-content: center;">
    <p style="text-align: center; color: black; font-size: 14px; width: 100%;">© 2026 Le Parfum. All rights reserved.</p>
  </div>
</footer>

</body>
</html>