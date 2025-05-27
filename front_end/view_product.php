

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
                <img id="main-image">
                <div id="thumbnail-container" class="thumbnail-row"></div>
            </div>
            <p id="product-price" class="price" class="middle">R0.00</p>
            <div class="average-rating-container">
                <p id="average-rating" class="rating">Loading average rating...</p>
            </div>
            <p id="product-description" class="description"></p>
            <a id="external-link" href="#" target="_blank" class="external-link-btn" style="display: none;">View on Store Website</a>
        </div>

        <div class="product-container">
            <h3 class="All_Rating">All the ratings off the products</h3>
        </div>

        <div class="product-container">
            <button class="Add_rating" id="rating_button_submit">Add a rating</button>
        </div>

        <div class="product-container-for-rating" id="ratingForm" style="display: none;">
    <label for="rating">Rate this product:</label><br>
    <select id="rating" name="rating">
        <option value="" disabled selected>Select a rating</option>
        <option value="1">1 - Poor</option>
        <option value="2">2 - Fair</option>
        <option value="3">3 - Good</option>
        <option value="4">4 - Very Good</option>
        <option value="5">5 - Excellent</option>
    </select><br><br>

    <label for="comment">Your Comment:</label><br>
    <textarea id="comment" rows="4" cols="40" placeholder="Write your thoughts..."></textarea><br><br>

    <button type="submit" id="submitRating">Submit</button>
</div>

    </main>
    <script src="scripts/view_product.js"></script>
   <?php
require_once 'footer.php';
?>
</body>
</html>
