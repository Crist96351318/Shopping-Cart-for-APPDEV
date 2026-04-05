<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le parfum Shop</title>
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

<section class="hero">
  <div class="hero-content">
    <span class="hero-eyebrow">New Collection 2026</span>
    <h1 class="hero-title">Just like Men, Perfume is <br><em> Never Perfect Right Away; </em><br><em>You have to let it Seduce You.</em></h1>
    <p class="hero-sub">Masterfully blended fragrances crafted with rare aromatic notes and captivating essences for an unforgettable signature scent.</p>
    <div class="hero-actions">
      <a href="fragrances.php" class="btn-primary" style="display: inline-block; text-align: center;">Shop Now!</a>
      <a href="our_story.php" class="btn-outline" style="display: inline-flex; align-items: center; justify-content: center;">Our Story</a>
    </div>
  </div>
  <div class="hero-image">
  <div class="hero-image-placeholder" style="padding: 60px;">
    <img src="../assets/bg.png" alt="Featured Perfume Collection" style="width: 110%; height: 115%; object-fit: cover; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
    </div>
  </div>
</div>
</section>

<section class="categories">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Explore</span>
      <h2 class="section-title">Perfume <em>Categories</em></h2>
    </div>
  <div class="cat-grid">

  <div class="cat-card" style="cursor: pointer;" onclick="window.location.href='fragrances.php?category=Extrait de Parfum'">
    <div class="cat-img">
      <img src="../assets/cat1.png" alt="Extrait de Parfum Collection">
    </div>
    <div class="cat-label">
      <div class="cat-label-tag">Extrait de Parfum</div>
      <div class="cat-label-name">20-40% Concentration</div>
    </div>
  </div>

  <div class="cat-card" style="cursor: pointer;" onclick="window.location.href='fragrances.php?category=Eau de Parfum'">
    <div class="cat-img">
      <img src="../assets/cat2.png" alt="Eau de Parfum Collection">
    </div>
    <div class="cat-label">
      <div class="cat-label-tag">Eau de Parfum</div>
      <div class="cat-label-name">15-20% Concentration</div>
    </div>
  </div>

  <div class="cat-card" style="cursor: pointer;" onclick="window.location.href='fragrances.php?category=Eau de Toilette'">
    <div class="cat-img">
      <img src="../assets/cat3.png" alt="Eau de Toilette Collection">
    </div>
    <div class="cat-label">
      <div class="cat-label-tag">Eau de Toilette</div>
      <div class="cat-label-name">5-15% Concentration</div>
    </div>
  </div>

  <div class="cat-card" style="cursor: pointer;" onclick="window.location.href='fragrances.php?category=Eau de Cologne'">
    <div class="cat-img">
      <img src="../assets/cat4.png" alt="Eau de Cologne Collection">
    </div>
    <div class="cat-label">
      <div class="cat-label-tag">Eau de Cologne</div>
      <div class="cat-label-name">2-5% Concentration</div>
    </div>
  </div>

  <div class="cat-card" style="cursor: pointer;" onclick="window.location.href='fragrances.php?category=Eau Fraiche'">
    <div class="cat-img">
      <img src="../assets/cat5.png" alt="Eau Fraiche Collection">
    </div>
    <div class="cat-label">
      <div class="cat-label-tag">Eau Fraiche</div>
      <div class="cat-label-name">1-3% Concentration</div>
    </div>
  </div>

</div>
</div>
</section>

<section class="featured">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Bestsellers</span>
      <h2 class="section-title">Most <em>Loved</em></h2>
    </div>
    <div class="product-grid" id="featuredProductsGrid">
      <p style="grid-column: span 4; text-align: center;">Loading bestsellers...</p>
    </div>
  </div>
</section>

<section class="testimonials">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Reviews</span>
      <h2 class="section-title">What They're <em>Saying</em></h2>
    </div>
    <div class="testi-grid">
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"My skin has never looked this luminous. The Glow Serum is the one product I'd take to a desert island without question."</p>
        <div class="testi-author">
          <div class="testi-avatar">S</div>
          <div>
            <div class="testi-name">Sophia M.</div>
            <div class="testi-handle">Verified Customer</div>
          </div>
        </div>
      </div>
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"I tried everything for my dry skin. The Velvet Cloud Cream solved it in two weeks. I'm completely obsessed and have told everyone."</p>
        <div class="testi-author">
          <div class="testi-avatar">A</div>
          <div>
            <div class="testi-name">Amara K.</div>
            <div class="testi-handle">Verified Customer</div>
          </div>
        </div>
      </div>
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"Sustainable, effective, beautifully packaged. Lumière is everything I want a clean beauty brand to be. Repeat customer forever."</p>
        <div class="testi-author">
          <div class="testi-avatar">C</div>
          <div>
            <div class="testi-name">Clara B.</div>
            <div class="testi-handle">Verified Customer</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-logo">Le Parfum</div>
      <p class="footer-desc">Clean, botanical skincare rooted in nature and crafted for your most radiant self. Cruelty-free, sustainably sourced.</p>
      <div class="footer-social">
        <a class="social-link" href="#">ig</a>
        <a class="social-link" href="#">fb</a>
        <a class="social-link" href="#">tk</a>
        <a class="social-link" href="#">pi</a>
      </div>
    </div>
    <div>
      <div class="footer-heading">Shop</div>
      <div class="footer-links">
        <a href="#">New Arrivals</a>
        <a href="#">Bestsellers</a>
        <a href="#">Skincare</a>
        <a href="#">Body Care</a>
        <a href="#">Gift Sets</a>
      </div>
    </div>
    <div>
      <div class="footer-heading">Help</div>
      <div class="footer-links">
        <a href="#">FAQ</a>
        <a href="#">Shipping & Returns</a>
        <a href="#">Track Your Order</a>
        <a href="#">Contact Us</a>
      </div>
    </div>
    <div>
      <div class="footer-heading">Company</div>
      <div class="footer-links">
        <a href="#">Our Story</a>
        <a href="#">Ingredients</a>
        <a href="#">Sustainability</a>
        <a href="#">Journal</a>
        <a href="#">Press</a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 Le Parfum. All rights reserved.</p>
    <div class="payment-icons">
      <span class="pay-icon">Visa</span>
      <span class="pay-icon">MC</span>
      <span class="pay-icon">Amex</span>
      <span class="pay-icon">PayPal</span>
      <span class="pay-icon">Shop Pay</span>
    </div>
  </div>
</footer>


<button id="scrollToTopBtn" class="scroll-to-top" title="Go to top">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <line x1="12" y1="19" x2="12" y2="5"></line>
    <polyline points="5 12 12 5 19 12"></polyline>
  </svg>
</button>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    try {
        const response = await fetch('../backend/get_products.php');
        const data = await response.json();
        
        if (data.success && data.products) {
            const grid = document.getElementById('featuredProductsGrid');
            
            // Take the first 4 products to feature as bestsellers
            const featuredProducts = data.products.slice(0, 4);
            
            grid.innerHTML = featuredProducts.map((product, index) => {
                let badge = '';
                if (index === 0) badge = '<span class="badge-new">New</span>';
                if (index === 1) badge = '<span class="badge-sale">Sale</span>';
                
                // Adjust path depending on what get_products.php returns
                const imgSrc = product.image_path ? `../${product.image_path}` : '../assets/placeholder.png';
                
                return `
                <div class="product-card">
                  <div class="product-img-wrap" style="height: 250px; display: flex; justify-content: center; align-items: center; overflow: hidden; background: #f9f9f9;">
                    <img src="${imgSrc}" alt="${product.name}" style="width: 100%; height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                    ${badge}
                    <div class="product-actions">
                     <button class="add-cart" style="width: 100%;" onclick="handleBuyNow(${product.product_id})">Buy Now</button>
                      <button class="add-cart" onclick="handleAddToCart(${product.product_id})" style="width: 100%;">Add to Cart</button>
                    </div>
                  </div>
                  <div class="product-tag">${product.category_name || 'Fragrance'}</div>
                  <div class="product-name">${product.name}</div>
                  <div class="product-price">$${parseFloat(product.price).toFixed(2)}</div>
                </div>
                `;
            }).join('');
        }
    } catch (error) {
        console.error("Failed to load featured products:", error);
    }
});

</script>

<script src="script.js"></script>
</body>
</html>