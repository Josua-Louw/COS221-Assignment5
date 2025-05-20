<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompareIt - Price Comparison</title>
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <header role="banner">
        <nav class="main-nav" aria-label="Main navigation">
            <div class="button-container">
                <a href="products.php" class="nav-link">Products</a>
                
                <div class="dropdown" aria-haspopup="true">
                    <button class="dropbtn" aria-expanded="false">Categories</button>
                    <ul class="dropdown-content" role="menu">
                        <?php
                        // You can replace this with dynamic categories from your database
                        $categories = ['Electronics', 'Clothing', 'Home & Garden', 'Books'];
                        foreach ($categories as $category) {
                            echo '<li role="none"><a href="products.php?category=' . urlencode($category) . '" role="menuitem">' . htmlspecialchars($category) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                
                <a href="stores.php" class="nav-link">Stores</a>
            </div>
            
            <div class="login-container" id="auth-buttons">
                <a href="login.php" class="auth-link" id="login-btn">Login</a>
                <a href="register.php" class="auth-link" id="register-btn">Register</a>
            </div>
        </nav>
    </header>

    <script src="scripts/login.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.dropdown');
            const dropbtn = document.querySelector('.dropbtn');
            
            dropbtn.addEventListener('click', function(e) {
                e.preventDefault();
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
                dropdown.classList.toggle('open');
            });
            
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropbtn.setAttribute('aria-expanded', 'false');
                    dropdown.classList.remove('open');
                }
            });
        });
    </script>
</body>
</html>
