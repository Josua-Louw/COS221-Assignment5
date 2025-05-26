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
    <?php require_once '../front_end/header.php'; ?>

    <main>
        <div class="product-container">
            <h1 id="product-title">Loading...</h1>
            <div class="image-section">
                <img id="main-image" src="https://via.placeholder.com/300" alt="Main Product Image">
            </div>
            <p id="product-price" class="price">R0.00</p>
            <p id="product-description" class="description">Loading description...</p>
            <div id="rating-stars" class="rating">Rating: -</div>
            <a id="external-link" href="#" target="_blank" class="external-link-btn" style="display: none;">View on Store Website</a>

            <!--reviews Section -->
            <div id="reviews-section">
              <h3>Customer Reviews</h3>
              <div id="review-list">Loading reviews...</div>
            </div>
            <div id="submit-review">
              <h4>Leave a Review</h4>
              <label for="rating-input">Rating (1 to 5):</label>
              <input type="number" id="rating-input" min="1" max="5" step="1" required>
              <br>
              <label for="comment-input">Comment:</label>
              <textarea id="comment-input" rows="3" required></textarea>
              <br>
              <button id="submit-review-btn">Submit Review</button>
              <button id="update-review-btn" style="display: none;">Update Review</button>
              <button id="delete-review-btn" style="display: none;">Delete Review</button>
            </div>
        </div>
    </main>

    <script src="scripts/view_product.js"></script>
    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
