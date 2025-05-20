<?php
Product Listing Page
require_once 'config.php'; 

function fetchProducts() {
    $url = 'api.php';
    $data = ['type' => 'GetAllProducts'];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    return json_decode($result, true);
}

$products = fetchProducts()['data'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompareIt - Product List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .product { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .product img { max-width: 150px; max-height: 150px; }
        .price { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Available Products</h1>
    
    <?php if (!empty($products)): ?>
        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <?php if (!empty($product['thumbnail'])): ?>
                        <img src="<?= htmlspecialchars($product['thumbnail']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($product['title']) ?></h3>
                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <a href="<?= htmlspecialchars($product['product_link']) ?>" target="_blank">View Product</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</body>
</html>
