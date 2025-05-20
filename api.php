<?php

/* The Null Pointers - COS221 Assignment 5

    Progress:   [0] - Untested
                [1] - Main functionality tested
                [2] - Edge cases tested
                [3] - Edge cases tested + secure

    Types Handled:

    Login               [0]
    Register            [0]
    AddProduct          [0]
    DeleteProduct       [0]
    EditProduct         [0]
    GetFilteredProducts [0]
    SubmitRating        [0]
    GetRatings          [0]
    DeleteRating        [0]
    EditRating          [0]
    GetStores           [0]
    Follow              [0]
    GetFollowing        [0]
    Unfollow            [0]

*/

include 'config.php';

header('Content-Type: application/json'); 

$_POST = json_decode(file_get_contents("php://input"), true);



//For user we have the following login and registartion
//login
if ($_POST['type'] == 'Login') {
=

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => [
                    "user_id" => $user['user_id'],
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "user_type" => $user['user_type']
                ]
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    }

    exit();
}

//registartion
if ($_POST['type'] == 'Register') 
{

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];
    $registrationNo = $_POST['registrationNo'] ?? null;

    $check = $conn->prepare("SELECT * FROM User WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists."]);
        exit();
    }


    //$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $apiKey = bin2hex(random_bytes(16));

    $stmt = $conn->prepare("
        INSERT INTO User (name, email, password, user_type, apiKey)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $name, $email, $hashedPassword, $user_type, $apiKey);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        if ($user_type === "Store Owner" && $registrationNo) {
            $ownerStmt = $conn->prepare("INSERT INTO StoreOwner (user_id, registrationNo) VALUES (?, ?)");
            $ownerStmt->bind_param("is", $user_id, $registrationNo);
            $ownerStmt->execute();
        }

        echo json_encode([
            "status" => "success",
            "message" => "User registered successfully.",
            "user_id" => $user_id
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to register user."]);
    }

    exit();
}

//now we have the following for products(add/delete/edit/remove)
//add product
if ($_POST['type'] == 'AddProduct') 
{

    $title = $_POST['title'];
    $price = $_POST['price'];
    $product_link = $_POST['product_link'];
    $description = $_POST['description'];
    $launch_date = $_POST['launch_date'];
    $thumbnail = $_POST['thumbnail'];
    $category = $_POST['category'];
    $brand_id = $_POST['brand_id'];


    $stmt = $conn->prepare("
        INSERT INTO Product (title, price, product_link, description, launch_date, thumbnail, category, brand_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sdsssssi", $title, $price, $product_link, $description, $launch_date, $thumbnail, $category, $brand_id);

    if ($stmt->execute()) 
    {
        echo json_encode([
            "status" => "success",
            "message" => "Product added successfully to the database",
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add product to the database."
        ]);
    }

    exit();
}


//delete product
if ($_POST['type'] == 'DeleteProduct') 
{

    $prod_id = $_POST['prod_id'];

    $stmt = $conn->prepare("DELETE FROM Product WHERE prod_id = ?");
    $stmt->bind_param("i", $prod_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Product deleted successfully in the database"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to delete product from the database"
        ]);
    }

    exit();
}


//edit product
if ($_POST['type'] == 'EditProduct') 
{


    $prod_id = $_POST['prod_id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $product_link = $_POST['product_link'];
    $description = $_POST['description'];
    $launch_date = $_POST['launch_date'];
    $thumbnail = $_POST['thumbnail'];
    $category = $_POST['category'];
    $brand_id = $_POST['brand_id'];

    $stmt = $conn->prepare("
        UPDATE Product 
        SET title = ?, price = ?, product_link = ?, description = ?, launch_date = ?, thumbnail = ?, category = ?, brand_id = ?
        WHERE prod_id = ?
    ");

    $stmt->bind_param("sdsssssii", $title, $price, $product_link, $description, $launch_date, $thumbnail, $category, $brand_id, $prod_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Product updated successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update product."
        ]);
    }

    exit();
}

//filter products
if ($_POST['type'] == 'GetFilteredProducts') 
{


    
    $brand_id = $_POST['brand_id'] ?? null;
    $category = $_POST['category'] ?? null;
    $min_price = $_POST['min_price'] ?? null;
    $max_price = $_POST['max_price'] ?? null;
    $search = $_POST['search'] ?? null;
    $sort_by = $_POST['sort_by'] ?? 'launch_date';
    $order = $_POST['order'] ?? 'desc';

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

    $allowedSort = ['price', 'title', 'launch_date'];
    $allowedOrder = ['asc', 'desc'];

    if (in_array($sort_by, $allowedSort) && in_array(strtolower($order), $allowedOrder)) {
        $sql .= " ORDER BY $sort_by " . strtoupper($order);
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

    echo json_encode([
        "status" => "success",
        "data" => $products
    ]);
    exit();
}

//now we have the follwing for rating
//Submit Rating
if ($_POST['type'] == 'SubmitRating') 
{
    $prod_id = $_POST['prod_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO Rating (prod_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $prod_id, $rating, $comment);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Rating submitted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to submit rating."]);
    }
    exit();

}

// Get All Ratings for a Product
if ($_POST['type'] == 'GetRatings') {
    header('Content-Type: application/json');

    $prod_id = $_POST['prod_id'];

    $stmt = $conn->prepare("
        SELECT u.name, r.rating, r.comment, r.date
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

    echo json_encode(["status" => "success", "data" => $ratings]);
    exit();
}

//Delete Rating
if ($_POST['type'] == 'DeleteRating') {
    header('Content-Type: application/json');

    $rating_id = $_POST['rating_id'];

    $stmt = $conn->prepare("DELETE FROM Rating WHERE rating_id = ?");
    $stmt->bind_param("i", $rating_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Rating deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete rating."]);
    }
    exit();
}

//Edit Rating
if ($_POST['type'] == 'EditRating') {
    header('Content-Type: application/json');

    $rating_id = $_POST['rating_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("UPDATE Rating SET rating = ?, comment = ? WHERE rating_id = ?");
    $stmt->bind_param("isi", $rating, $comment, $rating_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Rating updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update rating."]);
    }
    exit();
}

//Fetch all the available stores. I made apiKey required but I can change it to only need the type
if ($_POST['type'] == "GetStores"){
    global $conn;

    // Validate API key exists
    if (!isset($_POST['apiKey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $apiKey = $_POST['apiKey'];

    // Retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apiKey = ?");
    $authQuery->bind_param("s", $apiKey);
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

    $user = $authResult->fetch_assoc();
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
if ($_POST['type'] == 'Follow') {

    //Set connection variable [Might need to change depending on the config file]
    global $conn;

    //I used store_name but we can change it to store_is
    if (!isset($_POST['apiKey']) || !isset($_POST['store_id'])){
        http_response_code(400);
        echo json_encode(["status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $store_id = $_POST['store_id'];
    $apiKey = $_POST['apiKey'];

    //retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apiKey = ?");
    $authQuery->bind_param("s", $apiKey);
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

    //Set connection variable [Might need to change depending on the config file]
    global $conn;

    if (!isset($_POST['apiKey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $apiKey = $_POST['apiKey'];

    //retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apiKey = ?");
    $authQuery->bind_param("s", $apiKey);
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

    //Set connection variable [Might need to change depending on the config file]
    global $conn;

    if (!isset($_POST['apiKey']) || !isset($_POST['store_id'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
    }

    $store_id = $_POST['store_id'];
    $apiKey = $_POST['apiKey'];

    //retrieve user
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apiKey = ?");
    $authQuery->bind_param("s", $apiKey);
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

?>