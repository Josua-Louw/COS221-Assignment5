<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="description" content="CompareIt - Compare prices across multiple stores to find the best deals">
  <title>CompareIt - Price Comparison</title>
  <link rel="stylesheet" href="css/header.css">
  <style>
    /* your existing small overrides */
    .auth-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .auth-links {
      display: flex;
      gap: 10px;
    }
    .theme-toggle {
      margin-left: 8px;
    }
  </style>
</head>
<body>
  <header role="banner">
    <nav class="main-nav" aria-label="Main navigation">
      
      <!-- LOGO (slightly reduced padding/font in CSS) -->
      <div class="logo-container">
        <a href="index.php" class="logo-link" aria-label="CompareIt Home">
         
           <img src="img/logo.png" alt="Logo" style="width: 100px; height: auto;">
        </a>
      </div>
      
      <!-- CENTERED: Products / Stores -->
      <div class="button-container">
       

         <a href="Dashbored.php"   class="nav-link">Dashbored</a>
        <a href="products.php" class="nav-link">Products</a>
        <a href="stores.php"   class="nav-link">Stores</a>
      </div>
      
      <!-- RIGHT: Auth links + Theme switch -->
      <div class="auth-container" id="auth-buttons">
        <?php if (isset($_SESSION['user'])): ?>
          <div class="user-dropdown" aria-haspopup="true">
            <button class="user-btn" aria-expanded="false" aria-controls="user-menu">
              <span class="user-avatar" aria-hidden="true">👤</span>
              <span class="username"><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Account') ?></span>
              <span class="dropdown-arrow" aria-hidden="true">▼</span>
            </button>
            <ul class="user-menu" id="user-menu" role="menu">
              <li role="none"><a href="account.php"       role="menuitem">My Account</a></li>
              <li role="none"><a href="user_settings.php" role="menuitem">Settings</a></li>
              <li role="none"><a href="logout.php"        role="menuitem">Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <div class="auth-links">
            <a href="login.php"    class="auth-link" id="login-btn">Login</a>
            <a href="register.php" class="auth-link" id="register-btn">Register</a>
            <a href="user_settings.php" class="auth-link">Settings</a>
          </div>
        <?php endif; ?>
        
        <!-- THEME SLIDER + LABEL -->
        <label class="theme-switch">
          <input type="checkbox" id="themeToggle" aria-label="Toggle dark mode">
          <span class="slider"></span>
          <span id="themeLabel"></span> <!-- will show “Light” or “Dark” -->
        </label>
      </div>
    </nav>
  </header>

  <script src="scripts/header.js"></script>
</body>
</html>
