
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="description" content="CompareIt - Compare prices across multiple stores to find the best deals">
  <title>CompareIt - Price Comparison</title>
  <link rel="stylesheet" href="css/header.css">
  <style>
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
      
    <div class="logo-container">
      <a href="index.php" class="logo-link" aria-label="CompareIt Home">
        <img src="img/logo-light.png" alt="CompareIt" id="logoLight">
        <img src="img/logo-dark.png"  alt="CompareIt" id="logoDark">
      </a>
    </div>
      
      <div class="button-container">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
        <a href="products.php" class="nav-link">Products</a>
        <a href="stores.php"   class="nav-link">Stores</a>
        <a href="manage_stores.php"  class="nav-link">Manage Stores</a>
      </div>
      
      <div class="auth-container" id="auth-buttons">
          <?php if (isset($_SESSION['apikey'])): ?> 
            <a href="logout.php"    class="auth-link" id="logout-btn">Logout</a>
            <a href="user_settings.php" class="auth-link">Settings</a>
            </ul>
          </div>
        <?php else: ?>
          <div class="auth-links">
            <a href="login.php"    class="auth-link" id="login-btn">Login</a>
            <a href="register.php" class="auth-link" id="register-btn">Register</a>
            <a href="user_settings.php" class="auth-link">Settings</a>
          </div>
        <?php endif; ?>
        
        <label class="theme-switch">
          <input type="checkbox" id="themeToggle" aria-label="Toggle dark mode">
          <span class="slider"></span>
          <span id="themeLabel"></span> 
        </label>
      </div>
    </nav>
  </header>

  <script src="scripts/header.js"></script>
</body>
</html>
