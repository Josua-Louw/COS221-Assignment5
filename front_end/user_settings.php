<?php
require_once 'header.php';
?>

<link rel="stylesheet" href="css/user_settings.css">

<div class="settings-container"
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

    <div class="form-group">
        <label for="email">New Email (optional):</label>
        <input type="email" id="email" name="email" placeholder="newemail@example.com">
    </div>

    <div class="form-group">
      <label for="password">New Password (optional):</label>
      <input type="password" id="password" name="password" placeholder="New password">
    </div>

    <button id="save-preferences-btn" type="submit">Save Settings</button>
  <div id="settingsMessage" class="error-message"></div>
  </form>
</div>

<script src="js/user_settings.js"></script>

<?php
require_once 'footer.php';
?>
