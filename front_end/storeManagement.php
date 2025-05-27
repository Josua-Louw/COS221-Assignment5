<?php include 'header.php';?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>CompareIt - StoreManage</title>
    <link rel = "stylesheet" href = "css/storeManagement.css">
    
</head>
<body>
    <div class="stores-container">
        <h1>Your Store</h1>
        <button onclick = "openPopup()">Add Store</button>
        

        <div class="store-list">
            
        </div>
    </div>
    
    <div class="popup-bg" id="popup">
        <div class="popup-form">
            <form method="POST" action="storeManagement.php">
                <h3>Add Store</h3>
                <input type="text" name="store_name" id="store_name" placeholder="Name" required>
                <input type="text" name="store_url" id="store_url" placeholder="URL" required>
                <input type="text" name="store_reg" id="store_reg" placeholder="Registration Number" required>
            <select class="button-group" id = "filter-dropdown">
                <option value="All">All Types</option>
                <option value="Online-only">Online</option>
                <option value="Physical-only">Physical</option>
                <option value="Wholesale">Wholesale</option>
                <option value="Omnichannel">Omnichannel</option>
            </select>
                <button class = "submit-btn" id = "submit-btn" type="submit">Submit</button>
                <button class = "cancel-btn" type="button" onclick="closePopup()">Cancel</button>
            </form>
        </div>
    </div>
    <div class = "product-list"></div>
    <div class="popup-bg" id="product-popup">
    <div class="popup-form" id="product-popup-form">
        <!-- Form content will be injected by JS -->
    </div>
</div>
    <div class="popup-bg" id="delete-popup">
        <div class="popup-form">
            <h3>Delete Product</h3>
            <p>Are you sure you want to delete this product?</p>
            <div class="button-group">
                <button class="submit-btn" id="confirm-delete-btn">Yes, Delete</button>
                <button class="cancel-btn" id="cancel-delete-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script src = "scripts/storeManager.js"></script>
</body>
</html>
<?php include 'footer.php';?>
