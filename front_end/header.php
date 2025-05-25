<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CompareIt - Compare prices across multiple stores to find the best deals">
    <title>CompareIt - Price Comparison</title>
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <header role="banner">
        <nav class="main-nav" aria-label="Main navigation">
            <div class="logo-container">
                <a href="index.php" class="logo-link" aria-label="CompareIt Home">
                    <h1>CompareIt</h1>
                </a>
            </div>
            
            <div class="button-container">
                <a href="products.php" class="nav-link" aria-current="page">Products</a>
                </div>
                
                <a href="stores.php" class="nav-link">Stores</a>
            </div>
            
            <div class="auth-container" id="auth-buttons">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-dropdown" aria-haspopup="true">
                        <button class="user-btn" aria-expanded="false" aria-controls="user-menu">
                            <span class="user-avatar" aria-hidden="true">👤</span>
                            <span class="username"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Account') ?></span>
                            <span class="dropdown-arrow" aria-hidden="true">▼</span>
                        </button>
                        <ul class="user-menu" id="user-menu" role="menu">
                            <li role="none"><a href="account.php" role="menuitem">My Account</a></li>
                            <li role="none"><a href="logout.php" role="menuitem">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="auth-link" id="login-btn">Login</a>
                    <a href="register.php" class="auth-link" id="register-btn">Register</a>
                <?php endif; ?>
                
                <button id="themeToggleBtn" class="theme-toggle" aria-label="Toggle dark mode">
                    <span class="theme-icon" aria-hidden="true"></span>
                    <span class="theme-text">Theme</span>
                </button>
            </div>
        </nav>
    </header>
                    <script src="scripts/header.js"></script>

    <main>
