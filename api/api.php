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
    GetAllProducts      [0]
    AddProduct          [0]
    DeleteProduct       [0]
    EditProduct         [-1]
    GetFilteredProducts [-1]
    SubmitRating        [-1]
    GetRatings          [-1]
    DeleteRating        [-1]
    EditRating          [-1]
    GetStores           [-1]
    Follow              [-1]
    GetFollowing        [-1]
    Unfollow            [-1]
    RegisterStoreOwner  [-1]
    SavePreference      [-1]

*/

//TODO:
//  Improve Errors
//  Test All Types
//  Create Types:
//      - SavePreference


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
        $userStmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
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
                INSERT INTO Users (name, email, password, salt, user_type, apikey)
                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hashedPassword, $salt, $user_type, $apikey);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        $customerStmt = $conn->prepare("INSERT INTO Customers (user_id) VALUES (?)");
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
        $stmt = $conn->prepare("SELECT * FROM Product");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $brandQuery = $conn->prepare("SELECT name FROM Brand where brand_id = ?");
            $brandQuery->bind_param("i", $row['brand_id']);
            $brandQuery->execute();
            $brandResult = $brandQuery->get_result();
            if ($brandResult->num_rows === 0) {
                http_response_code(400);
                echo json_encode([
                    "status" => "error",
                    "message" => "Brand does not exist.",
                    "Type Handler" => "GetAllProducts",
                    "API Line" => __LINE__
                ]);
                $brandQuery->close();
                exit();
            }
            $brand = $brandResult->fetch_assoc();
            $brandQuery->close();
            $products[] = [
                'id' => $row['id'],
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

    $validFields = ['title', 'price', 'product_link', 'description', 'launch_date', 'thumbnail', 'category', 'store_id', 'user_id', 'type'];

    foreach ($_POST as $key => $value) {
        if (!in_array($key, $validFields) && $key !== 'brand_name') {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid field: $key"
            ]);
            exit();
        }
    }

    foreach ($validFields as $field) {
        if (!isset($_POST[$field])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Missing required field: $field"
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
    $user_id = $_POST['user_id'];

    if (!isset($_POST['brand_name'])) {
        $brand_name = $_POST['title'];
    } else {
        $brand_name = $_POST['brand_name'];
    }

    try {
        $conn->begin_transaction();

        $brandStmt = $conn->prepare("SELECT * FROM Brand WHERE name = ?");
        $brandStmt->bind_param("s", $brand_name);
        $brandStmt->execute();
        $brandResult = $brandStmt->get_result();

        if ($brandResult->num_rows === 0) {
           $brand_id = createBrand($conn, $brand_name);
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
        $ownerStmt = $conn->prepare("SELECT userID,store_id FROM store_Owner WHERE user_id = ? AND store_id = ?");
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
        INSERT INTO Product (title, price, product_link, description, launch_date, thumbnail, category, brand_id, store_id)
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
        $stmt = $conn->prepare("SELECT user_id, store_id FROM store_Owner WHERE user_id = ? AND store_id = ?");
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
        $productCheck = $conn->prepare("SELECT * FROM Product WHERE prod_id = ? AND store_id = ?");
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

        $stmt = $conn->prepare("DELETE FROM Product WHERE prod_id = ?");
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

    if (!isset($_POST['prod_id']) || !isset($_POST['store_id']) || !isset($_POST['user_id'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $prod_id = $_POST['prod_id'];
    $user_id = $_POST['user_id'];
    $store_id = $_POST['store_id'];;

    $stmt = $conn->prepare("SELECT user_id, store_id FROM store_Owner WHERE user_id = ? AND store_id = ?");
    $stmt->bind_param("ii", $user_id,$store_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Sorry, You are not a store owner of this product"
        ]);
        exit();
    }


    $productCheck = $conn->prepare("SELECT * FROM Product WHERE prod_id = ? AND store_id = ?");
    $productCheck->bind_param("ii", $prod_id, $store_id);
    $productCheck->execute();
    $productResult = $productCheck->get_result();

    if ($productResult->num_rows === 0)
    {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "This product does not belong to your store."
        ]);
        exit();
    }

    $products = $productResult->fetch_assoc();
    $productCheck->close();

    $title = !isset($_POST['title']) ? $products['title'] : $_POST['title'];
    $price = !isset($_POST['price']) ? $products['price'] : $_POST['price'];
    $product_link = !isset($_POST['product_link']) ? $products['product_link'] : $_POST['product_link'];
    $description = !isset($_POST['description']) ? $products['description'] : $_POST['description'];
    $launch_date = !isset($_POST['launch_date']) ? $products['launch_date'] : $_POST['launch_date'];
    $thumbnail = !isset($_POST['thumbnail']) ? $products['thumbnail'] : $_POST['thumbnail'];
    $category = !isset($_POST['category']) ? $products['category'] : $_POST['category'];
    $brand_id = !isset($_POST['brand_id']) ? $products['brand_id'] : $_POST['brand_id'];

    $stmt = $conn->prepare("
        UPDATE Product
        SET title = ?, price = ?, product_link = ?, description = ?, launch_date = ?, thumbnail = ?, category = ?, brand_id = ?, store_id = ?
        WHERE prod_id = ?
    ");

    $stmt->bind_param("sdsssssiii", $title, $price, $product_link, $description, $launch_date, $thumbnail, $category, $brand_id, $prod_id,$store_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Product updated successfully."
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update product."
        ]);
    }
    $stmt->close();
    exit();
}

//filter products
if ($_POST['type'] == 'GetFilteredProducts')
{
    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }
    $apikey = $_POST['apikey'];

    $authQuery = $conn->prepare("SELECT id FROM user WHERE apikey = ?");
    $authQuery->bind_param("s", $apikey);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    if ($authResult->num_rows === 0) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit();
    }

    $user = $authResult->fetch_assoc();
    $authQuery->close();

    $brand_id = $_POST['brand_id'] ?? null;
    $category = $_POST['category'] ?? null;
    $min_price = $_POST['min_price'] ?? null;
    $max_price = $_POST['max_price'] ?? null;
    $search = $_POST['search'] ?? null;

    $sql = "SELECT * FROM Product WHERE 1=1";
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
        "data" => $products
    ]);
    exit();
}

//now we have the following for rating
if ($_POST['type'] == 'SubmitRating')
{
    if (!isset($_POST['user_id']) || !isset($_POST['prod_id']) || !isset($_POST['rating']) || !isset($_POST['comment'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $user_id = $_POST['user_id'];
    $prod_id = $_POST['prod_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $authQuery = $conn->prepare("SELECT id FROM user WHERE id = ?");
    $authQuery->bind_param("i", $user_id);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    if ($authResult->num_rows === 0) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit();
    }

    $authQuery->close();

    $duplicateStmt = $conn->prepare("SELECT user_id FROM Rating WHERE user_id = ?");
    $duplicateStmt->bind_param("i", $user_id);
    $duplicateStmt->execute();
    $duplicateResult = $duplicateStmt->get_result();

    if ($duplicateResult->num_rows !== 0){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "This user already submitted a rating for this product"
        ]);
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO Rating (user_id, prod_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $prod_id, $rating, $comment);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Rating submitted successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to submit rating."]);
    }
    $stmt->close();
    exit();
}

// Get All Ratings for a Product
if ($_POST['type'] == 'GetRatings')
{
    if (!isset($_POST['prod_id'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "prod_id is required"
        ]);
        exit();
    }

    $prod_id = $_POST['prod_id'];

    $stmt = $conn->prepare("
        SELECT u.name, r.rating, r.comment
        FROM Rating r
        JOIN User u ON r.user_id = u.user_id
        WHERE r.prod_id = ?
    ");
    $stmt->bind_param("i", $prod_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ratings = [];
    while ($row = $result->fetch_assoc()) {
        $ratings[] = $row;
    }
    $stmt->close();

    http_response_code(200);
    echo json_encode(["status" => "success", "data" => $ratings]);
    exit();
}

//Delete Rating
if ($_POST['type'] == 'DeleteRating')
{
    if (!isset($_POST['rating_id']) || !isset($_POST['user_id'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $user_id = $_POST['user_id'];
    $rating_id = $_POST['rating_id'];

    $authQuery = $conn->prepare("SELECT id FROM user WHERE id = ?");
    $authQuery->bind_param("i", $user_id);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    if ($authResult->num_rows === 0) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit();
    }

    $authQuery->close();

    $stmt = $conn->prepare("DELETE FROM Rating WHERE rating_id = ?");
    $stmt->bind_param("i", $rating_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Rating deleted successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to delete rating."]);
    }
    $stmt->close();
    exit();
}

//Edit Rating
if ($_POST['type'] == 'EditRating')
{
    $user_id = $_POST['user_id'];
    $rating_id = $_POST['rating_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $checkStmt = $conn->prepare("SELECT * FROM Rating WHERE rating_id = ? AND user_id = ?");
    $checkStmt->bind_param("ii", $rating_id, $user_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Unauthorized: You can only edit your own rating."]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE Rating SET rating = ?, comment = ? WHERE rating_id = ?");
    $stmt->bind_param("isi", $rating, $comment, $rating_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Rating updated successfully."]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to update rating."]);
    }
    $stmt->close();
    exit();
}

//Fetch all the available stores. I made apikey required but I can change it to only need the type
if ($_POST['type'] == "GetStores")
{

    // Validate API key exists
    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];

    // Retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apikey = ?");
    $authQuery->bind_param("s", $apikey);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    // Check if user exists
    if ($authResult->num_rows === 0) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit();
    }

    $authQuery->close();

    //Fetch stores
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
}

//Follow a store
if ($_POST['type'] == 'Follow')
{

    //I used store_name but we can change it to store_is
    if (!isset($_POST['apikey']) || !isset($_POST['store_id'])){
        http_response_code(400);
        echo json_encode(["status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $store_id = $_POST['store_id'];
    $apikey = $_POST['apikey'];

    //retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apikey = ?");
    $authQuery->bind_param("s", $apikey);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    //Checks if user is in database
    if ($authResult->num_rows === 0) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit();
    }
    $user = $authResult->fetch_assoc();
    $authQuery->close();

    //Add to the follows table
    try {
        $followStmt = $conn->prepare("INSERT INTO follows (store_id, user_id) VALUES (?, ?)");
        if (!$followStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $followStmt->bind_param("ii", $user["id"], $store_id);
        if (!$followStmt->execute()) {
            throw new Exception("Execute failed: " . $followStmt->error);
        }
        $followStmt->close();
    }catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        die(json_encode([
            "status" => "error",
            "message" => "Failed to follow store"
        ]));
    }

    $conn->commit();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Successfully followed store",
        "data" => $store_id
    ]);
}

//Retrieve stores that user follows
if ($_POST['type'] == 'GetFollowing') {

    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $apikey = $_POST['apikey'];

    //retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apikey = ?");
    $authQuery->bind_param("s", $apikey);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    //Checks if user is in database
    if ($authResult->num_rows === 0) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit();
    }
    $user = $authResult->fetch_assoc();
    $authQuery->close();

    //Get the stores
    try {
        $followStmt = $conn->prepare("SELECT store_id FROM follows WHERE user_id = ?");
        if (!$followStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $followStmt->bind_param("i", $user['id']);
        if (!$followStmt->execute()) {
            throw new Exception("Execute failed: " . $followStmt->error);
        }

        $followResult = $followStmt->get_result();
        $followedStoreIds = [];
        while ($id = $followResult->fetch_assoc()) {
            $followedStoreIds[] = $id['store_id'];
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

        $stores = [];
        $storeStmt = $conn->prepare("SELECT * FROM store WHERE store_id = ?");
        if (!$storeStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        foreach ($followedStoreIds as $storeId) {
            $storeStmt->bind_param("i", $storeId);
            if (!$storeStmt->execute()) {
                throw new Exception("Execute failed for store_id {$storeId}: " . $storeStmt->error);
            }

            $storeResult = $storeStmt->get_result();
            if ($store = $storeResult->fetch_assoc()) {
                $stores[] = $store;
            }
            $storeStmt->reset();
        }
        $storeStmt->close();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Successfully retrieved followed stores",
            "data" => $stores
        ]);

    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        die(json_encode([
            "status" => "error",
            "message" => "Failed to retrieve followed stores"
        ]));
    }
}

//Remove a follow
if ($_POST['type'] == 'Unfollow') {

    if (!isset($_POST['apikey']) || !isset($_POST['store_id'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
    }

    $store_id = $_POST['store_id'];
    $apikey = $_POST['apikey'];

    //retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apikey = ?");
    $authQuery->bind_param("s", $apikey);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    //Checks if user is in database
    if ($authResult->num_rows === 0) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit;
    }
    $user = $authResult->fetch_assoc();
    $authQuery->close();

    //Remove from the follows table
    try{
        $followStmt = $conn->prepare("DELETE FROM follows WHERE store_id = ? AND user_id = ?");
        if (!$followStmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $followStmt->bind_param("ii", $store_id, $user['id']);
        if (!$followStmt->execute()) {
            throw new Exception("Execute failed: " . $followStmt->error);
        }
        $followStmt->close();
    }catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to remove follow"
        ]);
        exit();
    }
    $conn->commit();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Successfully removed follow",
        "data" => $store_id
    ]);
}

//Registers user as a store owner
if ($_POST['type'] == 'RegisterStoreOwner') {

    //Check if all fields are present
    if (!isset($_POST['apikey']) || !isset($_POST['store_name']) || !isset($_POST['store_url']) || !isset($_POST['type'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $store_name = $_POST['store_name'];
    $store_url = $_POST['store_url'];
    $apikey = $_POST['apikey'];
    $type = $_POST['type'];

    //Check if user exists
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apikey = ?");
    $authQuery->bind_param("s", $apikey);
    $authQuery->execute();
    $authResult = $authQuery->get_result();

    if ($authResult->num_rows === 0) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Authentication failed. Invalid credentials."
        ]);
        $authQuery->close();
        exit();
    }

    $user = $authResult->fetch_assoc();
    $authQuery->close();

    //Add store to database
    $storeStmt = $conn->prepare("INSERT INTO store (store_name, store_url, type) VALUES (?, ?, ?)");
    $storeStmt->bind_param("sss", $store_name, $store_url, $type);
    if (!$storeStmt->execute()) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add store"
        ]);
        exit();
    }
    $store_id = $storeStmt->insert_id;
    $storeStmt->close();

    $storeQuery = $conn->prepare("SELECT store_id FROM store WHERE store_id = ?");
    $storeQuery->bind_param("s", $store_id);
    $storeQuery->execute();
    $storeResult = $storeQuery->get_result();

    if ($storeResult->num_rows === 0) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add store"
        ]);
        $storeQuery->close();
        exit();
    }

    $store = $storeResult->fetch_assoc();
    $storeQuery->close();

    $ownerStmt = $conn->prepare("Insert into store_owner (user_id, store_id) VALUES (?, ?)");
    $ownerStmt->bind_param("ii", $user['id'], $store['store_id']);
    if (!$ownerStmt->execute()) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add owner to the created store"
        ]);
        $ownerStmt->close();
        exit();
    }
    $ownerStmt->close();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Successfully added store and assigned owner"
    ]);
}

//Saves user's preference: filtering, theme, ens.
if ($_POST['type'] == 'SavePreference')
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

    $stmt = $conn->prepare("UPDATE User SET theme = ?, min_price = ? , max_price = ? WHERE apikey = ?");
    $stmt->bind_param("iiii", $theme, $min_price, $apikey);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Updated user settings"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating the user settings"]);
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

    //************************************** admin ************************************//

    try {
        $conn->begin_transaction();
        $brand_id = createBrand($conn, $brand_name);
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

    //************************************* Admin **********************************//

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("DELETE FROM Brand WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "RemoveBrand", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "RemoveBrand", __LINE__, true);
    }
    $conn->commit();
    exit();
}

//Get Brands
if ($_POST['type'] == 'GetBrands'){

    if (!isset($_POST['apikey'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing apikey field",
            "Type Handler" => "GetBrands",
            "API Line" => __LINE__
        ]);
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM Brand");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0){
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
function createBrand($conn, $brand_name)
{
    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO brand (brand_name) VALUES (?)");
    $stmt->bind_param("s", $brand_name);
    $stmt->execute();

    $brand_id = $stmt->insert_id;
    $stmt->close();

    $conn->commit();
    return $brand_id;
}

//Catches SQL errors
function catchErrorSQL($conn, $error, $line, $type , $rollback = false){
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
function catchError($conn, $error, $line, $type, $rollback = false){
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
        if ($result->num_rows == 0) {
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