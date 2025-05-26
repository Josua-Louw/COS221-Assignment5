<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompareIt - View Product</title>
    <link rel="stylesheet" href="css/view_product.css">
    <link rel="stylesheet" href="css/header.css">
    <script src="scripts/header.js" defer></script>
</head>
<body>
    <?php
require_once 'header.php';
?>

    <main>
        <div class="product-container">
            <h1 id="product-title">Loading...</h1>
            <div class="image-section">
                <img id="main-image" src="https://via.placeholder.com/300" alt="Main Product Image">
                <div id="thumbnail-container" class="thumbnail-row"></div>
            </div>
            <p id="product-price" class="price" class="middle">R0.00</p>
            <p id="product-description" class="description">Loading description...</p>
            <!-- <div id="rating-stars" class="rating">Rating: -</div> -->
            <a id="external-link" href="#" target="_blank" class="external-link-btn" style="display: none;">View on Store Website</a>
        </div>

        <div class="product-container">
            <h3 class="All_Rating">All the ratings off the products</h3>
        </div>

        <div class="product-container">
            <button class="Add_rating">Add a rating</button>
        </div>

    </main>
    <script src="scripts/view_product.js"></script>
   <?php
require_once 'footer.php';
?>
</body>
</html>
