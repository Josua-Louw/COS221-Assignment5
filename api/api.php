<?php

/* The Null Pointers - COS221 Assignment 5

    Progress:   [-1] - Unfinished
                [0] - Untested
                [1] - Main functionality tested
                [2] - Edge cases tested
                [3] - Edge cases tested + secure

    Types Handled:

    Login               [0]
    Register            [0]
    GetAllProducts      [1]
    AddProduct          [0]
    DeleteProduct       [0]
    EditProduct         [0]
    GetFilteredProducts [0]
    SubmitRating        [0]
    GetRatings          [0]
    DeleteRating        [0]
    EditRating          [0]
    GetStores           [0]
    GetUsersStores      [0]
    Follow              [0]
    GetFollowing        [0] *
    Unfollow            [0]
    RegisterStoreOwner  [0]
    SavePreference      [0]
    AddBrand            [-1]
    RemoveBrand         [-1]
    GetBrands           [0]

*/

//TODO:
//  Test All Types


require_once 'config.php';

header('Content-Type: application/json');

$_POST = json_decode(file_get_contents("php://input"), true);

//creates the connection
try {
    $conn = Database::instance()->getConnection();
} catch (Exception $e) {
    error_log("Register error: " . $e);
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error",
        "Type Handler" => "creating connection",
        "API Line" => __LINE__
    ]);
    exit();
}

//Checks if input has type field
if (!isset($_POST['type'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Bad Request - 'type' field is missing",
        "API Line" => __LINE__
    ]);
    exit();
}

//For user we have the following login and registration
if ($_POST['type'] == 'Login') {
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing 'email' or 'password' field",
            "Type Handler" => "Login",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    try {
        $userStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $userStmt->bind_param("s", $email);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        if ($userResult->num_rows === 0) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid email or password",
                "Type Handler" => "Login"
            ]);
            $userStmt->close();
            exit();
        }

        $user = $userResult->fetch_assoc();
        $userStmt->close();

        $hashedPassword = hash_pbkdf2("sha256", $password, $user['salt'], 10000, 127);
        if (!hash_equals($user['password'], $hashedPassword)) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid email or password",
                "Type Handler" => "Login"
            ]);
            exit();
        }

        session_start();
        $_SESSION["apikey"] = $user['apikey'];
        
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "user_id" => $user['user_id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "user_type" => $user['user_type'],
                "apikey" => $user['apikey']
            ]
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "Login", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "Login", __LINE__);
    }
    exit();
}

//registration
if ($_POST['type'] == 'Register') {

    if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['user_type'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "Register",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    try {
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->num_rows > 0) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "User with similar details already exists.",
                "Type Handler" => "Register",
            ]);
            $check->close();
            exit();
        }
        $check->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "Register", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "Register", __LINE__);
    }

    $salt = bin2hex(random_bytes(127));
    $hashedPassword = hash_pbkdf2("sha256", $password, $salt, 10000, 127);
    try {
        $apikey = generateApikey();
    } catch (Exception $e) {
        
    }
    

    try {

        $conn->begin_transaction();

        $stmt = $conn->prepare("
                INSERT INTO users (name, email, password, salt, user_type, apikey)
                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $salt, $user_type, $apikey);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        $customerStmt = $conn->prepare("INSERT INTO customers (user_id) VALUES (?)");
        $customerStmt->bind_param("i", $user_id);
        $customerStmt->execute();
        $customerStmt->close();

        $conn->commit();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "User registered successfully."
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "Register", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "Register", __LINE__, true);
    }
    exit();
}

//now we have the following for products(add/delete/edit/remove)
if ($_POST['type'] == 'GetAllProducts')
{
    try {
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $brandQuery = $conn->prepare("SELECT name FROM brand where brand_id = ?");
            $brandQuery->bind_param("i", $row['brand_id']);
            $brandQuery->execute();
            $brandResult = $brandQuery->get_result();
            if ($brandResult->num_rows === 0) {
                $brand = "no brand";
            } else {
                $brandRow = $brandResult->fetch_assoc();
                $brand = $brandRow['name'];
            }
            $brandQuery->close();
            $products[] = [
                'id' => $row['product_id'],
                'title' => $row['title'],
                'price' => $row['price'],
                'product_link' => $row['product_link'],
                'description' => $row['description'],
                'launch_date' => $row['launch_date'],
                'thumbnail' => $row['thumbnail'],
                'category' => $row['category'],
                'brand_name' => $brand
            ];
        }
        $stmt->close();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Products fetched successfully",
            "data" => $products
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetAllProducts", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetAllProducts", __LINE__);
    }
    exit();
}

//add product
if ($_POST['type'] == 'AddProduct') {

    $validFields = ['title', 'price', 'product_link', 'description', 'launch_date', 'thumbnail', 'category', 'store_id', 'apikey', 'type'];

    foreach ($_POST as $key => $value) {
        if (!in_array($key, $validFields) && $key !== 'brand_name') {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid field: $key",
                "Type Handler" => "AddProduct",
                "API Line" => __LINE__
            ]);
            exit();
        }
    }

    foreach ($validFields as $field) {
        if (!isset($_POST[$field])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Missing required field: $field",
                "Type Handler" => "AddProduct",
                "API Line" => __LINE__
            ]);
            exit();
        }
    }

    $title = $_POST['title'];
    $price = $_POST['price'];
    $product_link = $_POST['product_link'];
    $description = $_POST['description'];
    $launch_date = $_POST['launch_date'];
    $thumbnail = $_POST['thumbnail'];
    $category = $_POST['category'];
    $store_id = $_POST['store_id'];
    $apikey = $_POST['apikey'];
    $user_id = authenticate($conn, $apikey);

    if (!isset($_POST['brand_name'])) {
        $brand_name = $_POST['title'];
    } else {
        $brand_name = $_POST['brand_name'];
    }

    try {
        $conn->begin_transaction();

        $brandStmt = $conn->prepare("SELECT * FROM brand WHERE name = ?");
        $brandStmt->bind_param("s", $brand_name);
        $brandStmt->execute();
        $brandResult = $brandStmt->get_result();

        if ($brandResult->num_rows === 0) {
           $brand_id = createBrand($conn, $brand_name, false);
        } else {
            $brand_id = $brandResult->fetch_assoc()['brand_id'];
        }

        $brandStmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "AddProduct", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "AddProduct", __LINE__, true);
    }

    try {
        $ownerStmt = $conn->prepare("SELECT user_id ,store_id FROM store_owner WHERE user_id = ? AND store_id = ?");
        $ownerStmt->bind_param("ii", $user_id,$store_id);
        $ownerStmt->execute();
        $ownerStmt->store_result();

        if ($ownerStmt->num_rows === 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "User is not a store owner",
                "Type Handler" => "AddProduct",
                "API Line" => __LINE__
            ]);
            exit();
        }
        $ownerStmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "AddProduct", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"AddProduct", __LINE__);
    }

    try {
        $stmt = $conn->prepare("
        INSERT INTO products (title, price, product_link, description, launch_date, thumbnail, category, brand_id, store_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdssssssi", $title, $price, $product_link, $description, $launch_date, $thumbnail, $category, $brand_id, $store_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Product successfully added to the database."
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "AddProduct", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "AddProduct", __LINE__, true);
    }
    exit();
}

//delete product
if ($_POST['type'] == 'DeleteProduct')
{

    if (!isset($_POST['prod_id']) || !isset($_POST['apikey']) || !isset($_POST['store_id'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing product id, apikey or store id.",
            "Type Handler" => "DeleteProduct",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $prod_id = $_POST['prod_id'];
    $apikey = $_POST['apikey'];
    $store_id = $_POST['store_id'];

    $user_id = authenticate($conn, $apikey);

    try {
        $stmt = $conn->prepare("SELECT user_id, store_id FROM store_owner WHERE user_id = ? AND store_id = ?");
        $stmt->bind_param("ii", $user_id,$store_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "User is not this store's owner",
                "Type Handler" => "DeleteProduct",
                "API Line" => __LINE__
            ]);
            exit();
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "DeleteProduct", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"DeleteProduct", __LINE__);
    }

    try {
        $productCheck = $conn->prepare("SELECT * FROM products WHERE prod_id = ? AND store_id = ?");
        $productCheck->bind_param("ii", $prod_id, $store_id);
        $productCheck->execute();
        $productCheck->store_result();

        if ($productCheck->num_rows === 0)
        {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "This product does not belong to your store."
            ]);
            exit();
        }
        $productCheck->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "DeleteProduct", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"DeleteProduct", __LINE__);
    }

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("DELETE FROM products WHERE prod_id = ?");
        $stmt->bind_param("i", $prod_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Product successfully deleted from the database."
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "DeleteProduct", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "DeleteProduct", __LINE__, true);
    }

    exit();
}

//edit product
if ($_POST['type'] == 'EditProduct')
{
    if (!isset($_POST['prod_id']) || !isset($_POST['store_id']) || !isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "EditProduct",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $prod_id = $_POST['prod_id'];
    $apikey = $_POST['apikey'];
    $store_id = $_POST['store_id'];

    $user_id = authenticate($conn, $apikey);

    try {
        $stmt = $conn->prepare("SELECT user_id, store_id FROM store_owner WHERE user_id = ? AND store_id = ?");
        $stmt->bind_param("ii", $user_id,$store_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "User is not this store's owner",
                "Type Handler" => "EditProduct",
                "API Line" => __LINE__
            ]);
            exit();
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "EditProduct", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"EditProduct", __LINE__);
    }

    try {
        $productCheck = $conn->prepare("SELECT * FROM products WHERE prod_id = ? AND store_id = ?");
        $productCheck->bind_param("ii", $prod_id, $store_id);
        $productCheck->execute();
        $productResult = $productCheck->get_result();

        if ($productResult->num_rows === 0)
        {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "This product does not belong to your store.",
                "Type Handler" => "EditProduct",
                "API Line" => __LINE__
            ]);
            exit();
        }

        $products = $productResult->fetch_assoc();
        $productCheck->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "EditProduct", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"EditProduct", __LINE__);
    }

    $title = !isset($_POST['title']) ? $products['title'] : $_POST['title'];
    $price = !isset($_POST['price']) ? $products['price'] : $_POST['price'];
    $product_link = !isset($_POST['product_link']) ? $products['product_link'] : $_POST['product_link'];
    $description = !isset($_POST['description']) ? $products['description'] : $_POST['description'];
    $launch_date = !isset($_POST['launch_date']) ? $products['launch_date'] : $_POST['launch_date'];
    $thumbnail = !isset($_POST['thumbnail']) ? $products['thumbnail'] : $_POST['thumbnail'];
    $category = !isset($_POST['category']) ? $products['category'] : $_POST['category'];
    $brand_id = !isset($_POST['brand_id']) ? $products['brand_id'] : $_POST['brand_id'];

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("UPDATE products
        SET title = ?, price = ?, product_link = ?, description = ?, launch_date = ?, thumbnail = ?, category = ?, brand_id = ?, store_id = ?
        WHERE prod_id = ?");
        $stmt->bind_param("sdsssssiii", $title, $price, $product_link, $description, $launch_date, $thumbnail, $category, $brand_id, $store_id, $prod_id);;
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "EditProduct", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "EditProduct", __LINE__, true);
    }

    $conn->commit();
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Product successfully edited in the database."
    ]);

    exit();
}

//filter products
if ($_POST['type'] == 'GetFilteredProducts')
{
    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "GetFilteredProducts",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $user_id = authenticate($conn, $apikey);

    $brand_id = $_POST['brand_id'] ?? null;
    $category = $_POST['category'] ?? null;
    $min_price = $_POST['min_price'] ?? null;
    $max_price = $_POST['max_price'] ?? null;
    $search = $_POST['search'] ?? null;
    $store_id = $_POST['store_id'] ?? null;

    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($brand_id)) {
        $sql .= " AND brand_id = ?";
        $params[] = $brand_id;
        $types .= "i";
    }

    if (!empty($category)) {
        $sql .= " AND category = ?";
        $params[] = $category;
        $types .= "s";
    }

    if (!empty($min_price)) {
        $sql .= " AND price >= ?";
        $params[] = $min_price;
        $types .= "d";
    }

    if (!empty($max_price)) {
        $sql .= " AND price <= ?";
        $params[] = $max_price;
        $types .= "d";
    }

    if (!empty($search)) {
        $sql .= " AND title LIKE ?";
        $params[] = '%' . $search . '%';
        $types .= "s";
    }

    if (!empty($store_id)) {
        $sql .= " AND store_id = ?";
        $params[] = $store_id;
        $types .= "i";
    }

    try {
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Products fetched successfully.",
            "data" => $products
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetFilteredProducts", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetFilteredProducts", __LINE__);
    }
    exit();
}

//now we have the following for rating
if ($_POST['type'] == 'SubmitRating')
{
    if (!isset($_POST['apikey']) || !isset($_POST['prod_id']) || !isset($_POST['rating']) || !isset($_POST['comment'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "SubmitRating",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $prod_id = $_POST['prod_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $user_id = authenticate($conn, $apikey);

    try {
        $duplicateStmt = $conn->prepare("SELECT user_id FROM ratings WHERE user_id = ?");
        $duplicateStmt->bind_param("i", $user_id);
        $duplicateStmt->execute();
        $duplicateResult = $duplicateStmt->get_result();

        if ($duplicateResult->num_rows !== 0){
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "This user already submitted a rating for this product",
                "Type Handler" => "SubmitRating",
                "API Line" => __LINE__
            ]);
            exit();
        }
        $duplicateStmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "SubmitRating", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"SubmitRating", __LINE__);
    }

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("INSERT INTO ratings (user_id, prod_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $user_id, $prod_id, $rating, $comment);
        $stmt->execute();
        $stmt->close();
        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Rating successfully submitted."
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "SubmitRating", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "SubmitRating", __LINE__, true);
    }

    exit();
}

// Get All Ratings for a Product
if ($_POST['type'] == 'GetRatings')
{
    if (!isset($_POST['prod_id'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "prod_id is required",
            "Type Handler" => "GetRatings",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $prod_id = $_POST['prod_id'];

    try {
        $stmt = $conn->prepare("SELECT u.name, r.rating, r.comment FROM ratings r JOIN users u ON r.user_id = u.user_id WHERE r.prod_id = ?");
        $stmt->bind_param("i", $prod_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $ratings = [];
        while ($row = $result->fetch_assoc()) {
            $ratings[] = $row;
        }
        $stmt->close();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Ratings fetched successfully.",
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetRatings", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"GetRatings", __LINE__);
    }

    exit();
}

//Delete Rating
if ($_POST['type'] == 'DeleteRating')
{
    if (!isset($_POST['rating_id']) || !isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "DeleteRating",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $rating_id = $_POST['rating_id'];

    $user_id = authenticate($conn, $apikey);

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("DELETE FROM ratings WHERE rating_id = ?");
        $stmt->bind_param("i", $rating_id);
        $stmt->execute();
        $stmt->close();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Rating deleted successfully."
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "DeleteRating", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "DeleteRating", __LINE__, true);
    }

    exit();
}

//Edit Rating
if ($_POST['type'] == 'EditRating')
{
    if (!isset($_POST['rating_id']) || !isset($_POST['apikey']) || !isset($_POST['rating']) || !isset($_POST['comment'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "EditRating",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $rating_id = $_POST['rating_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $user_id = authenticate($conn, $apikey);

    try {
        $checkStmt = $conn->prepare("SELECT * FROM ratings WHERE rating_id = ? AND user_id = ?");
        $checkStmt->bind_param("ii", $rating_id, $user_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows === 0) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Unauthorized: You can only edit your own rating.",
                "Type Handler" => "EditRating",
                "API Line" => __LINE__
            ]);
            exit();
        }

        $checkStmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "EditRating", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"EditRating", __LINE__);
    }

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("UPDATE ratings SET rating = ?, comment = ? WHERE rating_id = ?");
        $stmt->bind_param("isi", $rating, $comment, $rating_id);
        $stmt->execute();
        $stmt->close();
        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Rating successfully edited."
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "EditRating", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "EditRating", __LINE__, true);
    }

    exit();
}

//Fetch all the available stores. I made apikey required but I can change it to only need the type
if ($_POST['type'] == "GetStores")
{
    //Fetch stores
    try {
        $storeStmt = $conn->prepare("SELECT * FROM store");
        $storeStmt->execute();
        $storeResult = $storeStmt->get_result();

        if ($storeResult->num_rows == 0){
            http_response_code(204);
            echo json_encode([
                "status" => "success",
                "message" => "There are currently no stores available",
                "data" => []
            ]);
            $storeStmt->close();
            exit();
        }

        $stores = [];
        while ($row = $storeResult->fetch_assoc()) {
            $stores[] = $row;
        }
        $storeStmt->close();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "All stores available, retrieved successfully.",
            "data" => $stores
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetStores", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetStores", __LINE__);
    }
    exit();
}

//Fetch users store's
if ($_POST['type'] == "GetUsersStore"){
    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "GetUsersStores",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $user_id = authenticate($conn, $apikey);

    try {
        $ownerStmt = $conn->prepare("SELECT * FROM store_owner WHERE user_id = ?");
        $ownerStmt->bind_param("i", $user_id);
        $ownerStmt->execute();
        $ownerResult = $ownerStmt->get_result();

        if ($ownerResult->num_rows == 0){
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "This user is not a store owner",
                "Type Handler" => "GetUsersStore",
                "API Line" => __LINE__
            ]);
            exit();
        }

        $storeID = $ownerResult->fetch_assoc()['store_id'];
        $ownerStmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetUsersStores", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetUsersStores", __LINE__);
    }

    try {
        $storeStmt = $conn->prepare("SELECT * FROM store WHERE store_id = ?");
        $storeStmt->bind_param("i", $storeID);
        $storeStmt->execute();
        $storeResult = $storeStmt->get_result();

        $stores = $storeResult->fetch_assoc();
        $storeStmt->close();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Stores retrieved successfully",
            "data" => $stores
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetUsersStores", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetUsersStores", __LINE__);
    }
    exit();
}

//Follow a store
if ($_POST['type'] == 'Follow')
{
    if (!isset($_POST['apikey']) || !isset($_POST['store_id'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "Follow",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $store_id = $_POST['store_id'];
    $apikey = $_POST['apikey'];
    $user_id = authenticate($conn, $apikey);

    try {
        $duplicateStmt = $conn->prepare("SELECT store_id FROM follows WHERE store_id = ? AND user_id = ?");
        $duplicateStmt->bind_param("ii", $store_id, $user_id);
        $duplicateStmt->execute();
        $duplicateResult = $duplicateStmt->get_result();

        if ($duplicateResult->num_rows !== 0){
            http_response_code(409);
            echo json_encode([
                "status" => "error",
                "message" => "This user already follows this store",
                "Type Handler" => "Follow",
                "API Line" => __LINE__
            ]);
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "Follow", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e,"Follow", __LINE__);
    }

    //Add to the follows table
    try {
        $conn->begin_transaction();

        $followStmt = $conn->prepare("INSERT INTO follows (store_id, user_id) VALUES (?, ?)");
        $followStmt->bind_param("ii", $store_id, $user_id);
        $followStmt->execute();
        $followStmt->close();
        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Successfully followed store",
            "data" => $store_id
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "Follow", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "Follow", __LINE__, true);
    }
    exit();
}

//Retrieve stores that user follows
if ($_POST['type'] == 'GetFollowing') {

    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "GetFollowing",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $user_id = authenticate($conn, $apikey);


    //Get the stores
    try {
        $followStmt = $conn->prepare("SELECT store_id FROM follows WHERE user_id = ?");
        $followStmt->bind_param("i", $user_id);
        $followStmt->execute();
        $followResult = $followStmt->get_result();
        $followedStoreIds = [];
        while ($row = $followResult->fetch_assoc()) {
            $followedStoreIds[] = $row['store_id'];
        }
        $followStmt->close();

        if (empty($followedStoreIds)) {
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "User is not following any stores",
                "data" => []
            ]);
            exit();
        }

        //******************* Check if this code is fine ***********************//
        $placeholders = implode(',', array_fill(0, count($followedStoreIds), '?'));
        $types = str_repeat('i', count($followedStoreIds));

        $storeStmt = $conn->prepare("SELECT * FROM store WHERE store_id IN ($placeholders)");
        $storeStmt->bind_param($types, ...$followedStoreIds);
        $storeStmt->execute();
        $storeResult = $storeStmt->get_result();
        $stores = $storeResult->fetch_all(MYSQLI_ASSOC);
        $storeStmt->close();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Successfully retrieved followed stores",
            "data" => $stores
        ]);

    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetFollowing", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetFollowing", __LINE__);
    }
    exit();
}

//Remove a follow
if ($_POST['type'] == 'Unfollow') {

    if (!isset($_POST['apikey']) || !isset($_POST['store_id'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "Unfollow",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $store_id = $_POST['store_id'];
    $apikey = $_POST['apikey'];
    $user_id = authenticate($conn, $apikey);


    //Remove from the follows table
    try{
        $conn->begin_transaction();
        $followStmt = $conn->prepare("DELETE FROM follows WHERE store_id = ? AND user_id = ?");
        $followStmt->bind_param("ii", $store_id, $user_id);
        $followStmt->execute();
        $followStmt->close();

        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Successfully unfollowed store",
            "data" => $store_id
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "Unfollow", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "Unfollow", __LINE__, true);
    }
    exit();
}

//Registers user as a store owner
if ($_POST['type'] == 'RegisterStoreOwner') {

    //Check if all fields are present
    if (!isset($_POST['apikey']) || !isset($_POST['store_name']) || !isset($_POST['store_url']) || !isset($_POST['store_type']) || !isset($_POST['registrationNo'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "RegisterStoreOwner",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $store_name = $_POST['store_name'];
    $store_url = $_POST['store_url'];
    $apikey = $_POST['apikey'];
    $type = $_POST['store_type'];
    $registrationNo = $_POST['registrationNo'];
    $user_id = authenticate($conn, $apikey);

    //Add store to database
    try {
        $conn->begin_transaction();

        $storeStmt = $conn->prepare("INSERT INTO store (store_name, store_url, store_type) VALUES (?, ?, ?)");
        $storeStmt->bind_param("sss", $store_name, $store_url, $type);
        $storeStmt->execute();
        $store_id = $storeStmt->insert_id;
        $storeStmt->close();

    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "RegisterStoreOwner", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "RegisterStoreOwner", __LINE__, true);
    }

    try {
        $ownerStmt = $conn->prepare("Insert into store_owner (user_id, store_id, registrationNo) VALUES (?, ?, ?)");
        $ownerStmt->bind_param("iii", $user_id, $store_id, $registrationNo);;
        $ownerStmt->execute();
        $ownerStmt->close();

        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Successfully registered store owner"
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "RegisterStoreOwner", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "RegisterStoreOwner", __LINE__, true);
    }

    exit();
}

//Filter stores
if ($_POST['type'] == 'getFilteredStores') 
{
    if (!isset($_POST['store_id']))
    {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $store_id = $_POST['store_id'];

    $stmt = $conn->prepare("SELECT * FROM stores WHERE store_id = ?");
    $stmt->bind_param("i", $store_id);

    if ($stmt->execute()) 
    {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $row]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error retrieving store"]);
    }

    exit();
}

//Add a brand. Admin use only
if ($_POST['type'] == 'AddBrand'){

    if (!isset($_POST['apikey']) || !isset($_POST['brand_name'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing apikey or brand_name field",
            "Type Handler" => "AddBrand",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $brand_name = $_POST['brand_name'];
    $user_id = authenticate($conn, $apikey);
    //************************************** admin ************************************//

    try {
        $conn->begin_transaction();
        $brand_id = createBrand($conn, $brand_name, false);
        $conn->commit();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Brand added successfully",
            "data" => $brand_id
        ]);
    } catch (Exception $e) {
        catchErrorSQL($conn, $e, "AddBrand", __LINE__, true);
    } catch (Error $e) {
        catchError($conn, $e, "AddBrand", __LINE__, true);
    }
    exit();
}

//Remove a brand. Admin use only
if ($_POST['type'] == 'RemoveBrand'){

    if (!isset($_POST['apikey']) || !isset($_POST['brand_id'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing apikey or brand_id field",
            "Type Handler" => "RemoveBrand",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];
    $brand_id = $_POST['brand_id'];
    $user_id = authenticate($conn, $apikey);
    //************************************* Admin **********************************//

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("DELETE FROM brand WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $stmt->close();
        $conn->commit();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Brand removed successfully"
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "RemoveBrand", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "RemoveBrand", __LINE__, true);
    }
    exit();
}

//Get Brands
if ($_POST['type'] == 'GetBrands'){

    try {
        $stmt = $conn->prepare("SELECT * FROM brand");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0){
            http_response_code(204);
            echo json_encode([
                "status" => "success",
                "message" => "There are currently no brands available",
                "data" => []
            ]);
        } else {
            $brands = [];
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Brands successfully retrieved",
                "data" => $brands
            ]);
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetBrands", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetBrands", __LINE__);
    }
    exit();
}

//creates a new brand
function createBrand($conn, $brand_name, $transaction = true)
{
    if ($transaction) {
        $conn->begin_transaction();
    }

    try {
        $stmt = $conn->prepare("INSERT INTO brand (brand_name) VALUES (?)");
        $stmt->bind_param("s", $brand_name);
        $stmt->execute();

        $brand_id = $stmt->insert_id;
        $stmt->close();

        if ($transaction) {
            $conn->commit();
        }
        return $brand_id;
    } catch (Exception $e) {
        if ($transaction) {
            $conn->rollback();
        }
        throw $e;
    }
}

//Catches SQL errors
function catchErrorSQL($conn, $error, $type , $line , $rollback = false){
    if ($rollback) {
        $conn->rollback();
    }
    error_log("Register error: " . $error);
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error",
        "Type Handler" => $type,
        "API Line" => $line
    ]);
    exit();
}

//Catches errors
function catchError($conn, $error, $type, $line, $rollback = false){
    if ($rollback) {
        $conn->rollback();
    }
    error_log("Register error: " . $error);
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error",
        "Type Handler" => $type,
        "API Line" => $line
    ]);
    exit();
}

if ($_POST['type'] == 'SavePreferences')
{

    if (!isset($_POST['theme']) || !isset($_POST['min_price']) || !isset($_POST['max_price']) || !isset($_POST['apikey'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $theme = $_POST['theme'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];
    $apikey = $_POST['apikey'];

    $user_id = authenticate($conn, $apikey);

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("UPDATE users SET theme = ?, min_price = ? , max_price = ? WHERE apikey = ?");
        $stmt->bind_param("sdds", $theme, $min_price, $max_price, $apikey);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Preferences successfully updated"
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "SavePreferences", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "SavePreferences", __LINE__, true);
    }
    exit();
}

/*
    This function generates an apikey that is unique.
*/
function generateApikey() {
    $apikey = "";
    $conn = Database::instance()->getConnection();
    do {
        try {
            $apikey = bin2hex(random_bytes(16));
            $stmt = $conn->prepare("SELECT apikey FROM users WHERE apikey = ?;");
            $stmt->bind_param("s", $apikey);

            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            catchErrorSQL($conn, $e, __LINE__, "generate apikey", false);
        } catch (Exception $e) {
            catchError($conn, $e, __LINE__, "generate apikey", false);
        }
        
    } while ($result->num_rows != 0);
    
    return $apikey;
}

/* 
    This function uses the apikey to see if the user is actually logged in by checking the session variable 
    and then returns the user_id if successful
    otherwise it catches errors or sends back an unauthorised message.
*/
function authenticate($conn, $apikey) {
    session_start();
    if (!isset($_SESSION["apikey"]) || $_SESSION["apikey"] != $apikey) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "user not signed in"]);
        die;
    }

    try {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE apikey = ?;");
        $stmt->bind_param("s", $apikey);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];
            return $user_id;
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "user not signed in"]);
            die;
        }
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, __LINE__, "authentication", false);
    } catch (Exception $e) {
        catchError($conn, $e, __LINE__, "authentication", false);
    }
}
?>
