<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <link rel = "stylesheet" href = "css/header.css">
</head>
<body>
    
    <div class ="button-container">
        <a href = "products.php">Products</a>
        <!--Need a script here that based on the input it applies the filter to the products page-->
        <li class="dropdown">
                <a href="#" class="dropbtn">Categories</a>
                <div class="dropdown-content">
                    <a href="#">Category 1</a>
                    <a href="#">Category 2</a>
                    <a href="#">Category 3</a>
                    <a href="#">Another Category</a>
                </div>
            </li>
        <a href = "stores.php">Stores</a>
    </div>
    <div class = "login-container" id = "auth-buttons">
        
    </div>
    <script src = "scripts/login.js"></script>
</body>
</html>