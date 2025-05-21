<?php
require_once 'header.php';
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
            <input type="text" class="search-input" placeholder="Search stores...">
            <button class="search-btn">Search</button>
            <select class="search-input">
                <option value="">All Types</option>
                <option value="Online">Online</option>
                <option value="Physical">Physical</option>
            </select>
        </div>

        <div class="store-list">
            <?php
            $stores = [
                [
                    'store_id' => 1,
                    'store_name' => 'Tech Haven',
                    'store_url' => 'https://techhaven.com',
                    'type' => 'Online',
                    'logo' => 'https://via.placeholder.com/150?text=Tech+Haven'
                ],
                [
                    'store_id' => 2,
                    'store_name' => 'Urban Gadgets',
                    'store_url' => 'https://urbangadgets.co.za',
                    'type' => 'Physical',
                    'logo' => 'https://via.placeholder.com/150?text=Urban+Gadgets'
                ],
                [
                    'store_id' => 3,
                    'store_name' => 'Electro World',
                    'store_url' => 'https://electroworld.com',
                    'type' => 'Online',
                    'logo' => 'https://via.placeholder.com/150?text=Electro+World'
                ]
            ];

            foreach ($stores as $store): 
            ?>
                <div class="store-card">
                    <img src="<?= htmlspecialchars($store['logo']) ?>" alt="<?= htmlspecialchars($store['store_name']) ?>" class="store-logo">
                    
                    <div class="store-info">
                        <h2 class="store-name"><?= htmlspecialchars($store['store_name']) ?></h2>
                        <span class="store-type"><?= htmlspecialchars($store['type']) ?></span>
                        <p>Explore a wide range of products at <?= htmlspecialchars($store['store_name']) ?>.</p>
                        
                        <div class="store-actions">
                            <a href="<?= htmlspecialchars($store['store_url']) ?>" target="_blank" class="btn btn-visit">Visit Store</a>
                            <button class="btn btn-follow" data-store-id="<?= $store['store_id'] ?>">Follow</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
   <script src = "scripts/stores.js"></script>
</body>
</html>

<?php
require_once 'footer.php';
?>
