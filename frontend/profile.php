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
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 24px;
            min-height: 60vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
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
        @media (max-width: 968px) {
            .profile-container {
                grid-template-columns: 1fr;
                gap: 24px;
            }
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
        <p class="profile-subtitle">Manage your personal information and preferences.</p>
        <div id="checkoutNotification" style="display:none; background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 16px; margin-bottom: 24px; color: #856404;">
            <strong>⚠️ Complete Your Payment Details</strong><br>
            Please fill in all shipping and payment details below to proceed with checkout.
        </div>
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
                <h3 class="form-section-title">Change Password</h3>
                <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" name="old_password" placeholder="Enter your current password">
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" placeholder="Enter your new password">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm your new password">
                </div>
            </div>
            <button type="submit" class="btn-primary save-btn">Save Profile</button>
        </form>
    </div>
    <div class="profile-box">
        <h2 class="profile-title">Shipping & Payment</h2>
        <p class="profile-subtitle">Update your shipping address and payment details so checkout is fast and seamless.</p>
        <form id="shippingForm">
            <div class="form-section">
                <h3 class="form-section-title">Shipping Address</h3>
                <div class="form-group">
                    <label>Shipping Address</label>
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
            <button type="submit" class="btn-primary save-btn">Save Shipping & Payment</button>
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

<script>
// Load user profile data
async function loadUserProfile() {
    try {
        const response = await fetch('../backend/user.php');
        
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = 'login.php';
                return;
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.user) {
            const user = data.user;
            
            // Safe element setter function
            const setInputValue = (selector, value) => {
                const element = document.querySelector(selector);
                if (element && value !== null && value !== undefined) {
                    element.value = value;
                }
            };
            
            // Populate profile form
            setInputValue('input[name="first_name"]', user.first_name);
            setInputValue('input[name="last_name"]', user.last_name);
            setInputValue('input[name="email"]', user.email);
            
            // Populate shipping form
            setInputValue('input[name="address"]', user.address);
            setInputValue('input[name="city"]', user.city);
            setInputValue('input[name="postal_code"]', user.postal_code);
            
            // Mask card number if it exists
            const cardInput = document.querySelector('input[name="card_number"]');
            if (cardInput && user.card_last4) {
                cardInput.value = '•••• •••• •••• ' + user.card_last4;
                cardInput.placeholder = 'Enter new card or leave blank to keep current';
            }
            
            setInputValue('input[name="expiry"]', user.card_expiry);
        } else if (data.message && data.message.includes('logged in')) {
            window.location.href = 'login.php';
        }
    } catch (error) {
        console.error('Error loading profile:', error);
        alert('Failed to load profile. Please try again.');
    }
}

// Initialize event listeners when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Load profile data
    loadUserProfile();
    
    // Handle profile form submission
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData();
            const firstNameEl = document.querySelector('input[name="first_name"]');
            const lastNameEl = document.querySelector('input[name="last_name"]');
            const oldPasswordEl = document.querySelector('input[name="old_password"]');
            const newPasswordEl = document.querySelector('input[name="new_password"]');
            const confirmPasswordEl = document.querySelector('input[name="confirm_password"]');
            
            if (firstNameEl) formData.append('first_name', firstNameEl.value);
            if (lastNameEl) formData.append('last_name', lastNameEl.value);
            if (oldPasswordEl) formData.append('old_password', oldPasswordEl.value);
            if (newPasswordEl) formData.append('new_password', newPasswordEl.value);
            if (confirmPasswordEl) formData.append('confirm_password', confirmPasswordEl.value);
            
            try {
                const response = await fetch('../backend/update_profile.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Profile updated successfully!');
                    // Clear password fields
                    if (oldPasswordEl) oldPasswordEl.value = '';
                    if (newPasswordEl) newPasswordEl.value = '';
                    if (confirmPasswordEl) confirmPasswordEl.value = '';
                } else {
                    alert('Error: ' + (data.message || 'Failed to update profile'));
                }
            } catch (error) {
                alert('Error updating profile: ' + error.message);
            }
        });
    }
    
    // Handle shipping form submission
    const shippingForm = document.getElementById('shippingForm');
    if (shippingForm) {
        shippingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData();
            const addressEl = document.querySelector('input[name="address"]');
            const cityEl = document.querySelector('input[name="city"]');
            const postalCodeEl = document.querySelector('input[name="postal_code"]');
            const cardNumberEl = document.querySelector('input[name="card_number"]');
            const expiryEl = document.querySelector('input[name="expiry"]');
            
            if (addressEl) formData.append('address', addressEl.value);
            if (cityEl) formData.append('city', cityEl.value);
            if (postalCodeEl) formData.append('postal_code', postalCodeEl.value);
            if (cardNumberEl) formData.append('card_number', cardNumberEl.value);
            if (expiryEl) formData.append('expiry', expiryEl.value);
            
            try {
                const response = await fetch('../backend/update_profile.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Shipping and payment information updated successfully!');
                } else {
                    alert('Error: ' + (data.message || 'Failed to update shipping information'));
                }
            } catch (error) {
                alert('Error updating shipping information: ' + error.message);
            }
        });
    }
});

// Show notification if redirected from checkout
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const redirectReason = urlParams.get('message');
    const tab = urlParams.get('tab');
    const notification = document.getElementById('checkoutNotification');
    
    if (redirectReason === 'incomplete_profile' && notification) {
        notification.style.display = 'block';
        // Scroll to the shipping section
        setTimeout(() => {
            const shippingForm = document.getElementById('shippingForm');
            if (shippingForm) {
                shippingForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 100);
    }
    
    // Also handle tab parameter if shipping tab is requested
    if (tab === 'shipping') {
        const shippingForm = document.getElementById('shippingForm');
        if (shippingForm) {
            setTimeout(() => {
                shippingForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }
});
</script>

