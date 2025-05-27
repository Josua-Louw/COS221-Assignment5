<?php
require_once 'header.php';
?>

<link rel="stylesheet" href="css/user_settings.css">

<div class="settings-container">
  <h2>Your Settings</h2>
  <form id="settingsForm">
    <div class="form-group theme-toggle">
        <label for="theme">Preferred Theme:</label>
        <select id="theme" name="theme">
            <option value="light">Light</option>
            <option value="dark">Dark</option>
        </select>
    </div>

    <div class="form-group">
        <label for="price-filter">Price Range:</label>
        <select id="price-filter" name="price">
            <option value="all">All</option>
            <option value="0-40">0–39.99</option>
            <option value="40-80">40–79.99</option>
            <option value="80-120">80–119.99</option>
            <option value="120-200">120–199.99</option>
            <option value="200-400">200–399.99</option>
            <option value="400-600">400–599.99</option>
            <option value="600-1000">600–999.99</option>
        </select>
    </div>

    <?php if (isset($_SESSION['apikey'])): ?>
    <div class="form-group">
      <label for="current-email">Current Email<span class="required">*</span>:</label>
      <input type="email" id="current-email" name="current_email" required>
    </div>
    <div class="form-group">
      <label for="current-password">Current Password<span class="required">*</span>:</label>
      <input type="password" id="current-password" name="current_password" required>
    </div>

    <div class="form-group">
      <label for="new-email">New Email<span class="required">*</span>:</label>
      <input type="email" id="new-email" name="new_email" required>
    </div>
    <div class="form-group">
      <label for="new-password">New Password<span class="required">*</span>:</label>
      <input type="password" id="new-password" name="new_password" required>
    </div>
    <?php else: ?>
      <p class="note">Log in to change your email or password.</p>
    <?php endif; ?>

    <button id="save-preferences-btn" type="submit">Save Settings</button>
    <div id="settingsMessage" class="error-message"></div>
  </form>
</div>

<script src="scripts/user_settings.js"></script>

<?php
require_once 'footer.php';
?>
