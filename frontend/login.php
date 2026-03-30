<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Le Parfum</title>
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
        <h2 class="account-title">Log<em>in</em></h2>
        <p style="font-size: 15px; color: black; margin-bottom: 32px; text-align: center;">ENTER YOUR DETAILS </p>
        <form action="#" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" id="passwordInput" placeholder="Enter your password">
                    <span class="toggle-password" onclick="togglePassword()">
                        <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-primary" style="width: 100%;">Sign In</button>
            </div>
            <div style="margin-top: 16px; text-align: center;">
                <a href="#" style="font-size: 11px; color: var(--brown); text-decoration: underline;">Forgot Password?</a>
            </div>
        </form>
        <a href="register.php" class="toggle-link">Don't have an account? Create one</a>
    </div>
</main>

<footer>
  <div class="footer-bottom" style="justify-content: center;">
    <p style="text-align: center; color: black; font-size: 14px; width: 100%;">© 2026 Le Parfum. All rights reserved.</p>
  </div>
</footer>


<script>
    function togglePassword() {
        const passInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passInput.type === 'password') {
            passInput.type = 'text';
            // Switches to a slashed 'eye-off' icon
            eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
        } else {
            passInput.type = 'password';
            // Switches back to normal eye icon
            eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
        }
    }
</script>

</body>
</html>