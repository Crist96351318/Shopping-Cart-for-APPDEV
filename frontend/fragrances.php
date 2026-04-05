<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fragrances | Le parfum Shop</title>
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

<main style="padding: 80px 48px; min-height: 60vh;">
    <section class="section-header">
      <span class="section-tag">Explore our</span>
      <h2 class="section-title">Fragrance <em>Collection</em></h2>
    </section>

    <div class="container" style="margin-top: 60px;">
        <div class="category-item">
            <button class="category-trigger" onclick="toggleCategory('extrait', 'Extrait de Parfum')">
                <span>Extrait de Parfum</span>
                <span class="icon">+</span>
            </button>
            <div id="extrait" class="category-content">
                <div class="product-grid" id="extrait-grid">
                    <div class="loading">Loading products...</div>
                </div>
            </div>
        </div>

        <div class="category-item">
            <button class="category-trigger" onclick="toggleCategory('edp', 'Eau de Parfum')">
                <span>Eau de Parfum</span>
                <span class="icon">+</span>
            </button>
            <div id="edp" class="category-content">
                <div class="product-grid" id="edp-grid">
                    <div class="loading">Loading products...</div>
                </div>
            </div>
        </div>

        <div class="category-item">
            <button class="category-trigger" onclick="toggleCategory('edt', 'Eau de Toilette')">
                <span>Eau de Toilette</span>
                <span class="icon">+</span>
            </button>
            <div id="edt" class="category-content">
                <div class="product-grid" id="edt-grid">
                    <div class="loading">Loading products...</div>
                </div>
            </div>
        </div>

        <div class="category-item">
            <button class="category-trigger" onclick="toggleCategory('edc', 'Eau de Cologne')">
                <span>Eau de Cologne</span>
                <span class="icon">+</span>
            </button>
            <div id="edc" class="category-content">
                <div class="product-grid" id="edc-grid">
                    <div class="loading">Loading products...</div>
                </div>
            </div>
        </div>

        <div class="category-item">
            <button class="category-trigger" onclick="toggleCategory('fraiche', 'Eau Fraiche')">
                <span>Eau Fraiche</span>
                <span class="icon">+</span>
            </button>
            <div id="fraiche" class="category-content">
                <div class="product-grid" id="fraiche-grid">
                    <div class="loading">Loading products...</div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .category-item { border-bottom: 1px solid var(--sand); }
    .category-trigger {
        width: 100%;
        padding: 30px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: var(--serif);
        font-size: 28px;
        color: var(--dark);
        text-transform: none;
        letter-spacing: 0;
    }
    .category-trigger .icon { font-size: 20px; color: var(--taupe); transition: transform 0.3s; }
    .category-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-out, padding 0.3s;
    }
    .category-content.show { max-height: 2000px; padding-bottom: 60px; }
    .category-trigger.active .icon { transform: rotate(45deg); }

    .loading, .error, .no-products {
        text-align: center;
        padding: 40px;
        color: var(--taupe);
        font-style: italic;
    }

    .error { color: #cc6666; }
    .no-products { color: var(--brown); }
</style>
<script>
function toggleCategory(id, categoryName) {
    const content = document.getElementById(id);
    const trigger = content.previousElementSibling;
    const isOpening = !content.classList.contains('show');

    content.classList.toggle('show');
    trigger.classList.toggle('active');

    // Load products if opening and not already loaded
    if (isOpening && !content.dataset.loaded) {
        loadCategoryProducts(id, categoryName);
    }
}

function loadCategoryProducts(categoryId, categoryName) {
    const grid = document.getElementById(categoryId + '-grid');
    const content = document.getElementById(categoryId);

    if (window.fragranceProducts && window.fragranceProducts.length > 0) {
        const products = window.fragranceProducts.filter(p => p.category_name && p.category_name.toLowerCase() === categoryName.toLowerCase());
        renderCategoryProducts(grid, products);
        content.dataset.loaded = 'true';
        return;
    }

    fetch('../backend/get_products.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.fragranceProducts = data.products || [];
                const products = window.fragranceProducts.filter(p => p.category_name && p.category_name.toLowerCase() === categoryName.toLowerCase());
                renderCategoryProducts(grid, products);
                content.dataset.loaded = 'true';
            } else {
                grid.innerHTML = '<div class="error">Failed to load products</div>';
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
            grid.innerHTML = '<div class="error">Error loading products</div>';
        });
}

function renderCategoryProducts(container, products) {
    if (!products || products.length === 0) {
        container.innerHTML = '<div class="no-products">No products available in this category</div>';
        return;
    }

    const html = products.map(product => `
        <div class="product-card">
            <div class="product-img-wrap" style="height: 250px; display: flex; justify-content: center; align-items: center; overflow: hidden; background: #f9f9f9;">
                <img src="${product.image_path || '../assets/placeholder.png'}" class="product-img" alt="${product.name}" style="width: 100%; height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                <div class="product-actions">
                    <button class="add-cart" style="width: 100%;" onclick="handleBuyNow(${product.product_id})">Buy Now</button>
                    <button class="add-cart" style="width: 100%;" onclick="handleAddToCart(${product.product_id})">Add to Cart</button>
                </div>
            </div>
            <div class="product-name">${product.name}</div>
            <div class="product-price">$${parseFloat(product.price).toFixed(2)}</div>
        </div>
    `).join('');

    container.innerHTML = html;
}

// Preload all category products on page show and keep data in window cache
document.addEventListener('DOMContentLoaded', function() {
    const categories = {
        extrait: 'Extrait de Parfum',
        edp: 'Eau de Parfum',
        edt: 'Eau de Toilette',
        edc: 'Eau de Cologne',
        fraiche: 'Eau Fraiche'
    };

    // Perform a single bulk fetch once
    fetch('../backend/get_products.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to retrieve products');
            }
            window.fragranceProducts = data.products || [];

            // Render all categories immediately
            Object.entries(categories).forEach(([id, name]) => {
                const content = document.getElementById(id);
                if (content) {
                    const grid = document.getElementById(id + '-grid');
                    const products = window.fragranceProducts.filter(p => p.category_name && p.category_name.toLowerCase() === name.toLowerCase());
                    renderCategoryProducts(grid, products);
                    content.dataset.loaded = 'true';
                }
            });

            // Handle the URL parameter to auto-dropdown the category
            const urlParams = new URLSearchParams(window.location.search);
            const categoryParam = urlParams.get('category');

            if (categoryParam) {
                const targetId = Object.keys(categories).find(key => 
                    categories[key].toLowerCase() === categoryParam.toLowerCase()
                );

                if (targetId) {
                    const targetContent = document.getElementById(targetId);
                    const targetTrigger = targetContent.previousElementSibling;
                    
                    targetContent.classList.add('show');
                    targetTrigger.classList.add('active');

                    // Scroll to it so it is perfectly visible
                    setTimeout(() => {
                        targetTrigger.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            }
        })
        .catch(error => {
            console.error('Error preloading products:', error);
            Object.keys(categories).forEach(id => {
                const grid = document.getElementById(id + '-grid');
                if (grid) grid.innerHTML = '<div class="error">Unable to load products</div>';
            });
        });
});
</script>


<footer>
  <div class="footer-bottom">
    <p>© 2026 Lumière Skincare. All rights reserved.</p>
  </div>
</footer>

<button id="scrollToTopBtn" class="scroll-to-top" title="Go to top">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <line x1="12" y1="19" x2="12" y2="5"></line>
    <polyline points="5 12 12 5 19 12"></polyline>
  </svg>
</button>

<script src="script.js"></script>

</body>
</html> 