<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .history-container { max-width: 1000px; margin: 60px auto; padding: 0 24px; min-height: 60vh; }
        .history-title { font-family: var(--serif); font-size: 42px; font-weight: 300; margin-bottom: 40px; text-align: center; }
        .orders-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .orders-table th { text-align: left; padding: 20px; font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; color: var(--brown); border-bottom: 1px solid var(--sand); }
        .orders-table td { padding: 24px 20px; border-bottom: 1px solid var(--sand); vertical-align: middle; }
        .order-id { font-family: var(--serif); font-weight: 500; color: var(--dark); }
        .order-date { color: var(--text); }
        .order-status { padding: 4px 12px; border-radius: 12px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .order-total { font-family: var(--serif); font-weight: 500; color: var(--dark); }
        .view-details { font-size: 11px; text-transform: uppercase; color: var(--brown); cursor: pointer; border: none; background: none; text-decoration: underline; }
        .no-orders { text-align: center; padding: 60px; color: var(--text); }
        
        /* Modal Styles */
        .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; }
        .modal-content { background: var(--warm-white); border: 1px solid var(--sand); max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; border-radius: 8px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid var(--sand); }
        .modal-header h3 { font-family: var(--serif); font-size: 24px; margin: 0; color: var(--dark); }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text); }
        .modal-body { padding: 20px; }
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

<main class="history-container">
    <h2 class="history-title">Order History</h2>
    
    <table class="orders-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="ordersTable">
            <!-- Orders will be loaded here -->
        </tbody>
    </table>
</main>

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Order Details</h3>
            <button class="modal-close" onclick="closeOrderDetailsModal()">&times;</button>
        </div>
        <div id="orderDetailsContent">
            <!-- Order details will be loaded here -->
        </div>
    </div>
</div>

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