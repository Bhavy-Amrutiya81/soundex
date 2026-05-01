<?php
/**
 * Shared Header Navigation
 * Included in all frontend pages.
 * CSS is embedded here to guarantee it loads regardless of page-specific styles.
 */
$current_page = basename($_SERVER['PHP_SELF']);
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!-- ===================== SOUNDEX GLOBAL NAV ===================== -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
/* ── SOUNDEX HEADER — embedded in header.php so it always wins ── */

/* Ensure body never hides the fixed nav */
body { padding-top: 80px !important; }

/* ── Nav container ── */
nav {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    background-color: #22313f !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4) !important;
    z-index: 999999 !important;
    height: 80px !important;
    display: flex !important;
    align-items: center !important;
    box-sizing: border-box !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* ── Nav list ── */
nav ul {
    list-style: none !important;
    margin: 0 !important;
    padding: 0 5% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
    height: 100% !important;
}

/* ── Logo ── */
.logo { margin-right: auto !important; }

.logo a {
    text-decoration: none !important;
    display: flex !important;
    align-items: center !important;
}

.logo h1 {
    color: #ffffff !important;
    font-family: 'Playfair Display', 'Georgia', serif !important;
    font-size: 32px !important;
    font-weight: 700 !important;
    letter-spacing: 3px !important;
    text-transform: uppercase !important;
    transition: color 0.3s ease !important;
    margin: 0 !important;
    padding: 0 !important;
}

.logo h1 span { color: #5dade2 !important; }

.logo h1:hover        { color: #5dade2 !important; }
.logo h1:hover span   { color: #ffffff !important; }

/* ── Nav items ── */
nav ul li {
    list-style: none !important;
    display: inline-block !important;
}

/* ── Nav links ── */
nav ul li a {
    color: #ffffff !important;
    padding: 0 15px !important;
    text-align: center !important;
    text-transform: uppercase !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    font-size: 13px !important;
    letter-spacing: 1px !important;
    font-family: 'Poppins', sans-serif !important;
    position: relative !important;
    height: 80px !important;
    line-height: 80px !important;
    display: block !important;
    transition: color 0.3s ease !important;
}

nav ul li a:hover { color: #5dade2 !important; }

/* Animated underline */
nav ul li a::before {
    content: '' !important;
    position: absolute !important;
    bottom: 18px !important;
    left: 50% !important;
    transform: translateX(-50%) !important;
    width: 0 !important;
    height: 2px !important;
    background-color: #5dade2 !important;
    transition: width 0.3s ease !important;
}

nav ul li a:hover::before,
nav ul li a.active::before { width: 70% !important; }

/* Active page link */
nav ul li a.active { color: #5dade2 !important; }

/* ── Cart icon ── */
.cart-icon {
    position: relative !important;
    margin-left: 20px !important;
    padding: 0 20px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    color: #fff !important;
    height: 80px !important;
    line-height: 80px !important;
    font-size: 20px !important;
}

.cart-icon:hover { color: #5dade2 !important; transform: translateY(-2px) !important; }

.cart-count {
    position: absolute !important;
    top: 14px !important;
    right: 8px !important;
    background-color: #e74c3c !important;
    color: #fff !important;
    border-radius: 50% !important;
    width: 18px !important;
    height: 18px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 10px !important;
    font-weight: bold !important;
}

.cart-icon.empty .cart-count { display: none !important; }

/* ── Responsive ── */
@media (max-width: 1200px) {
    nav ul { padding: 0 20px !important; }
    nav ul li a { padding: 0 10px !important; font-size: 12px !important; }
}

@media (max-width: 992px) {
    nav { height: auto !important; padding: 10px 0 !important; }
    body { padding-top: 0 !important; }
    nav ul { flex-direction: column !important; padding: 0 !important; }
    nav ul li { width: 100% !important; }
    nav ul li a { height: 50px !important; line-height: 50px !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
    .logo { margin: 0 0 10px 0 !important; text-align: center !important; width: 100% !important; }
    .logo a { justify-content: center !important; }
    .cart-icon { margin: 10px 0 !important; justify-content: center !important; }
}
</style>

<nav style="position:fixed!important;top:0!important;left:0!important;right:0!important;width:100%!important;background-color:#22313f!important;height:80px!important;display:flex!important;align-items:center!important;z-index:999999!important;box-shadow:0 4px 15px rgba(0,0,0,0.4)!important;box-sizing:border-box!important;margin:0!important;padding:0!important;">
    <ul>
        <div class="logo">
            <a href="../pages/home.php">
                <h1>Soun<span>Dex</span></h1>
            </a>
        </div>
        <li><a href="../pages/home.php"      class="<?php echo ($current_page == 'home.php')       ? 'active' : ''; ?>">Home</a></li>
        <li><a href="../pages/Gallery.php"   class="<?php echo ($current_page == 'Gallery.php')    ? 'active' : ''; ?>">Gallery</a></li>
        <li><a href="../pages/faqs.php"      class="<?php echo ($current_page == 'faqs.php')       ? 'active' : ''; ?>">FAQs</a></li>
        <li><a href="../pages/services.php"  class="<?php echo ($current_page == 'services.php')   ? 'active' : ''; ?>">Services</a></li>
        <li><a href="../pages/contact us.php" class="<?php echo ($current_page == 'contact us.php') ? 'active' : ''; ?>">Contact</a></li>
        <li><a href="../pages/about.php"     class="<?php echo ($current_page == 'about.php')      ? 'active' : ''; ?>">About</a></li>

        <?php if ($isLoggedIn): ?>
            <li><a href="../pages/history.php" class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">History</a></li>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="../admin/index.php" style="color: #f50057 !important; font-weight: bold !important;">Admin Panel</a></li>
            <?php endif; ?>
            <li><a href="#" style="color: #5dade2 !important; font-weight: bold !important;"><?php echo htmlspecialchars($username); ?></a></li>
            <li><a href="../logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="../pages/login.php"  class="<?php echo ($current_page == 'login.php')  ? 'active' : ''; ?>">Login</a></li>
            <li><a href="../pages/signup.php" class="<?php echo ($current_page == 'signup.php') ? 'active' : ''; ?>">Sign Up</a></li>
        <?php endif; ?>

        <li>
            <a href="../pages/checkout.php"
               class="cart-icon <?php echo ($current_page == 'checkout.php') ? 'active' : ''; ?>"
               id="cartIcon">
                🛒
                <span class="cart-count" id="cartCount">0</span>
            </a>
        </li>
    </ul>
</nav>

<script>
    /**
     * Updates the cart count badge in the header.
     */
    function updateCartCount() {
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        const cartCountEl = document.getElementById('cartCount');
        const cartIconEl  = document.getElementById('cartIcon');
        
        if (!isLoggedIn) {
            if (cartCountEl) {
                cartCountEl.textContent = '0';
                if (cartIconEl) cartIconEl.classList.add('empty');
            }
            return;
        }

        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((total, item) => total + (item.quantity || 1), 0);
        if (cartCountEl) {
            cartCountEl.textContent = totalItems;
            cartIconEl.classList.toggle('empty', totalItems === 0);
        }
    }
    document.addEventListener('DOMContentLoaded', updateCartCount);
    window.addEventListener('storage', updateCartCount);
</script>
