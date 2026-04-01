<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Story | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
    </nav>
    <div class="logo">le parfum </div>
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

<main style="padding: 96px 48px; max-width: 900px; margin: 0 auto; min-height: 60vh;">
    <div class="section-header" style="text-align: center; margin-bottom: 60px;">
        <span class="section-tag">The Visionary</span>
        <h1 class="section-title" style="font-size: clamp(42px, 5vw, 64px);">The Soul of <em>Le Parfum</em></h1>
    </div>

    <div class="story-content" style="font-family: var(--serif); font-size: 21px; color: var(--dark); font-weight: 300; line-height: 1.8; text-align: justify;">
        <p style="margin-bottom: 28px;">For us, the world is not merely experienced through colors or sounds, but through the invisible, intricate architecture of scent. From the very beginning, we are drawn to the subtle language of aroma—where others see a garden, we perceive a symphony of volatile molecules: the damp earth, the sharp green of crushed stems, and the intoxicating, fleeting breath of blooming petals.</p> 
        
        <p style="margin-bottom: 28px;">Perfume, to us, is not simply a cosmetic accessory. It is a vessel of memory, a way to capture the ephemeral beauty of the world and make it endure. We believe that a scent can preserve a moment in time far more vividly than a photograph ever could. Our purpose is not just to create something pleasant, but to capture the very soul of each ingredient.</p> 
        
        <p style="margin-bottom: 28px;">Driven by a shared and unwavering passion, we embrace the ancient arts of enfleurage and distillation. We strive to isolate the essence of nature while preserving its pure, original form. Every bottle crafted in the atelier of <em>Le Parfum</em> reflects this devotion: a harmonious blend of top, heart, and base notes designed to evoke emotion, awaken memories, and become a signature that lingers.</p> 
        
        <p style="text-align: center; font-style: italic; margin-top: 48px; font-size: 24px;">Welcome to our life's work. Welcome to the true essence of fragrance.</p>
    </div>
</main>

<footer>
  <div class="footer-bottom">
    <p>© 2026 Le Parfum. All rights reserved.</p>
  </div>
</footer>

<script src="script.js"></script>
</body>
</html>