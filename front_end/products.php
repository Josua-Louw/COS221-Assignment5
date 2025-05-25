<?php
require_once 'header.php';
require_once __DIR__ . '/../api/config.php';

//TODO: this section is temporary and will be removed once once i make it work with its JS file. (working on it)
$conn = Database::instance()->getConnection();

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompareIt - Product List</title>
    <link rel="stylesheet" href="css/products.css">
</head>
<body>
    <h1>Available Products</h1>
    <div class="product-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product">
                    <?php if (!empty($product['thumbnail'])): ?>
                        <img src="<?= htmlspecialchars($product['thumbnail']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                    <?php endif; ?>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['title']) ?></h3>
                        <p class="price">R<?= number_format($product['price'], 2) ?></p>
                        <p><?= htmlspecialchars($product['description'] ?? 'No description available.') ?></p>
                        <a href="<?= htmlspecialchars($product['product_link'] ?? '#') ?>" class="view-product" target="_blank">View Product</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found in Database</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php require_once 'footer.php'; ?>
