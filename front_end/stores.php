
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
            <input type="text" class = "search-input" id="filter-input" placeholder="Search stores...">
            <select class="search-input" id = "filter-dropdown">
                <option value="All">All Types</option>
                <option value="Online-only">Online</option>
                <option value="Physical-only">Physical</option>
                <option value="Wholesale">Wholesale</option>
                <option value="Omnichannel">Omnichannel</option>
            </select>

            <select class="Follow_products" id="Follow_products">
            <option value="">Showing Stores</option>
            <option value="1">Stores you follow</option>
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
