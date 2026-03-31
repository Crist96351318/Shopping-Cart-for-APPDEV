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

        select { padding: 8px 12px; border: 1px solid var(--taupe); font-family: var(--sans); font-size: 12px; color: var(--text); outline: none; background: #fff; cursor: pointer; }
        select:focus { border-color: var(--dark); }
    </style>
</head>
<body>

<aside class="admin-sidebar">
    <div class="sidebar-logo">LE PARFUM<small>ADMINISTRATOR</small></div>
    <nav class="sidebar-nav">
        <a href="#" class="active" onclick="showSection('analytics', event)">Dashboard</a>
        <a href="#" onclick="showSection('products', event)">Product Catalog</a>
        <a href="#" onclick="showSection('orders', event)">Order Management</a>
        <a href="../backend/logout.php" class="logout-btn">Logout</a>
    </nav>
</aside>

<main class="admin-main">
    <section id="analytics-section">
        <h2 class="section-title">Dashboard <em>Overview</em></h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">$12,450.00</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Active Orders</div>
                <div class="stat-value">18</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Stock Alerts</div>
                <div class="stat-value" style="color: #cc6666;">4 Low</div>
            </div>
        </div>
    </section>

    <section id="products-section" style="display:none;">
        <h2 class="section-title">Product <em>Catalog</em></h2>
        
        <div class="admin-table-card">
            <div class="table-header">
                <h3>Current Inventory</h3>
                <button class="btn-primary">+ Add New Perfume</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#101</td>
                        <td>Island Khadlaj</td>
                        <td>Extrait de Parfum</td>
                        <td>$120.00</td>
                        <td>45</td>
                        <td>
                            <button class="action-link">Edit</button>
                            <button class="action-link" style="color: #cc6666;">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#102</td>
                        <td>Chanel N°5</td>
                        <td>Eau de Parfum</td>
                        <td>$150.00</td>
                        <td>12</td>
                        <td>
                            <button class="action-link">Edit</button>
                            <button class="action-link" style="color: #cc6666;">Delete</button>
                        </td>
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
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#8021</td>
                        <td>Cristian Aton</td>
                        <td>Mar 31, 2026</td>
                        <td>$270.00</td>
                        <td><span class="status pending">Pending</span></td>
                        <td>
                            <select>
                                <option>Pending</option>
                                <option>Shipped</option>
                                <option>Completed</option>
                            </select>
                        </td>
                    </tr>
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
        
        // Show target
        document.getElementById(section + '-section').style.display = 'block';
        
        // Update active nav state
        const links = document.querySelectorAll('.sidebar-nav a:not(.logout-btn)');
        links.forEach(link => link.classList.remove('active'));
        if (event) event.currentTarget.classList.add('active');
    }
</script>

</body>
</html>