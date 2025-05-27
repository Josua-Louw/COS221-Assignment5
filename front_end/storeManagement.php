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
        <h1>Your Stores</h1>
        
        

        <div class="store-list">
            
        </div>
    </div>
    <button onclick = "openPopup()">Add Store</button>
    <div class="popup-bg" id="popup">
        <div class="popup-form">
            <form method="POST" action="storeManagement.php">
                <h3>Add Store</h3>
                <input type="text" name="storeName" placeholder="Name" required>
                <input type="text" name="store_url" placeholder="URL" required>
                <input type="text" name="storeReg" placeholder="Registration Number" required>
            <select class="button-group" id = "filter-dropdown">
                <option value="All">All Types</option>
                <option value="Online-only">Online</option>
                <option value="Physical-only">Physical</option>
                <option value="Wholesale">Wholesale</option>
                <option value="Omnichannel">Omnichannel</option>
            </select>
                <button class = "submit-btn" type="submit" onclick = "registerStoreOwner()">Submit</button>
                <button class = "cancel-btn" type="button" onclick="closePopup()">Cancel</button>
            </form>
        </div>
    </div>

    <script src = "scripts/storeManager.js"></script>
</body>
</html>
<?php include 'footer.php';?>
