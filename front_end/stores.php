<?php
require_once 'header.php';
require_once __DIR__ . '/../api/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompareIt - Stores</title>
      <link rel="stylesheet" href="css/stores.css">
</head>
<body>
    <div class="stores-container">
        <h1>Available Stores</h1>
        
        <div class="search-container">
            <input type="text" class = "search-input" id="search-input" placeholder="Search stores..." onkeyup = searchStores()>
            <select class="search-input">
                <option value="">All Types</option>
                <option value="Online">Online</option>
                <option value="Physical">Physical</option>
            </select>
        </div>

        <div class="store-list">
            
        </div>
    </div>
   <script src = "scripts/stores.js"></script>
</body>
</html>

<?php
require_once 'footer.php';
?>
