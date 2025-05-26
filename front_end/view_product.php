<?php
session_start();

// TODO: finish code to connect to database and api and remove the placeholder

/*
include '../includes/db_connect.php';

// Validate product ID
if (!isset($_GET['prod_id'])) {
    echo "<p>Product ID not specified.</p>";
    include '../includes/footer.php';
    exit;
}

$prod_id = intval($_GET['prod_id']);
$stmt = mysqli_prepare($mysqli, "SELECT * FROM product WHERE prod_id = ?");
mysqli_stmt_bind_param($stmt, "i", $prod_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<p>Product not found.</p>";
    include '../includes/footer.php';
    exit;
}
*/

// Temporary placeholder
$product = [
    'title' => 'Test Product',
    'thumbnail' => 'https://via.placeholder.com/300',
    'brand_id' => 'TestBrand',
    'store_id' => 'TestStore',
    'price' => '49.99',
    'description' => 'This is a test product'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Product</title>
  <link rel="stylesheet" href="/front_end/css/view_product.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <section id="product-title">
    <h2><?php echo htmlspecialchars($product['title']); ?></h2>
  </section>

  <section id="product-gallery-info" style="display: flex; gap: 2rem;">
    <div id="product-images">
      <img id="main-image" src="<?php echo htmlspecialchars($product['thumbnail']); ?>" alt="Product Image" width="300">
      <div id="thumbnail-container"></div>
    </div>

    <div id="product-details">
      <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand_id']); ?></p>
      <p><strong>Store:</strong> <?php echo htmlspecialchars($product['store_id']); ?> <button id="follow-store-btn">Follow</button></p>
      <p><strong>Price:</strong> R<?php echo htmlspecialchars($product['price']); ?></p>
      <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
      <p><strong>Rating:</strong> <span id="rating-stars">Loading...</span></p>
      <button id="wishlist-btn">❤️ Add to Wishlist</button>
    </div>
  </section>

  <section id="reviews">
    <h3>User Reviews</h3>
    <div id="review-list">Loading reviews</div>

    <form id="review-form">
      <textarea id="review-text" placeholder="Write your review..." required></textarea>
      <select id="rating" required>
        <option value="">Rate</option>
        <option value="5">⭐⭐⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="2">⭐⭐</option>
        <option value="1">⭐</option>
      </select>
      <button type="submit">Submit Review</button>
    </form>
  </section>
</main>

<script src="/front_end/scripts/view_product.js"></script>

<?php include 'footer.php'; ?>
