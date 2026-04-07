// API Configuration
// Use relative path from frontend to backend. Adjust as needed for your server setup.
const API_BASE = '../backend/';

// Utility Functions
function showMessage(message, type = 'info') {
    // Simple alert for now; can be enhanced with toast notifications
    alert(message);
}

function setLocalUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
    localStorage.setItem('isLoggedIn', 'true');
    localStorage.setItem('isAdmin', user.is_admin ? 'true' : 'false');
}

function getLocalUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

function clearLocalUser() {
    localStorage.removeItem('user');
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('isAdmin');
}

function isLoggedIn() {
    return localStorage.getItem('isLoggedIn') === 'true';
}

// API Functions
async function apiRequest(endpoint, options = {}) {
    const url = API_BASE + endpoint;
    const config = {
        headers: {
            'Content-Type': 'application/json',
        },
        ...options
    };

    try {
        const response = await fetch(url, { ...config, credentials: 'same-origin' });
        const text = await response.text();
        let data;

        try {
            data = text ? JSON.parse(text) : {};
        } catch (err) {
            throw new Error('Invalid JSON from API: ' + text.slice(0, 300));
        }

        if (!response.ok) {
            const serverMessage = data && data.message ? data.message : null;
            const errorText = serverMessage ? `${serverMessage} (${response.status})` : `API request failed (${response.status})`;
            throw new Error(errorText);
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

async function login(email, password) {
    return await apiRequest('login.php', {
        method: 'POST',
        body: JSON.stringify({ email, password })
    });
}

async function register(userData) {
    return await apiRequest('registration.php', {
        method: 'POST',
        body: JSON.stringify(userData)
    });
}

async function getUser() {
    return await apiRequest('user.php');
}

async function logout() {
    const result = await apiRequest('logout.php');
    clearLocalUser();
    return result;
}

async function addToCart(productId, quantity = 1) {
    return await apiRequest('add_to_cart.php', {
        method: 'POST',
        body: JSON.stringify({ product_id: productId, quantity })
    });
}

async function getCart() {
    return await apiRequest('cart.php');
}

async function updateCartItem(productId, quantity) {
    return await apiRequest('cart.php', {
        method: 'PUT',
        body: JSON.stringify({ product_id: productId, quantity })
    });
}

async function removeCartItem(productId) {
    return await apiRequest('cart.php', {
        method: 'DELETE',
        body: JSON.stringify({ product_id: productId })
    });
}

async function getProducts() {
    return await apiRequest('get_products.php');
}

async function checkout(selectedIds = []) {
    return await apiRequest('checkout.php', {
        method: 'POST',
        body: JSON.stringify({ selected_product_ids: selectedIds })
    });
}

async function updateProfile(profileData) {
    return await apiRequest('update_profile.php', {
        method: 'POST',
        body: JSON.stringify(profileData)
    });
}

async function getOrderHistory() {
    return await apiRequest('order_history.php');
}

async function getOrderConfirmation(orderId) {
    return await apiRequest(`order_confirmation.php?order_id=${orderId}`);
}

async function handleAddToCart(productId) {
    try {
        const data = await addToCart(productId);
        updateCartCount(data.cart.count);
        showMessage('Product added to cart!');
    } catch (error) {
        showMessage('Failed to add to cart: ' + error.message);
    }
}

async function loadCart() {
    try {
        const data = await getCart();
        renderCart(data.cart);
    } catch (error) {
        showMessage('Failed to load cart: ' + error.message);
    }
}

function renderCart(cart) {
    const tbody = document.getElementById('cartItems');
    const subtotalEl = document.getElementById('subtotal');
    const totalEl = document.getElementById('total');
    
    if (!tbody) return; // Not on cart page
    
    tbody.innerHTML = '';
    
    if (!cart.items || cart.items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">Your cart is empty</td></tr>';
        if (subtotalEl) subtotalEl.textContent = '$0.00';
        if (totalEl) totalEl.textContent = '$0.00';
        return;
    }
    
    const selectedIds = getSelectedCheckoutIds();
    cart.items.forEach(item => {
        const row = document.createElement('tr');
        const isChecked = selectedIds.includes(String(item.product_id));
        row.innerHTML = `
            <td>
                <input type="checkbox" class="cart-item-select" data-product-id="${item.product_id}" ${isChecked ? 'checked' : ''} />
            </td>
            <td>
                <div class="cart-item-info">
                    <img src="${item.image_path || '../assets/placeholder.png'}" class="cart-item-img" alt="${item.name}">
                    <div>
                        <span class="cart-item-tag">${item.category || 'Product'}</span>
                        <span class="cart-item-name">${item.name}</span>
                    </div>
                </div>
            </td>
            <td><input type="number" class="qty-input" value="${item.quantity}" min="1" onchange="updateQuantity(${item.product_id}, this.value)"></td>
            <td>$${item.price.toFixed(2)}</td>
            <td>$${(item.price * item.quantity).toFixed(2)}</td>
            <td><button class="remove-btn" onclick="removeItem(${item.product_id})">Remove</button></td>
        `;
        tbody.appendChild(row);
    });
    
    if (subtotalEl) subtotalEl.textContent = `$${cart.total.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `$${cart.total.toFixed(2)}`;

    attachCartCheckboxListeners();
}

function getSelectedCheckoutIds() {
    const stored = localStorage.getItem('selectedCheckoutItems');
    return stored ? JSON.parse(stored) : [];
}

function setSelectedCheckoutIds(ids) {
    localStorage.setItem('selectedCheckoutItems', JSON.stringify(ids));
}

function attachCartCheckboxListeners() {
    const checkboxes = document.querySelectorAll('.cart-item-select');
    const masterCheckbox = document.getElementById('selectAllItems');
    const selectedIds = getSelectedCheckoutIds();

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const productId = checkbox.dataset.productId;
            let selected = getSelectedCheckoutIds();
            if (checkbox.checked) {
                if (!selected.includes(productId)) selected.push(productId);
            } else {
                selected = selected.filter(id => id !== productId);
            }
            setSelectedCheckoutIds(selected);
            if (masterCheckbox) {
                masterCheckbox.checked = selected.length === checkboxes.length;
            }
        });
    });

    if (masterCheckbox) {
        masterCheckbox.checked = checkboxes.length > 0 && selectedIds.length === checkboxes.length;
    }
}

function toggleAllCartItems(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.cart-item-select');
    const selectedIds = [];
    checkboxes.forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
        if (masterCheckbox.checked) {
            selectedIds.push(checkbox.dataset.productId);
        }
    });
    setSelectedCheckoutIds(selectedIds);
}

async function updateQuantity(productId, quantity) {
    try {
        const data = await updateCartItem(productId, parseInt(quantity));
        renderCart(data.cart);
        updateCartCount(data.cart.count);
    } catch (error) {
        showMessage('Failed to update quantity: ' + error.message);
        loadCart(); // Reload to revert
    }
}

async function removeItem(productId) {
    try {
        const data = await removeCartItem(productId);
        renderCart(data.cart);
        updateCartCount(data.cart.count);
        showMessage('Item removed from cart');
    } catch (error) {
        showMessage('Failed to remove item: ' + error.message);
    }
}

async function loadProducts() {
    try {
        const data = await getProducts();
        renderProducts(data.products);
    } catch (error) {
        showMessage('Failed to load products: ' + error.message);
    }
}

function renderProducts(products) {
    const grid = document.getElementById('productsGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    if (!products || products.length === 0) {
        grid.innerHTML = '<p style="text-align: center; padding: 40px;">No products available</p>';
        return;
    }
    
    products.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        const outOfStock = (product.stock_quantity === 0 || product.stock_quantity === '0' || product.stock_quantity === null || product.stock_quantity === undefined);
        const stockBadge = outOfStock ? '<span class="stock-badge" style="color:#cc3333; font-weight:700; margin-right:8px;">Out of stock</span>' : `<span class="stock-badge" style="color:#2d7f2d; font-weight:700; margin-right:8px;">${product.stock_quantity} in stock</span>`;

        card.innerHTML = `
            <div class="product-img-wrap">
                <div class="product-img" style="background-image: url('${product.image_path || '../assets/placeholder.png'}')"></div>
                <div class="product-actions">
                    <button>Wishlist</button>
                    <button class="add-cart" ${outOfStock ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : ''} onclick="handleAddToCart(${product.product_id})">${outOfStock ? 'Unavailable' : 'Add to Cart'}</button>
                </div>
            </div>
            <div class="product-tag">${product.category_name || 'Fragrance'}</div>
            <div class="product-name">${product.name}</div>
            <div class="product-price">$${product.price.toFixed(2)}</div>
            <div>${stockBadge}</div>
        `;
        grid.appendChild(card);
    });
}

async function loadCheckoutSummary() {
    try {
        const data = await getCart();
        renderCheckoutSummary(data.cart);
    } catch (error) {
        showMessage('Failed to load checkout summary: ' + error.message);
    }
}

function renderCheckoutSummary(cart) {
    const itemsEl = document.getElementById('orderItems');
    const totalEl = document.getElementById('orderTotal');
    
    if (!itemsEl) return;
    
    itemsEl.innerHTML = '';
    
    if (!cart.items || cart.items.length === 0) {
        itemsEl.innerHTML = '<p>Your cart is empty</p>';
        if (totalEl) totalEl.textContent = '$0.00';
        return;
    }
    
    cart.items.forEach(item => {
        const itemEl = document.createElement('div');
        itemEl.className = 'summary-item';
        itemEl.innerHTML = `
            <span>${item.name} (x${item.quantity})</span>
            <span>$${(item.price * item.quantity).toFixed(2)}</span>
        `;
        itemsEl.appendChild(itemEl);
    });
    
    if (totalEl) totalEl.textContent = `$${cart.total.toFixed(2)}`;
}

async function handleCheckout() {
    try {
        const userData = await getUser();
        const user = userData.user || {};

        const hasAddress = user.address && typeof user.address === 'string' && user.address.trim().length > 0;
        const hasCity = user.city && typeof user.city === 'string' && user.city.trim().length > 0;
        const hasPostalCode = user.postal_code && typeof user.postal_code === 'string' && user.postal_code.trim().length > 0;

        if (!hasAddress || !hasCity || !hasPostalCode) {
            window.location.href = 'profile.php?tab=shipping&message=incomplete_profile';
            return;
        }

        const data = await checkout();
        showMessage('Order placed successfully!');
        window.location.href = `order_confirmation.php?order_id=${data.order_id}`;
    } catch (error) {
        if (error.message && (error.message.includes('complete your Profile') || error.message.includes('address') || error.message.includes('payment'))) {
            window.location.href = 'profile.php?tab=shipping&message=incomplete_profile';
        } else if (error.message && error.message.includes('not logged in')) {
            window.location.href = 'login.php';
        } else {
            showMessage('Checkout failed: ' + error.message);
        }
    }
}

async function loadProfilePage() {
    const profileForm = document.getElementById('profileForm');
    const shippingForm = document.getElementById('shippingForm');
    if (!profileForm && !shippingForm) return;

    try {
        const data = await getUser();
        const user = data.user || {};

        const setFieldValue = (form, name, value) => {
            if (!form || !form.elements) return;
            const field = form.elements[name];
            if (field) field.value = value;
        };

        setFieldValue(profileForm, 'first_name', user.first_name || '');
        setFieldValue(profileForm, 'last_name', user.last_name || '');
        setFieldValue(profileForm, 'email', user.email || '');
        setFieldValue(profileForm, 'card_number', user.card_last4 ? `**** **** **** ${user.card_last4}` : '');
        setFieldValue(profileForm, 'expiry', user.card_expiry || '');

        setFieldValue(shippingForm, 'address', user.address || '');
        setFieldValue(shippingForm, 'city', user.city || '');
        setFieldValue(shippingForm, 'postal_code', user.postal_code || '');
    } catch (error) {
        showMessage('Failed to load profile: ' + error.message);
    }
}

async function handleProfileSave(event) {
    event.preventDefault();
    const form = event.target;
    const cardNumberRaw = form.elements['card_number'] ? form.elements['card_number'].value || '' : '';
    const cardDigits = cardNumberRaw.replace(/\D/g, '');
    const profileData = {
        first_name: form.elements['first_name'] ? form.elements['first_name'].value : '',
        last_name: form.elements['last_name'] ? form.elements['last_name'].value : '',
        old_password: form.elements['old_password'] ? form.elements['old_password'].value : '',
        new_password: form.elements['new_password'] ? form.elements['new_password'].value : '',
        confirm_password: form.elements['confirm_password'] ? form.elements['confirm_password'].value : ''
    };

    if (cardDigits) {
        profileData.card_number = cardDigits;
    }

    const expiryValue = form.elements['expiry'] ? form.elements['expiry'].value.trim() : '';
    if (expiryValue) {
        profileData.expiry = expiryValue;
    }

    try {
        const data = await updateProfile(profileData);
        if (data && data.user) {
            setLocalUser(data.user);
            // Reload the forms with updated data
            await loadProfilePage();
        }
        showMessage('Profile updated successfully!');
    } catch (error) {
        showMessage('Profile update failed: ' + error.message);
    }
}

async function handleCheckoutSubmit(event) {
    event.preventDefault();
    
    try {
        // First, check if user is logged in and has complete payment details
        const userData = await getUser();
        const user = userData.user || {};
        
        // Validate that user is logged in
        if (!user || !user.customer_id) {
            window.location.href = 'login.php';
            return;
        }
        
        // Check if all required fields are present for checkout
        // Use strict checking - field must exist and have non-empty value after trim
        const hasAddress = user.address && typeof user.address === 'string' && user.address.trim().length > 0;
        const hasCity = user.city && typeof user.city === 'string' && user.city.trim().length > 0;
        const hasPostalCode = user.postal_code && typeof user.postal_code === 'string' && user.postal_code.trim().length > 0;
        const hasCardNumber = user.card_last4 && typeof user.card_last4 === 'string' && user.card_last4.trim().length > 0;
        const hasExpiry = user.card_expiry && typeof user.card_expiry === 'string' && user.card_expiry.trim().length > 0;
        
        const hasCompleteDetails = hasAddress && hasCity && hasPostalCode && hasCardNumber && hasExpiry;
        
        // If details incomplete, redirect to profile WITHOUT showing error message
        if (!hasCompleteDetails) {
            localStorage.setItem('checkoutRedirectReason', 'incomplete');
            window.location.href = 'profile.php?tab=shipping&message=incomplete_profile';
            return;
        }
        
        // Payment details are complete, proceed with checkout
        const selectedIdsStr = localStorage.getItem('selectedCheckoutItems');
        let selectedIds = [];
        if (selectedIdsStr) {
            selectedIds = JSON.parse(selectedIdsStr);
        }
        
        const data = await checkout(selectedIds);
        showMessage('Order placed successfully!');
        localStorage.removeItem('selectedCheckoutItems');
        window.location.href = `order_confirmation.php?order_id=${data.order_id}`;
    } catch (error) {
        // Check if error is due to incomplete profile (API-level check)
        if (error.message && (error.message.includes('complete your Profile') || error.message.includes('address') || error.message.includes('payment'))) {
            localStorage.setItem('checkoutRedirectReason', 'incomplete');
            window.location.href = 'profile.php?tab=shipping&message=incomplete_profile';
        } else if (error.message && error.message.includes('not logged in')) {
            window.location.href = 'login.php';
        } else {
            showMessage('Checkout failed: ' + error.message);
        }
    }
}

async function loadOrderHistory() {
    try {
        const data = await getOrderHistory();
        renderOrderHistory(data.orders);
    } catch (error) {
        showMessage('Failed to load order history: ' + error.message);
    }
}

function renderOrderHistory(orders) {
    const tbody = document.getElementById('ordersTable');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (!orders || orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="no-orders">No orders found</td></tr>';
        return;
    }
    
    orders.forEach(order => {
        const row = document.createElement('tr');
        const statusClass = `status-${order.status.toLowerCase()}`;
        row.innerHTML = `
            <td><span class="order-id">#${order.order_id}</span></td>
            <td><span class="order-date">${new Date(order.order_date).toLocaleDateString()}</span></td>
            <td><span class="order-status ${statusClass}">${order.status}</span></td>
            <td><span class="order-total">$${order.total_amount.toFixed(2)}</span></td>
            <td><button class="view-details" onclick="viewOrderDetails(${order.order_id})">View Details</button></td>
        `;
        tbody.appendChild(row);
    });
}

async function viewOrderDetails(orderId) {
    try {
        const data = await getOrderConfirmation(orderId);
        renderOrderDetailsModal(data.order);
        document.getElementById('orderDetailsModal').style.display = 'flex';
    } catch (error) {
        showMessage('Failed to load order details: ' + error.message);
    }
}

function closeOrderDetailsModal() {
    document.getElementById('orderDetailsModal').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('orderDetailsModal');
    if (modal && event.target === modal) {
        closeOrderDetailsModal();
    }
});

function renderOrderDetailsModal(order) {
    const contentEl = document.getElementById('orderDetailsContent');
    if (!contentEl) return;
    
    const orderDate = new Date(order.order_date).toLocaleDateString();
    
    contentEl.innerHTML = `
        <div class="modal-body">
            <div class="order-info">
                <div class="info-group">
                    <div class="info-label">Order Number</div>
                    <div class="info-value">#${order.order_id}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Order Date</div>
                    <div class="info-value">${orderDate}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Status</div>
                    <div class="info-value">${order.status}</div>
                </div>
                <div class="info-group">
                    <div class="info-label">Total Amount</div>
                    <div class="info-value">$${order.total_amount.toFixed(2)}</div>
                </div>
            </div>
            
            <div class="order-items">
                <h3 class="items-title">Order Items</h3>
                ${order.items.map(item => `
                    <div class="item-row">
                        <div>
                            <div class="item-name">${item.name}</div>
                            <div class="item-details">Quantity: ${item.quantity}</div>
                        </div>
                        <div class="item-price">$${(item.price_at_purchase * item.quantity).toFixed(2)}</div>
                    </div>
                `).join('')}
                <div class="order-total">
                    <strong>Total: $${order.total_amount.toFixed(2)}</strong>
                </div>
            </div>
        </div>
    `;
}

async function loadOrderConfirmation() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    
    if (!orderId) {
        showMessage('No order ID provided');
        return;
    }
    
    try {
        const data = await getOrderConfirmation(orderId);
        renderOrderConfirmation(data.order);
    } catch (error) {
        showMessage('Failed to load order details: ' + error.message);
    }
}

function renderOrderConfirmation(order) {
    const detailsEl = document.getElementById('orderDetails');
    if (!detailsEl) return;
    
    const orderDate = new Date(order.order_date).toLocaleDateString();
    
    detailsEl.innerHTML = `
        <div class="order-info">
            <div class="info-group">
                <div class="info-label">Order Number</div>
                <div class="info-value">#${order.order_id}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Order Date</div>
                <div class="info-value">${orderDate}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Status</div>
                <div class="info-value">${order.status}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Total Amount</div>
                <div class="info-value">$${order.total_amount.toFixed(2)}</div>
            </div>
        </div>
        
        <div class="order-items">
            <h3 class="items-title">Order Items</h3>
            ${order.items.map(item => `
                <div class="item-row">
                    <div>
                        <div class="item-name">${item.name}</div>
                        <div class="item-details">Quantity: ${item.quantity}</div>
                    </div>
                    <div class="item-price">$${(item.price_at_purchase * item.quantity).toFixed(2)}</div>
                </div>
            `).join('')}
            <div class="order-total">
                <strong>Total: $${order.total_amount.toFixed(2)}</strong>
            </div>
        </div>
    `;
}

// UI Update Functions
function updateAccountDropdown(user) {
    const dropdownName = document.querySelector('.dropdown-name');
    const cartBtn = document.querySelector('.cart-btn');
    const notificationWrapper = document.getElementById('notificationWrapper'); // ADDED
    const loginLink = document.querySelector('.dropdown-item[href="login.php"]');
    const registerLink = document.querySelector('.dropdown-item[href="register.php"]');
    const divider = document.querySelector('.dropdown-divider');
    const logoutLink = document.querySelector('.dropdown-item.logout-link');

    if (dropdownName) {
        dropdownName.textContent = user ? `${user.first_name} ${user.last_name}` : 'Guest';
    }

    if (cartBtn) {
        cartBtn.style.display = user ? 'inline-flex' : 'none';
    }
    
    // ADDED: Toggle notification bell visibility exactly like the cart button
    if (notificationWrapper) {
        notificationWrapper.style.display = user ? 'inline-flex' : 'none';
    }

    if (user) {
        // Logged in: show Edit Profile, Order History, Change Theme, Logout
        if (loginLink) loginLink.style.display = 'none';
        if (registerLink) registerLink.style.display = 'none';
        if (divider) divider.style.display = 'block';
        if (logoutLink) {
            logoutLink.style.display = 'block';
            logoutLink.textContent = 'Logout';
            logoutLink.setAttribute('href', '#');
            logoutLink.classList.add('logout-link');
            logoutLink.onclick = async (event) => {
                event.preventDefault();
                await handleLogout();
            };
            logoutLink.style.cursor = 'pointer';
        }

        // Add additional logged-in options if they don't exist
        let dropdownLinks = document.querySelector('.dropdown-links');
        if (dropdownLinks) {
            // Remove existing dynamic items
            const existingItems = dropdownLinks.querySelectorAll('.dynamic-item');
            existingItems.forEach(item => item.remove());

            // Add Profile
            const profileLink = document.createElement('a');
            profileLink.href = 'profile.php';
            profileLink.className = 'dropdown-item dynamic-item';
            profileLink.textContent = 'Profile';
            dropdownLinks.insertBefore(profileLink, divider);

            // Add Order History
            const orderHistory = document.createElement('a');
            orderHistory.href = 'order_history.php';
            orderHistory.className = 'dropdown-item dynamic-item';
            orderHistory.textContent = 'Order History';
            dropdownLinks.insertBefore(orderHistory, divider);

            // Add Theme Toggle
            const themeToggle = document.createElement('a');
            themeToggle.href = '#';
            themeToggle.className = 'dropdown-item dynamic-item';
            const isDark = document.documentElement.classList.contains('dark-theme');
            themeToggle.textContent = isDark ? 'Toggle Light Theme' : 'Toggle Dark Theme';
            themeToggle.onclick = (e) => {
                e.preventDefault();
                toggleDarkTheme();
            };
            dropdownLinks.insertBefore(themeToggle, divider);
        }
    } else {
        // Guest: show Login, Register, hide other items
        if (loginLink) loginLink.style.display = 'block';
        if (registerLink) registerLink.style.display = 'block';
        if (divider) divider.style.display = 'block';
        if (logoutLink) logoutLink.style.display = 'none';

        // Remove dynamic items
        const dynamicItems = document.querySelectorAll('.dynamic-item');
        dynamicItems.forEach(item => item.remove());
    }
}

function toggleDarkTheme() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark-theme');
    const themeToggle = document.querySelector('.dropdown-item[onclick*="toggleDarkTheme"]');
    
    if (isDark) {
        html.classList.remove('dark-theme');
        localStorage.setItem('theme', 'light');
        if (themeToggle) themeToggle.textContent = 'Toggle Dark Theme';
        showMessage('Switched to light theme');
    } else {
        html.classList.add('dark-theme');
        localStorage.setItem('theme', 'dark');
        if (themeToggle) themeToggle.textContent = 'Toggle Light Theme';
        showMessage('Switched to dark theme');
    }
}

function loadTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-theme');
    }
}

function updateCartCount(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count || 0;
    }
}

let notificationInterval; // ADDED

async function checkSession() {
    if (isLoggedIn()) {
        try {
            const data = await getUser();
            setLocalUser(data.user);
            updateAccountDropdown(data.user);
            
            // Also update cart count
            const cartData = await getCart();
            updateCartCount(cartData.cart.count);
            
            // ADDED: Initialize Notifications
            loadNotifications();
            if (notificationInterval) clearInterval(notificationInterval);
            notificationInterval = setInterval(loadNotifications, 30000); // Check for new updates every 30 seconds
            
        } catch (error) {
            // Session invalid, clear local state
            clearLocalUser();
            updateAccountDropdown(null);
            updateCartCount(0);
            if (notificationInterval) clearInterval(notificationInterval); // ADDED
        }
    } else {
        updateAccountDropdown(null);
        updateCartCount(0);
        if (notificationInterval) clearInterval(notificationInterval); // ADDED
    }
}

async function handleLogout(event) {
    if (event) {
        event.preventDefault();
    }
    try {
        await logout();
        showMessage('Logged out successfully');
        window.location.href = 'index.php';
    } catch (error) {
        showMessage('Logout failed: ' + error.message);
    }
}

// Form Handlers
async function handleLogin(event) {
    event.preventDefault();
    const form = event.target;
    const email = form.email.value;
    const password = form.password.value;

    try {
        const data = await login(email, password);
        setLocalUser(data.user);
        showMessage('Login successful!');
        
        // Redirect to admin dashboard if user is admin
        if (data.user.is_admin) {
            window.location.href = 'admin.php';
        } else {
            window.location.href = 'index.php';
        }
    } catch (error) {
        showMessage('Login failed: ' + error.message);
    }
}

async function handleRegister(event) {
    event.preventDefault();
    const form = event.target;
    const userData = {
        first_name: form.first_name.value,
        last_name: form.last_name.value,
        email: form.email.value,
        password: form.password.value,
        address: form.contact_no ? form.contact_no.value : '' // Optional
    };

    try {
        const data = await register(userData);
        setLocalUser(data.user);
        showMessage('Registration successful!');
        window.location.href = 'index.php';
    } catch (error) {
        showMessage('Registration failed: ' + error.message);
    }
}

// Password Toggle
function togglePassword() {
    const passInput = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passInput.type === 'password') {
        passInput.type = 'text';
        eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
    } else {
        passInput.type = 'password';
        eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    }
}

// Back to Top Button Logic
document.addEventListener("DOMContentLoaded", () => {
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");

    if (scrollToTopBtn) {
        // Show or hide the button based on scroll position
        window.addEventListener("scroll", () => {
            if (window.scrollY > 400) {
                scrollToTopBtn.classList.add("show");
            } else {
                scrollToTopBtn.classList.remove("show");
            }
        });

        // Smooth scroll to top when clicked
        scrollToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }
});

// Account Dropdown Logic
document.addEventListener("DOMContentLoaded", () => {
    const accountBtn = document.getElementById("accountBtn");
    const accountDropdown = document.getElementById("accountDropdown");

    if (accountBtn && accountDropdown) {
        // Toggle dropdown when the Account button is clicked
        accountBtn.addEventListener("click", (e) => {
            e.stopPropagation(); // Stops the click from immediately closing the menu
            accountDropdown.classList.toggle("show");
        });

        // Close dropdown if user clicks anywhere else on the page
        document.addEventListener("click", (e) => {
            if (!accountDropdown.contains(e.target) && e.target !== accountBtn) {
                accountDropdown.classList.remove("show");
            }
        });
    }
});

// Initialize on page load
document.addEventListener("DOMContentLoaded", () => {
    loadTheme();
    checkSession();

    // Attach form handlers if forms exist
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
    
    // Load cart if on cart page
    if (document.getElementById('cartItems')) {
        loadCart();
    }
    
    // Load products if on product pages
    if (document.getElementById('productsGrid')) {
        loadProducts();
    }
    
    // Load checkout summary if on checkout page
    if (document.getElementById('orderItems')) {
        loadCheckoutSummary();
    }
    
    // Load order history if on history page
    if (document.getElementById('ordersTable')) {
        loadOrderHistory();
    }
    
    // Load order confirmation if on confirmation page
    if (document.getElementById('orderDetails')) {
        loadOrderConfirmation();
    }
    
    // Attach checkout handler
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', handleCheckout);
    }
    
    // Attach checkout form handler
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', handleCheckoutSubmit);
    }

    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        loadProfilePage();
        profileForm.addEventListener('submit', handleProfileSave);
    }
});

async function handleAddToCart(productId) {
    try {
        // Check if product is in stock before adding to cart
        const products = await getProducts();
        const product = products.products.find(p => p.product_id == productId);

        if (!product || product.stock_quantity <= 0) {
            showMessage('This product is currently out of stock.');
            return;
        }

        const data = await addToCart(productId);
        updateCartCount(data.cart.count);
        showMessage('Product added to cart!');
    } catch (error) {
        showMessage('Failed to add to cart: ' + error.message);
    }
}

// Add this new function:
async function handleBuyNow(productId) {
    try {
        // Check if product is in stock before buying
        const products = await getProducts();
        const product = products.products.find(p => p.product_id == productId);

        if (!product || product.stock_quantity <= 0) {
            showMessage('This product is currently out of stock.');
            return;
        }

        const data = await addToCart(productId);
        updateCartCount(data.cart.count);
        // Redirect the user to the cart page immediately
        window.location.href = 'cart.php';
    } catch (error) {
        showMessage('Failed to process Buy Now: ' + error.message);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Check the URL for the 'category' parameter
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');

    if (categoryParam) {
        // Replace 'categoryDropdown' with the actual ID of your <select> element in fragrances.php
        const dropdown = document.getElementById('categoryDropdown'); 
        
        if (dropdown) {
            // Loop through options to find the matching category
            for (let i = 0; i < dropdown.options.length; i++) {
                if (dropdown.options[i].text === categoryParam || dropdown.options[i].value === categoryParam) {
                    dropdown.selectedIndex = i;
                    
                    // Manually trigger the 'change' event so your products actually filter
                    dropdown.dispatchEvent(new Event('change'));
                    break;
                }
            }
        }
    }
});

// ADDED: Notification Logic
async function loadNotifications() {
    const user = getLocalUser();
    if (!user) return;
    
    try {
        const response = await fetch(`${API_BASE}get_notifications.php?user_id=${user.customer_id}`);
        const data = await response.json();
        
        if (data.success) {
            renderNotifications(data.notifications);
        }
    } catch (error) {
        console.error('Failed to load notifications:', error);
    }
}

function renderNotifications(notifications) {
    const list = document.getElementById('notificationList');
    const badge = document.getElementById('notificationBadge');
    
    if (!list || !badge) return;
    
    list.innerHTML = '';
    
    const unreadCount = notifications.filter(n => !n.read).length;
    
    if (unreadCount > 0) {
        badge.textContent = unreadCount;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
    
    if (notifications.length === 0) {
        list.innerHTML = '<div style="padding: 20px; text-align: center; font-size: 12px; color: var(--taupe);">No notifications yet</div>';
        return;
    }
    
    notifications.forEach(notif => {
        const item = document.createElement('div');
        item.className = `notification-item ${notif.read ? 'read' : 'unread'}`;
        
        const date = new Date(notif.created_at).toLocaleString();
        
        item.innerHTML = `
            <div class="notification-title">${notif.title}</div>
            <div class="notification-message">${notif.message}</div>
            <div class="notification-time">${date}</div>
        `;
        
        item.onclick = async () => {
            if (!notif.read) {
                await markNotificationRead(notif.id);
            }
            // Navigate the user to their order history when they click it
            window.location.href = 'order_history.php';
        };
        
        list.appendChild(item);
    });
}

async function markNotificationRead(notificationId) {
    try {
        await fetch(`${API_BASE}mark_notification_read.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notification_id: notificationId })
        });
        loadNotifications(); // Reload to update the badge count
    } catch (error) {
        console.error('Failed to mark notification as read:', error);
    }
}

// Ensure the dropdown toggles securely
document.addEventListener("DOMContentLoaded", () => {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
        });
        
        // Hide if clicking outside
        document.addEventListener('click', (e) => {
            if (!notificationDropdown.contains(e.target) && e.target !== notificationBtn) {
                notificationDropdown.style.display = 'none';
            }
        });
    }
});