<?php
require_once 'header.php';
// Fetch existing settings from the database or user session here
// just an example: $settings = getUserSettings($_SESSION['user_id']);
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
      <label for="stores">Preferred Stores:</label>
      <select id="stores" name="stores[]" multiple>
        <option value="Store A">Store A</option>
        <option value="Store B">Store B</option>
        <option value="Store C">Store C</option>
        <!-- …need to be generated  dynamically -->
      </select>
    </div>

    <!-- any otheruser‐settings fields? -->

    <button type="submit">Save Settings</button>
    <div id="settingsMessage" class="error-message"></div>
  </form>
</div>

<script src="js/user_settings.js"></script>

<?php
require_once 'footer.php';
?>
