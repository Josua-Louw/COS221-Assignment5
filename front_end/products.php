<?php
require_once 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompareIt - Product List</title>
    <link rel = "stylesheet" href = "css/products.css">
</head>
<body>
    <h1>Available Products</h1>
    <div class="product-list">
        <?php
        // Mock products data
        $products = [
            [
                'title' => 'Wireless Headphones',
                'price' => 99.99,
                'thumbnail' => 'https://m.media-amazon.com/images/I/61Cj6f5rRaL._AC_UF1000,1000_QL80_.jpg',
                'description' => 'Premium noise-cancelling headphones with 30-hour battery life. Features Bluetooth 5.0 and built-in microphone.',
                'product_link' => '#'
            ],
            [
                'title' => 'Smartphone X Pro',
                'price' => 799.00,
                'thumbnail' => 'https://www.apple.com/newsroom/images/product/iphone/standard/Apple-iPhone-14-Pro-iPhone-14-Pro-Max-hero-220907.jpg.landing-big_2x.jpg',
                'description' => 'Latest flagship smartphone with 6.5" OLED display, 256GB storage, and triple camera system.',
                'product_link' => '#'
            ],
            [
                'title' => 'Portable Bluetooth Speaker',
                'price' => 59.50,
                'thumbnail' => 'https://www.jbl.com/dw/image/v2/BFND_PRD/on/demandware.static/-/Sites-masterCatalog_Harman/default/dw1e8d0b3e/JBL_Flip6_Hero_Blue_001.png?sw=535&sh=535',
                'description' => 'Waterproof speaker with 12 hours playtime and powerful bass. Perfect for outdoor adventures.',
                'product_link' => '#'
            ]
        ];

        foreach ($products as $product): 
        ?>
            <div class="product">
                <?php if (!empty($product['thumbnail'])): ?>
                    <img src="<?= htmlspecialchars($product['thumbnail']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                <?php endif; ?>
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['title']) ?></h3>
                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <a href="<?= htmlspecialchars($product['product_link']) ?>" class="view-product" target="_blank">View Product</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>

<?php
require_once 'footer.php';
