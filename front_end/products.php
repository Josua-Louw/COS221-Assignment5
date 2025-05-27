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
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <?php if (isset($products)): ?>
    <script>
        window.initialProducts = <?php echo json_encode($products); ?>;
    </script>
    <?php endif; ?>
</head>
<body>
    <div class="products-container">
        <h1>Available Products</h1>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search products...">
            
            <div class="filter-section">

            <select class="rating-filter" id="rating">
            <option value="">All Ratings</option>
            <option value="4">4 Stars & Up</option>
            <option value="3">3 Stars & Up</option>
            <option value="2">2 Stars & Up</option>
            <option value="1">1 Star & Up</option>
            </select>
                
                <div class="price-range">
                    <input type="number" class="min-price" placeholder="Min" min="0">
                    <span>to</span>
                    <input type="number" class="max-price" placeholder="Max" min="0">
                </div>
                
                <button class="apply-filters btn-primary">Apply Filters</button>
                <button class="reset-filters btn-secondary">Reset</button>

                <select class="Follow_products" id="Follow_products">
                <option value="">Showing Products</option>
                <option value="4">Products you follow</option>
                </select>

            </div>
        </div>

        <div class="product-list">
            <?php
            //optional server side rendering
            if (isset($products) && !empty($products)) {
                foreach ($products as $product) {
                    $avgRating = $product['average_rating'] ?? null;
                    $ratingDisplay = $avgRating ? number_format($avgRating, 1) . '/5' : 'Not rated yet';
                    
                    echo '<div class="product" data-id="' . htmlspecialchars($product['product_id']) . '">';
                    echo '    <img src="' . htmlspecialchars($product['thumbnail'] ?? '') . '" alt="' . htmlspecialchars($product['title']) . '">';
                    echo '    <div class="product-info">';
                    echo '        <h3>' . htmlspecialchars($product['title']) . '</h3>';
                    echo '        <div class="product-meta">';
                    echo '            <p class="price">R' . number_format($product['price'], 2) . '</p>';
                    echo '            <div class="rating-container">';
                    
                    if ($avgRating) {
                        echo generateStarRating($avgRating);
                        echo '<span class="rating-text">' . $ratingDisplay . '</span>';
                    } else {
                        echo '<div class="no-rating">' . $ratingDisplay . '</div>';
                    }
                    
                    echo '            </div>';
                    echo '        </div>';
                    echo '        <button class="view-product">View Product</button>';
                    echo '    </div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
    
    <script src="scripts/products.js"></script>
</body>
</html>

<?php
require_once 'footer.php';

function generateStarRating($rating) {
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
    
    $output = '<div class="star-rating">';
    
    for ($i = 0; $i < $fullStars; $i++) {
        $output .= '<i class="fas fa-star"></i>';
    }
    
    if ($hasHalfStar) {
        $output .= '<i class="fas fa-star-half-alt"></i>';
    }
    
    for ($i = 0; $i < $emptyStars; $i++) {
        $output .= '<i class="far fa-star"></i>';
    }
    
    $output .= '</div>';
    
    return $output;
}
?>
