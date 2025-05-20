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

require_once 'config.php';

header('Content-Type: application/json'); 

$_POST = json_decode(file_get_contents("php://input"), true);

$conn = Database::instance()->getConnection(); //created the connection to have global scope. Not sure if local scope would be safer?

//For user we have the following login and registartion
//login
if ($_POST['type'] == 'Login') {


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


    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
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
//getallproducts
if ($_POST['type'] == 'GetAllProducts') {

    $stmt = $conn->prepare("SELECT * FROM Product");

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'price' => $row['price'],
                'product_link' => $row['product_link'],
                'description' => $row['description'],
                'launch_date' => $row['launch_date'],
                'thumbnail' => $row['thumbnail'],
                'category' => $row['category'],
                'brand_id' => $row['brand_id'],
            ];
        }

        echo json_encode([
            "status" => "success",
            "message" => "Products fetched successfully",
            "data" => $products
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to fetch products from the database."
        ]);
    }

    exit();
}


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
    $Store_id = $_POST['store_id'];


    $stmt = $conn->prepare("
        INSERT INTO Product (title, price, product_link, description, launch_date, thumbnail, category, brand_id, store_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sdsssssii", $title, $price, $product_link, $description, $launch_date, $thumbnail, $category, $brand_id,$Store_id);

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
    $Store_id = $_POST['store_id'];

    $stmt = $conn->prepare("
        UPDATE Product 
        SET title = ?, price = ?, product_link = ?, description = ?, launch_date = ?, thumbnail = ?, category = ?, brand_id = ?, store_id = ?
        WHERE prod_id = ?
    ");

    $stmt->bind_param("sdsssssiii", $title, $price, $product_link, $description, $launch_date, $thumbnail, $category, $brand_id, $prod_id,$store_id);

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

    echo json_encode(["status" => "success", "data" => $ratings]);
    exit();
}

//Delete Rating
if ($_POST['type'] == 'DeleteRating') {
  

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
if ($_POST['type'] == "GetStores")
{

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
if ($_POST['type'] == 'Follow') 
{


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

    if ($_POST['type'] == 'RegisterStoreOwner') {
     

    //Check if all fields are present
    if (!isset($_POST['apiKey']) || !isset($_POST['store_name']) || !isset($_POST['store_url']) || !isset($_POST['type'])){
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $store_name = $_POST['store_name'];
    $store_url = $_POST['store_url'];
    $apiKey = $_POST['apiKey'];
    $type = $_POST['type'];

    //Check if user exists
    $authQuery = $conn->prepare("SELECT id FROM user WHERE apiKey = ?");
    $authQuery->bind_param("s", $apiKey);
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
}

?>