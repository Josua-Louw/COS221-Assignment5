<?php
require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompareIt - Product List</title>
    <link rel="stylesheet" href="css/products.css">
</head>
<body>
    <h1>Available Products</h1>
    
    <div class="product-controls">
        <input type="text" class="search-input" placeholder="Search products...">
        
        <div class="filter-section">
            <label for="brand">Filter by Brand:</label>
            <select class="brand-filter" id="brand">
                <option value="">All Brands</option>
            </select>
            
            <div class="price-range">
                <input type="number" class="min-price" placeholder="Min" min="0">
                <span>to</span>
                <input type="number" class="max-price" placeholder="Max" min="0">
            </div>
            
            <button class="apply-filters">Apply Filters</button>
            <button class="reset-filters">Reset</button>
        </div>
    </div>
    
    <div class="product-list"></div>
    
    <script src="scripts/products.js"></script>
</body>
</html>

<?php
require_once 'footer.php';
?>
