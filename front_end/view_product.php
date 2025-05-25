<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompareIt - View Product</title>
    <link rel="stylesheet" href="css/view_product.css">
    <link rel="stylesheet" href="css/header.css"> 
    <script src="scripts/header.js" defer></script> 
</head>
<body>
<!--the html in header.php was not working along with the html in this php file so i did not include the header and just made it a part of my html-->
    <header role="banner">
        <nav class="main-nav" aria-label="Main navigation">
            <div class="button-container">
                <a href="products.php" class="nav-link">Products</a>

                <div class="dropdown" aria-haspopup="true">
                    <button class="dropbtn" aria-expanded="false">Categories</button>
                    <ul class="dropdown-content" role="menu">
                        <?php
                        $categories = ['Electronics', 'Clothing', 'Home & Garden', 'Books'];
                        foreach ($categories as $category) {
                            echo '<li role="none"><a href="products.php?category=' . urlencode($category) . '" role="menuitem">' . htmlspecialchars($category) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

                <a href="stores.php" class="nav-link">Stores</a>
                <a href="user_settings.php" class="nav-link">Settings</a>
            </div>

            <div class="login-container" id="auth-buttons">
                <a href="login.php" class="auth-link" id="login-btn">Login</a>
                <a href="register.php" class="auth-link" id="register-btn">Register</a>
            </div>
        </nav>
    </header>

    <!--product dsplay section -->
    <div class="product-container">
        <h1 id="product-title">Loading...</h1>
        <div class="image-section">
            <img id="main-image" src="https://via.placeholder.com/300" alt="Main Product Image">
            <div id="thumbnail-container" class="thumbnail-row"></div>
        </div>
        <p id="product-price" class="price">R0.00</p>
        <p id="product-description" class="description">Loading description...</p>
        <div id="rating-stars" class="rating">Rating: -</div>
        <a id="external-link" href="#" target="_blank" class="external-link-btn" style="display: none;">View on Store Website</a>

    </div>

    <script src="scripts/view_product.js"></script>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>


