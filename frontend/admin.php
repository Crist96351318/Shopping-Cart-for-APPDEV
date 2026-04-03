<?php
require_once __DIR__ . '/../backend/config.php';
require_once __DIR__ . '/../backend/functions.php';

// Secure admin page: require a valid logged-in admin session
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Le Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        /* Define variables locally so the admin panel never breaks */
        :root {
            --cream: #f7f3ee;
            --warm-white: #fdfaf6;
            --sand: #e8dfd3;
            --taupe: #c2b5a5;
            --brown: #8a7060;
            --dark: #2a2219;
            --text: #3d3228;
            --serif: 'Cormorant Garamond', serif;
            --sans: 'Jost', sans-serif;
            --sidebar-width: 250px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: var(--sans); background-color: var(--warm-white); color: var(--text); display: flex; min-height: 100vh; font-weight: 300; }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--dark);
            color: var(--cream);
            position: fixed;
            height: 100vh;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            z-index: 100;
        }
        .sidebar-logo {
            padding: 40px 20px;
            text-align: center;
            font-family: var(--serif);
            font-size: 26px;
            letter-spacing: 0.15em;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            color: var(--cream);
        }
        .sidebar-logo small { font-family: var(--sans); font-size: 10px; letter-spacing: 0.25em; display: block; margin-top: 8px; color: var(--taupe); }
        .sidebar-nav { flex: 1; padding-top: 30px; }
        .sidebar-nav a {
            display: block;
            padding: 18px 30px;
            color: var(--taupe);
            text-decoration: none;
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background-color: rgba(255,255,255,0.03);
            color: var(--cream);
            border-left-color: var(--brown);
        }
        .logout-btn { margin-top: auto; margin-bottom: 40px; color: #cc6666 !important; }
        .logout-btn:hover { border-left-color: #cc6666 !important; background-color: rgba(204,102,102,0.05) !important; }

        /* Main Content */
        .admin-main { margin-left: var(--sidebar-width); flex: 1; padding: 60px 80px; background-color: var(--warm-white); }
        .section-title { font-family: var(--serif); font-size: 42px; font-weight: 300; color: var(--dark); margin-bottom: 40px; }
        .section-title em { font-style: italic; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 50px; }
        .stat-card {
            background: #ffffff; padding: 36px; border: 1px solid var(--sand);
            border-radius: 2px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .stat-label { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.2em; color: var(--brown); margin-bottom: 16px; font-weight: 500; }
        .stat-value { font-family: var(--serif); font-size: 42px; color: var(--dark); }

        /* Tables */
        .admin-table-card { background: #ffffff; padding: 40px; border: 1px solid var(--sand); border-radius: 2px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .table-header h3 { font-family: var(--serif); font-size: 28px; font-weight: 300; color: var(--dark); }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.15em; color: var(--taupe); padding: 20px 16px; border-bottom: 1px solid var(--sand); font-weight: 500; }
        td { padding: 20px 16px; font-size: 14px; color: var(--text); border-bottom: 1px solid var(--cream); vertical-align: middle; }
        tr:hover td { background-color: var(--warm-white); }

        /* Buttons & Actions */
        .btn-primary { background: var(--dark); color: var(--cream); padding: 14px 28px; border: none; font-family: var(--sans); font-size: 10.5px; letter-spacing: 0.18em; text-transform: uppercase; cursor: pointer; transition: 0.3s ease; }
        .btn-primary:hover { background: var(--brown); }
        
        .action-link { color: var(--brown); text-decoration: none; font-size: 12px; margin-right: 12px; transition: color 0.2s; cursor: pointer; border: none; background: none; font-family: var(--sans); text-transform: uppercase; letter-spacing: 0.1em; }
        .action-link:hover { color: var(--dark); }

        /* Status Badges */
        .status { padding: 6px 14px; border-radius: 20px; font-size: 9.5px; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 500; }
        .status.pending { background: #fdf3e1; color: #b7791f; border: 1px solid #f7dfb5; }
        .status.shipped { background: #e1f0fd; color: #2b6cb0; border: 1px solid #c2e0fa; }
        .status.completed { background: #e1f5e1; color: #2d7f2d; border: 1px solid #c2f0c2; }
        .status.cancelled { background: #fde1e1; color: #a32f2f; border: 1px solid #f5c2c2; }

        select { padding: 8px 12px; border: 1px solid var(--taupe); font-family: var(--sans); font-size: 12px; color: var(--text); outline: none; background: #fff; cursor: pointer; }
        select:focus { border-color: var(--dark); }
    </style>
</head>
<body>

<script>
    // Protect admin page - only admins can access, using server session + local storage
    async function checkAdminAccess() {
        try {
            const response = await fetch('../backend/user.php', {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Server session not authenticated');
            }

            const data = await response.json();
            if (!data.success || !data.user || !data.user.is_admin) {
                throw new Error('Not an admin');
            }

            localStorage.setItem('user', JSON.stringify(data.user));
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('isAdmin', 'true');
            return true;

        } catch (err) {
            localStorage.removeItem('user');
            localStorage.removeItem('isLoggedIn');
            localStorage.removeItem('isAdmin');
            alert('Access Denied: Admin privileges required. Please log in again.');
            window.location.href = 'login.php';
            return false;
        }
    }

    if (document.readyState !== 'loading') {
        checkAdminAccess();
    } else {
        document.addEventListener('DOMContentLoaded', checkAdminAccess);
    }
</script>

<aside class="admin-sidebar">
    <div class="sidebar-logo">LE PARFUM<small>ADMINISTRATOR</small></div>
    <nav class="sidebar-nav">
        <a href="#" class="active" onclick="showSection('analytics', event)">Dashboard</a>
        <a href="#" onclick="showSection('products', event)">Product Catalog</a>
        <a href="#" onclick="showSection('orders', event)">Order Management</a>
        <a href="#" onclick="showSection('admins', event)">Admin Management</a>
        <a href="#" class="logout-btn" onclick="handleLogout(event)">Logout</a>
    </nav>
</aside>

<main class="admin-main">
    <section id="analytics-section">
        <h2 class="section-title">Dashboard <em>Overview</em></h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value" id="totalRevenueValue">$0.00</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Orders</div>
                <div class="stat-value" id="activeOrdersValue">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Stock Alerts</div>
                <div class="stat-value" style="color: #cc6666;" id="stockAlertsValue">0 Low</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 60px;">
            <div class="stat-card">
                <div class="stat-label">Total Products</div>
                <div class="stat-value" id="totalProductsValue">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Customers</div>
                <div class="stat-value" id="totalCustomersValue">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Completed Orders</div>
                <div class="stat-value" id="completedOrdersValue">0</div>
            </div>
        </div>

        <div style="margin-top: 60px;">
            <div class="admin-table-card">
                <div class="table-header">
                    <h3>Recent Transactions</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="recentTransactionsBody">
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 24px;">Loading recent orders...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="products-section" style="display:none;">
        <h2 class="section-title">Product <em>Catalog</em></h2>
        
        <div class="admin-table-card">
            <div class="table-header">
                <h3>Current Inventory</h3>
            </div>

            <form id="addProductForm" style="margin-bottom: 24px; display: grid; gap: 12px; grid-template-columns: repeat(2, 1fr);">
                <input type="text" name="name" placeholder="Product Name" required style="padding: 10px; border: 1px solid var(--taupe)">
                <input type="text" name="category" placeholder="Category" required style="padding: 10px; border: 1px solid var(--taupe)">
                <input type="number" name="price" placeholder="Price" step="0.01" min="0" required style="padding: 10px; border: 1px solid var(--taupe)">
                <input type="number" name="stock_quantity" placeholder="Stock Quantity" min="0" required style="padding: 10px; border: 1px solid var(--taupe)">
                <input type="url" name="image_path" placeholder="Primary Image URL" style="padding: 10px; border: 1px solid var(--taupe); grid-column: 1 / span 2;">
                <div style="grid-column: 1 / span 2;">
                    <label style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; color: var(--brown); margin-bottom: 8px;">Add Image</label>
                    <div id="addAdditionalImages"></div>
                    <button type="button" onclick="addImageFieldToAdd()" style="background: #f0f0f0; border: 1px solid #ccc; padding: 5px 10px; margin-top: 5px;">+ Upload Image</button>
                </div>
                <input type="text" name="description" placeholder="Description" style="padding: 10px; border: 1px solid var(--taupe); grid-column: 1 / span 2;">
                <button type="submit" class="btn-primary" style="grid-column: 1 / span 2;">Add New Perfume</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="inventoryRows">
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 24px;">No products loaded yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section id="orders-section" style="display:none;">
        <h2 class="section-title">Customer <em>Orders</em></h2>
        
        <div class="admin-table-card">
            <div class="table-header">
                <h3>Recent Transactions</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="adminOrdersList">
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 24px;">Loading orders...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section id="admins-section" style="display:none;">
        <h2 class="section-title">Admin <em>Management</em></h2>
        
        <div class="admin-table-card">
            <div class="table-header">
                <h3>Register New Admin</h3>
            </div>
            <form id="admin-registration-form" style="max-width: 500px; margin-bottom: 40px;">
                <div style="margin-bottom: 20px;">
                    <label for="admin-username" style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; color: var(--brown); margin-bottom: 8px;">Username</label>
                    <input type="text" id="admin-username" name="username" required style="width: 100%; padding: 12px; border: 1px solid var(--taupe); font-family: var(--sans); font-size: 14px; outline: none;" placeholder="Enter admin username">
                </div>
                <div style="margin-bottom: 20px;">
                    <label for="admin-email" style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; color: var(--brown); margin-bottom: 8px;">Email</label>
                    <input type="email" id="admin-email" name="email" required style="width: 100%; padding: 12px; border: 1px solid var(--taupe); font-family: var(--sans); font-size: 14px; outline: none;" placeholder="Enter admin email">
                </div>
                <div style="margin-bottom: 30px;">
                    <label for="admin-password" style="display: block; font-size: 12px; text-transform: uppercase; letter-spacing: 0.15em; color: var(--brown); margin-bottom: 8px;">Password</label>
                    <input type="password" id="admin-password" name="password" required style="width: 100%; padding: 12px; border: 1px solid var(--taupe); font-family: var(--sans); font-size: 14px; outline: none;" placeholder="Enter admin password">
                </div>
                <button type="submit" class="btn-primary">Register Admin</button>
            </form>
            
            <div class="table-header">
                <h3>Current Admins</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="admins-list">
                    <!-- Admins will be loaded here -->
                </tbody>
            </table>
        </div>
    </section>
</main>

<script>
    function showSection(section, event) {
        if (event) event.preventDefault();
        
        // Hide all
        document.getElementById('analytics-section').style.display = 'none';
        document.getElementById('products-section').style.display = 'none';
        document.getElementById('orders-section').style.display = 'none';
        document.getElementById('admins-section').style.display = 'none';
        
        // Show target
        document.getElementById(section + '-section').style.display = 'block';
        
        // Update active nav state
        const links = document.querySelectorAll('.sidebar-nav a:not(.logout-btn)');
        links.forEach(link => link.classList.remove('active'));
        if (event) event.currentTarget.classList.add('active');
        
        // Load data based on section
        if (section === 'analytics') {
            loadDashboardStats();
        } else if (section === 'admins') {
            loadAdminsList();
        } else if (section === 'orders') {
            loadOrdersList();
        } else if (section === 'products') {
            fetchProductsForAdmin();
        }
    }

    // Load dashboard stats
    function loadDashboardStats() {
        fetch('../backend/get_dashboard_stats.php')
        .then(response => response.json())
        .then(result => {
            if (result.success && result.stats) {
                const stats = result.stats;
                
                // Update total revenue
                document.getElementById('totalRevenueValue').textContent = '$' + parseFloat(stats.total_revenue).toFixed(2);
                
                // Update active orders
                document.getElementById('activeOrdersValue').textContent = stats.active_orders;
                
                // Update stock alerts
                document.getElementById('stockAlertsValue').textContent = stats.low_stock_count + ' Low';
                
                // Update total products
                document.getElementById('totalProductsValue').textContent = stats.total_products;
                
                // Update total customers
                document.getElementById('totalCustomersValue').textContent = stats.total_customers;
                
                // Calculate completed orders count from recent orders
                const completedCount = stats.recent_orders.filter(o => o.status === 'Completed').length;
                document.getElementById('completedOrdersValue').textContent = completedCount;
                
                // Render recent transactions table
                renderRecentTransactions(stats.recent_orders);
                
                console.log('Dashboard stats loaded:', stats);
            } else {
                console.error('Failed to load dashboard stats');
            }
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
        });
    }

    // Render recent transactions on dashboard
    function renderRecentTransactions(recentOrders) {
        const tbody = document.getElementById('recentTransactionsBody');
        tbody.innerHTML = '';

        if (!recentOrders || recentOrders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding: 24px;">No recent orders</td></tr>';
            return;
        }

        recentOrders.forEach(order => {
            const orderDate = new Date(order.date).toLocaleDateString();
            const statusClass = order.status.toLowerCase();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>#${order.order_id}</td>
                <td>${order.customer_name || 'Guest'}</td>
                <td>${orderDate}</td>
                <td>$${parseFloat(order.amount).toFixed(2)}</td>
                <td><span class="status ${statusClass}">${order.status}</span></td>
            `;
            tbody.appendChild(row);
        });
    }

    // Handle admin registration form
    document.getElementById('admin-registration-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            username: formData.get('username'),
            email: formData.get('email'),
            password: formData.get('password')
        };
        
        fetch('../backend/add_admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Admin registered successfully!');
                this.reset();
                loadAdminsList();
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while registering the admin.');
        });
    });

    // Load admins list
    function loadAdminsList() {
        fetch('../backend/get_admins.php')
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const tbody = document.getElementById('admins-list');
                tbody.innerHTML = '';
                
                result.admins.forEach(admin => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${admin.admin_id}</td>
                        <td>${admin.username}</td>
                        <td>${admin.email}</td>
                        <td>${new Date(admin.created_at).toLocaleDateString()}</td>
                        <td>
                            <button class="action-link" onclick="editAdminPassword(${admin.admin_id}, '${admin.username}')">Change Password</button>
                            <button class="action-link" style="color: #cc6666;" onclick="deleteAdmin(${admin.admin_id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                console.error('Error loading admins:', result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Delete admin function
    function deleteAdmin(adminId) {
        if (confirm('Are you sure you want to delete this admin?')) {
            fetch('../backend/delete_admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ admin_id: adminId })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Admin deleted successfully!');
                    loadAdminsList();
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the admin.');
            });
        }
    }

    // Edit admin password function
    function editAdminPassword(adminId, username) {
        const newPassword = prompt(`Set a new password for ${username} (min 6 chars):`);
        if (newPassword === null) return; // canceled
        if (newPassword.length < 6) {
            alert('Password must be at least 6 characters long.');
            return;
        }

        fetch('../backend/update_admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ admin_id: adminId, password: newPassword })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Password updated successfully!');
                loadAdminsList();
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the admin password.');
        });
    }

    // Load orders list
    function loadOrdersList() {
        console.log('loadOrdersList called');
        fetch('../backend/get_all_orders.php', { credentials: 'same-origin' })
        .then(response => {
            console.log('get_all_orders response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(result => {
            console.log('get_all_orders result:', result);
            if (result.success) {
                renderOrdersTable(result.orders);
            } else {
                document.getElementById('ordersTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center; color: #cc6666;">Failed to load orders: ' + (result.message || 'Unknown error') + '</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            document.getElementById('ordersTableBody').innerHTML = '<tr><td colspan="7" style="text-align:center; color: #cc6666;">Error: ' + error.message + '</td></tr>';
        });
    }

    // Render orders table
    function renderOrdersTable(orders) {
        console.log('renderOrdersTable called with:', orders);
        const tbody = document.getElementById('ordersTableBody');
        console.log('tbody element:', tbody);
        
        if (!tbody) {
            console.error('ordersTableBody element not found!');
            return;
        }
        
        tbody.innerHTML = '';

        if (!orders || orders.length === 0) {
            console.log('No orders to render');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 24px;">No orders found</td></tr>';
            return;
        }

        console.log('Rendering ' + orders.length + ' orders');
        orders.forEach((order, index) => {
            const orderDate = new Date(order.order_date).toLocaleDateString();
            const statusClass = order.status.toLowerCase();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>#${order.order_id}</td>
                <td>${order.customer_name || 'Guest'}</td>
                <td>${order.email || '-'}</td>
                <td>${orderDate}</td>
                <td>$${parseFloat(order.total_amount).toFixed(2)}</td>
                <td><span class="status ${statusClass}">${order.status}</span></td>
                <td>
                    <select onchange="updateOrderStatus(${order.order_id}, this.value)">
                        <option value="Pending" ${order.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="Shipped" ${order.status === 'Shipped' ? 'selected' : ''}>Shipped</option>
                        <option value="Completed" ${order.status === 'Completed' ? 'selected' : ''}>Completed</option>
                        <option value="Cancelled" ${order.status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </td>
            `;
            tbody.appendChild(row);
            console.log('Order row ' + (index + 1) + ' added');
        });
        console.log('All ' + orders.length + ' orders rendered');
    }

    // Update order status
    function updateOrderStatus(orderId, newStatus) {
        fetch('../backend/update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ order_id: orderId, status: newStatus })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                console.log('Order status updated successfully');
                loadOrdersList();
            } else {
                alert('Error: ' + result.message);
                loadOrdersList();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the order status.');
            loadOrdersList();
        });
    }

    // Add product / inventory functions
    function renderProductsTable(products) {
        const tbody = document.getElementById('inventoryRows');
        tbody.innerHTML = '';

        if (!products || products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 24px;">No products found</td></tr>';
            return;
        }

        products.forEach(product => {
            const imageHtml = product.image_path ? `<img src="${product.image_path}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">` : 'No image';
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>#${product.product_id}</td>
                <td>${imageHtml}</td>
                <td>${product.name}</td>
                <td>${product.category_name || product.category_id || '-'}</td>
                <td>$${parseFloat(product.price).toFixed(2)}</td>
                <td>${product.stock_quantity}</td>
                <td>
                    <button class="action-link" onclick="promptEditProduct(${product.product_id})">Edit</button>
                    <button class="action-link" style="color: #cc6666;" onclick="removeProduct(${product.product_id})">Delete</button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function fetchProductsForAdmin() {
        fetch('../backend/get_products.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderProductsTable(data.products);
            } else {
                alert('Failed to load products: ' + (data.message || 'unknown'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error loading products');
        });
    }



    document.getElementById('addProductForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(this);
        const data = {
            name: formData.get('name'),
            category: formData.get('category'),
            description: formData.get('description'),
            price: parseFloat(formData.get('price')),
            stock_quantity: parseInt(formData.get('stock_quantity'), 10),
            image_path: formData.get('image_path')
        };

        // First add the product
        fetch('../backend/add_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Then add additional images if any
                const additionalImages = formData.getAll('additional_images[]').filter(url => url.trim());
                if (additionalImages.length > 0) {
                    const productId = result.product_id;
                    const promises = additionalImages.map(imgUrl => 
                        fetch('../backend/add_product_image.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({product_id: productId, image_path: imgUrl})
                        }).then(res => res.json())
                    );
                    Promise.all(promises).then(results => {
                        const failures = results.filter(r => !r.success);
                        if (failures.length > 0) {
                            alert('Product added but some images failed to add.');
                        } else {
                            alert('Product and images added successfully!');
                        }
                        this.reset();
                        document.getElementById('addAdditionalImages').innerHTML = '';
                        fetchProductsForAdmin();
                    });
                } else {
                    alert('Product added successfully!');
                    this.reset();
                    fetchProductsForAdmin();
                }
            } else {
                alert('Error adding product: ' + (result.message || 'unknown'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error adding product');
        });
    });

    // Fetch products initially when products section opens
    function showSection(section, event) {
        if (event) event.preventDefault();

        document.getElementById('analytics-section').style.display = 'none';
        document.getElementById('products-section').style.display = 'none';
        document.getElementById('orders-section').style.display = 'none';
        document.getElementById('admins-section').style.display = 'none';

        document.getElementById(section + '-section').style.display = 'block';

        const links = document.querySelectorAll('.sidebar-nav a:not(.logout-btn)');
        links.forEach(link => link.classList.remove('active'));
        if (event) event.currentTarget.classList.add('active');

        if (section === 'admins') {
            loadAdminsList();
        }
        if (section === 'products') {
            fetchProductsForAdmin();
        }
    }

    function removeProduct(productId) {
        if (!confirm('Delete this product?')) return;
        fetch('../backend/delete_product.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({product_id: productId})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Product removed successfully');
                fetchProductsForAdmin();
            } else {
                alert('Unable to delete product: ' + (data.message || 'unknown'));
            }
        });
    }

    function promptEditProduct(productId) {
        // Find the product data from the current table
        const rows = document.querySelectorAll('#inventoryRows tr');
        let productData = null;
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0 && cells[0].textContent === `#${productId}`) {
                productData = {
                    id: productId,
                    name: cells[2].textContent,
                    category: cells[3].textContent,
                    price: parseFloat(cells[4].textContent.replace('$', '')),
                    stock: parseInt(cells[5].textContent)
                };
            }
        });

        if (!productData) {
            alert('Product data not found');
            return;
        }

        // Create edit form
        const editForm = document.createElement('div');
        editForm.innerHTML = `
            <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center;">
                <div style="background: white; padding: 20px; border-radius: 8px; max-width: 500px; width: 90%;">
                    <h3>Edit Product</h3>
                    <form id="editProductForm">
                        <input type="hidden" name="product_id" value="${productId}">
                        <div style="margin-bottom: 10px;">
                            <label>Name:</label>
                            <input type="text" name="name" value="${productData.name}" required style="width: 100%; padding: 8px;">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label>Category:</label>
                            <input type="text" name="category" value="${productData.category}" required style="width: 100%; padding: 8px;">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label>Price:</label>
                            <input type="number" name="price" value="${productData.price}" step="0.01" min="0" required style="width: 100%; padding: 8px;">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label>Stock:</label>
                            <input type="number" name="stock_quantity" value="${productData.stock}" min="0" required style="width: 100%; padding: 8px;">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label>Image URL:</label>
                            <input type="url" name="image_path" placeholder="https://..." style="width: 100%; padding: 8px;">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label>Description:</label>
                            <textarea name="description" style="width: 100%; padding: 8px; height: 60px;"></textarea>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label>Additional Images:</label>
                            <div id="additionalImages"></div>
                            <button type="button" onclick="addImageField()" style="background: #f0f0f0; border: 1px solid #ccc; padding: 5px 10px;">+ Add Image</button>
                        </div>
                        <button type="submit" class="btn-primary">Update Product</button>
                        <button type="button" onclick="closeEditForm()" style="margin-left: 10px; background: #ccc;">Cancel</button>
                    </form>
                </div>
            </div>
        `;
        document.body.appendChild(editForm);

        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = {
                product_id: formData.get('product_id'),
                name: formData.get('name'),
                category: formData.get('category'),
                description: formData.get('description'),
                price: parseFloat(formData.get('price')),
                stock_quantity: parseInt(formData.get('stock_quantity')),
                image_path: formData.get('image_path')
            };

            // First update the product
            fetch('../backend/update_product.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Then add additional images if any
                    const additionalImages = formData.getAll('additional_images[]').filter(url => url.trim());
                    if (additionalImages.length > 0) {
                        const promises = additionalImages.map(imgUrl => 
                            fetch('../backend/add_product_image.php', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json'},
                                body: JSON.stringify({product_id: data.product_id, image_path: imgUrl})
                            }).then(res => res.json())
                        );
                        Promise.all(promises).then(results => {
                            const failures = results.filter(r => !r.success);
                            if (failures.length > 0) {
                                alert('Product updated but some images failed to add.');
                            } else {
                                alert('Product and images updated successfully!');
                            }
                            closeEditForm();
                            fetchProductsForAdmin();
                        });
                    } else {
                        alert('Product updated successfully!');
                        closeEditForm();
                        fetchProductsForAdmin();
                    }
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the product.');
            });
        });
    }

    function addImageFieldToAdd() {
        const container = document.getElementById('addAdditionalImages');
        const div = document.createElement('div');
        div.style.marginBottom = '5px';
        div.innerHTML = '<input type="url" name="additional_images[]" placeholder="Additional image URL" style="width: 80%; padding: 5px;"><button type="button" onclick="this.parentNode.remove()" style="margin-left: 5px;">Remove</button>';
        container.appendChild(div);
    }

    function closeEditForm() {
        const editForm = document.querySelector('div[style*="position: fixed"]');
        if (editForm) editForm.remove();
    }

    // Initialize dashboard on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardStats();
    });

    function showSection(section, event) {
        if (event) event.preventDefault();

        document.getElementById('analytics-section').style.display = 'none';
        document.getElementById('products-section').style.display = 'none';
        document.getElementById('orders-section').style.display = 'none';
        document.getElementById('admins-section').style.display = 'none';

        document.getElementById(section + '-section').style.display = 'block';

        const links = document.querySelectorAll('.sidebar-nav a:not(.logout-btn)');
        links.forEach(link => link.classList.remove('active'));
        if (event) event.currentTarget.classList.add('active');

        if (section === 'admins') {
            loadAdminsList();
        }
        if (section === 'products') {
            fetchProductsForAdmin();
        }
        // (Hmph) Add this to load orders when the tab is clicked!
        if (section === 'orders') {
            loadAdminOrders();
        }
    }

    function loadAdminOrders() {
        const tbody = document.getElementById('adminOrdersList');
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 24px;">Loading orders...</td></tr>';
        
        // I assume your backend file is named get_all_orders.php. Change it if it's different!
        fetch('../backend/get_all_orders.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderAdminOrders(data.orders);
                } else {
                    tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding: 24px; color: #cc6666;">Error: ${data.message}</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error fetching orders:', error);
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 24px; color: #cc6666;">Failed to load orders.</td></tr>';
            });
    }

    function renderAdminOrders(orders) {
        const tbody = document.getElementById('adminOrdersList');
        tbody.innerHTML = '';
        
        if (!orders || orders.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding: 24px;">No orders found.</td></tr>';
            return;
        }
        
        orders.forEach(order => {
            const date = new Date(order.order_date).toLocaleDateString();
            
            // Format status class so the colors actually work
            let statusClass = 'pending';
            if (order.status.toLowerCase() === 'shipped') statusClass = 'shipped';
            if (order.status.toLowerCase() === 'completed') statusClass = 'completed';
            
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>#${order.order_id}</td>
                <td>${order.customer_name}</td>
                <td>${order.email}</td>
                <td>${date}</td>
                <td>$${parseFloat(order.total_amount).toFixed(2)}</td>
                <td><span class="status ${statusClass}">${order.status}</span></td>
                <td>
                    <select onchange="updateOrderStatus(${order.order_id}, this.value)">
                        <option value="Pending" ${order.status === 'Pending' ? 'selected' : ''}>Pending</option>
                        <option value="Shipped" ${order.status === 'Shipped' ? 'selected' : ''}>Shipped</option>
                        <option value="Completed" ${order.status === 'Completed' ? 'selected' : ''}>Completed</option>
                    </select>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Optional: Add this if you want the dropdowns to actually change the status
    function updateOrderStatus(orderId, newStatus) {
        fetch('../backend/update_order_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId, status: newStatus })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadAdminOrders(); // Reload to update badge colors
            } else {
                alert('Failed to update status: ' + data.message);
            }
        });
    }
</script>

<script src="script.js"></script>

</body>
</html>