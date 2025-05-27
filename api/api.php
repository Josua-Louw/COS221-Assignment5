<?php


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
        "message" => "Database errorsssss",
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
    //count login attempts
    session_start();
    if (isset($_SESSION["LoginBlock"])) {
        if (time() - $_SESSION["LoginBlock"] >= 60) {//can be extended but for demo cases we will keep it short
            $_SESSION["LoginAttempts"] = 0;
            unset($_SESSION['LoginBlock']);
        } else {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Login is still blocked just wait a bit longer.",
                "Type Handler" => "Login",
                "API Line" => __LINE__
            ]);
            exit();
        }
    }
    if (!isset($_SESSION["LoginAttempts"])) {
        $_SESSION["LoginAttempts"] = 0;
    } else {
        $_SESSION["LoginAttempts"] += 1;
        if ($_SESSION["LoginAttempts"] >= 5) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Too many failed attempts. Try again in a minute.",
                "Type Handler" => "Login",
                "API Line" => __LINE__
            ]);
            $_SESSION["LoginBlock"] = time();
            exit();
        }
    }

    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.",
            "Type Handler" => "Login",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION["apikey"] = $user['apikey'];

        unset($user['password'], $user['salt']);
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => $user
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
    $required = ['name', 'email', 'password'];
    foreach ($required as $field) {
        if (!isset($_POST[$field])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Missing required field: $field"
            ]);
            exit();
        }
    }

    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $user_type = "customer";

    try {
        $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            http_response_code(409);
            echo json_encode([
                "status" => "error",
                "message" => "Email already registered"
            ]);
            exit();
        }
        $check->close();

        $salt = bin2hex(random_bytes(127)); // Required field
        $hashedPassword = hash_pbkdf2("sha256", $password, $salt, 10000, 127);
        $apikey = bin2hex(random_bytes(32));
        $date_registered = date("Y-m-d");

        $conn->begin_transaction();

        // Include `salt` and `date_registered`
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password, salt, user_type, apikey, date_registered)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssssss", $name, $email, $hashedPassword, $salt, $user_type, $apikey, $date_registered);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO customers (user_id) VALUES (?)");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Registration successful"
        ]);

    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "Register", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "Register", __LINE__, true);
    }
    exit();
}


if ($_POST['type'] == 'GetProductsWithRatings') {
    $stmt = $conn->prepare("
        SELECT p.*, AVG(r.rating) as average_rating 
        FROM products p
        LEFT JOIN ratings r ON p.product_id = r.product_id
        GROUP BY p.product_id
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode([
        "status" => "success",
        "products" => $products
    ]);
    exit();
}

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

    $title = sanitizeInput($_POST['title']);
    $price = sanitizeInput($_POST['price']);
    $product_link = sanitizeInput($_POST['product_link']);
    $description = sanitizeInput($_POST['description']);
    $launch_date = sanitizeInput($_POST['launch_date']);
    $thumbnail = sanitizeInput($_POST['thumbnail']);
    $category = sanitizeInput($_POST['category']);
    $store_id = sanitizeInput($_POST['store_id']);
    $apikey = sanitizeInput($_POST['apikey']);
    $user_id = authenticate($conn, $apikey);

    if (!isset($_POST['brand_name'])) {
        $brand_name = sanitizeInput($_POST['title']);
    } else {
        $brand_name = sanitizeInput($_POST['brand_name']);
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
        INSERT INTO products (title, thumbnail,launch_date, product_link,price,  description,  category,  store_id,brand_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdssii", $title, $thumbnail, $launch_date,$product_link, $price, $description,  $category, $store_id,$brand_id);
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

    $prod_id = sanitizeInput($_POST['prod_id']);
    $apikey = sanitizeInput($_POST['apikey']);
    $store_id = sanitizeInput($_POST['store_id']);

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
        $productCheck = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND store_id = ?");
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

        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
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

    $prod_id = sanitizeInput($_POST['prod_id']);
    $apikey = sanitizeInput($_POST['apikey']);
    $store_id = sanitizeInput($_POST['store_id']);

    $requiredFields = ['title', 'price', 'product_link', 'description', 'launch_date', 'thumbnail', 'category'];
    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Missing required field: $field",
                "Type Handler" => "EditProduct",
                "API Line" => __LINE__
            ]);
            exit();
        }
    }
    if (isset($_POST['brand_id'])) {
    $brand_id = $_POST['brand_id'];
} elseif (isset($_POST['brand_name'])) {
    
    $brand_name = $_POST['brand_name'];
    $brandStmt = $conn->prepare("SELECT brand_id FROM brand WHERE name = ?");
    $brandStmt->bind_param("s", $brand_name);
    $brandStmt->execute();
    $brandResult = $brandStmt->get_result();
    if ($brandResult->num_rows > 0) {
        $brand_id = $brandResult->fetch_assoc()['brand_id'];
    } else {
        $brand_id = createBrand($conn, $brand_name, false);
    }
    $brandStmt->close();
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Missing required field: brand_id or brand_name",
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
        $productCheck = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND store_id = ?");
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

    $title = !isset($_POST['title']) ? $products['title'] : sanitizeInput($_POST['title']);
    $price = !isset($_POST['price']) ? $products['price'] : sanitizeInput($_POST['price']);
    $product_link = !isset($_POST['product_link']) ? $products['product_link'] : sanitizeInput($_POST['product_link']);
    $description = !isset($_POST['description']) ? $products['description'] : sanitizeInput($_POST['description']);
    $launch_date = !isset($_POST['launch_date']) ? $products['launch_date'] : sanitizeInput($_POST['launch_date']);
    $thumbnail = !isset($_POST['thumbnail']) ? $products['thumbnail'] : sanitizeInput($_POST['thumbnail']);
    $category = !isset($_POST['category']) ? $products['category'] : sanitizeInput($_POST['category']);
    //$brand_id = !isset($_POST['brand_id']) ? $products['brand_id'] : sanitizeInput($_POST['brand_id']);

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("UPDATE products
        SET title = ?, price = ?, product_link = ?, description = ?, launch_date = ?, thumbnail = ?, category = ?, brand_id = ?, store_id = ?
        WHERE product_id = ?");
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

if ($_POST['type'] == 'GetFilteredProducts')
{
    $brand_id = $_POST['brand_id'] ?? null;
    $category = $_POST['category'] ?? null;
    $min_price = $_POST['min_price'] ?? null;
    $max_price = $_POST['max_price'] ?? null;
    $search = $_POST['search'] ?? null;
    $store_id = $_POST['store_id'] ?? null;
    $min_rating = $_POST['min_rating'] ?? null;

    // Unified query selecting brand and rating info
    $sql = "SELECT p.*, b.name AS brand_name, AVG(r.rating) AS average_rating
            FROM products p
            LEFT JOIN brand b ON p.brand_id = b.brand_id
            LEFT JOIN ratings r ON p.product_id = r.product_id
            WHERE 1=1";

    $params = [];
    $types = "";

    if (!empty($brand_id)) {
        $sql .= " AND p.brand_id = ?";
        $params[] = $brand_id;
        $types .= "i";
    }

    if (!empty($category)) {
        $sql .= " AND p.category = ?";
        $params[] = $category;
        $types .= "s";
    }

    if (!empty($min_price)) {
        $sql .= " AND p.price >= ?";
        $params[] = $min_price;
        $types .= "d";
    }

    if (!empty($max_price)) {
        $sql .= " AND p.price <= ?";
        $params[] = $max_price;
        $types .= "d";
    }

    if (!empty($search)) {
        $sql .= " AND p.title LIKE ?";
        $params[] = '%' . $search . '%';
        $types .= "s";
    }

    if (!empty($store_id)) {
        $sql .= " AND p.store_id = ?";
        $params[] = $store_id;
        $types .= "i";
    }

    $sql .= " GROUP BY p.product_id";

    if (!empty($min_rating)) {
        $sql .= " HAVING average_rating >= ?";
        $params[] = $min_rating;
        $types .= "d";
    }

    $sql .= " ORDER BY average_rating ASC";

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
        //http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "SubmitRating",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = sanitizeInput($_POST['apikey']);
    $prod_id = sanitizeInput($_POST['prod_id']);
    $rating = sanitizeInput($_POST['rating']);
    $comment = sanitizeInput($_POST['comment']);
    $user_id = authenticate($conn, $apikey);
    $date = date("Y-m-d");

    try {
        $stmt = $conn->prepare("SELECT * FROM ratings WHERE user_id_ratings = ? AND product_id = ?;");
        $stmt->bind_param("ii", $user_id, $prod_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
             http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "You have already rated this product.",
                "Type Handler" => "SubmitRating",
                "API Line" => __LINE__
            ]);
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "SubmitRating", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "SubmitRating", __LINE__);
    }

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("INSERT INTO ratings (rating, comment,product_id,user_id_ratings, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiis", $rating, $comment,$prod_id,$user_id, $date);
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
if ($_POST['type'] === 'GetRatings') {
    if (!isset($_POST['prod_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Product ID is required",
            "line" => __LINE__
        ]);
        exit();
    }

    $prod_id = (int)sanitizeInput($_POST['prod_id']);

    $stmt = $conn->prepare("
    SELECT r.rating_id, r.rating, r.comment, u.name, u.user_id
    FROM ratings r
    JOIN users u ON r.user_id_ratings = u.user_id
    WHERE r.product_id = ?
");
    $stmt->bind_param("i", $prod_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $ratings = [];
    while ($row = $result->fetch_assoc()) {
        $ratings[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $ratings
    ]);
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

    $apikey = sanitizeInput($_POST['apikey']);
    $rating_id = sanitizeInput($_POST['rating_id']);

    $user_id = authenticate($conn, $apikey);

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("DELETE FROM ratings WHERE rating_id = ?");
        $stmt->bind_param("i", $rating_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
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
    if (!isset($_POST['prod_id']) || !isset($_POST['apikey']) || !isset($_POST['rating']) || !isset($_POST['comment'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "EditRating",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = sanitizeInput($_POST['apikey']);
    $product_id = sanitizeInput($_POST['prod_id']);
    $rating = sanitizeInput($_POST['rating']);
    $comment = sanitizeInput($_POST['comment']);

    $user_id = authenticate($conn, $apikey);

    try {
        $checkStmt = $conn->prepare("SELECT * FROM ratings WHERE product_id = ? AND user_id_ratings = ?");
        $checkStmt->bind_param("ii", $product_id, $user_id);
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

        $stmt = $conn->prepare("UPDATE ratings SET rating = ?, comment = ? WHERE product_id = ? AND user_id_ratings = ?");
        $stmt->bind_param("isii", $rating, $comment, $product_id, $user_id);
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

    $apikey = sanitizeInput($_POST['apikey']);
    $user_id = authenticate($conn, $apikey);

    try {
        $ownerStmt = $conn->prepare("SELECT * FROM store_owner WHERE user_id = ?");
        $ownerStmt->bind_param("i", $user_id);
        $ownerStmt->execute();
        $ownerResult = $ownerStmt->get_result();

        if ($ownerResult->num_rows == 0) {
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "User has no store",
        "data" => null
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

    $store_id = sanitizeInput($_POST['store_id']);
    $apikey = sanitizeInput($_POST['apikey']);
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

    $apikey = sanitizeInput($_POST['apikey']);
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

    $store_id = sanitizeInput($_POST['store_id']);
    $apikey = sanitizeInput($_POST['apikey']);
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

    $store_name = sanitizeInput($_POST['store_name']);
    $store_url = sanitizeInput($_POST['store_url']);
    $apikey = sanitizeInput($_POST['apikey']);
    $type = sanitizeInput($_POST['store_type']);
    $registrationNo = sanitizeInput($_POST['registrationNo']);
    $user_id = authenticate($conn, $apikey);
    if (!$user_id) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing API key"
    ]);
    exit();
}
    //Check to see if user already has a store
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM store_owner WHERE user_id = ?");
    $checkStmt->bind_param("i", $user_id);
    $checkStmt->execute();
    $checkStmt->bind_result($storeCount);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($storeCount > 0) {
        error_log("RegisterStoreOwner: User $user_id already owns a store.");
        http_response_code(409); // Conflict
        echo json_encode([
            "status" => "error",
            "message" => "User already owns a store",
            "Type Handler" => "RegisterStoreOwner",
            "API Line" => __LINE__
    ]);
    exit();
}
    //Add store to database
    try {
        $conn->begin_transaction();

        $storeStmt = $conn->prepare("INSERT INTO store (name, url, type) VALUES (?, ?, ?)");
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
        $ownerStmt = $conn->prepare("Insert into store_owner (user_id, store_id, registration_no) VALUES (?, ?, ?)");
        $ownerStmt->bind_param("iis", $user_id, $store_id, $registrationNo);
        $ownerStmt->execute();
        $ownerStmt->close();

        $updateTypeStmt = $conn->prepare("UPDATE users SET user_type = 'store_owner' WHERE user_id = ?");
        $updateTypeStmt->bind_param("i", $user_id);
        $updateTypeStmt->execute();
        $updateTypeStmt->close();

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

    $store_id = sanitizeInput($_POST['store_id']);

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

    $apikey = sanitizeInput($_POST['apikey']);
    $brand_name = sanitizeInput($_POST['brand_name']);
    $user_id = authenticate($conn, $apikey);

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

    $apikey = sanitizeInput($_POST['apikey']);
    $brand_id = sanitizeInput($_POST['brand_id']);
    $user_id = authenticate($conn, $apikey);

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

//Gets stats of the user
if ($_POST['type'] == 'GetStats'){
    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "GetStats",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = sanitizeInput($_POST['apikey']);
    $user_id = authenticate($conn, $apikey);
    $stats = [];

    try {
        $stmt = $conn->prepare("
            SELECT p.category, SUM(c.amount) AS total_clicks
            FROM clicks c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?
            GROUP BY p.category
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $clickStats = [];
        while ($row = $result->fetch_assoc()) {
            $clickStats[] = $row;
        }
        $stats['clicksData'] = $clickStats;

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetStats", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetStats", __LINE__);
    }

    try {
        $dateStmt = $conn->prepare("SELECT date_registered, name, min_price, max_price FROM users WHERE user_id = ?");
        $dateStmt->bind_param("i", $user_id);
        $dateStmt->execute();
        $dateResult = $dateStmt->get_result();
        $stats['user'] = $dateResult->fetch_assoc() ?? null;
        $dateStmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetStats", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetStats", __LINE__);
    }

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Stats successfully retrieved",
        "data" => $stats
    ]);
    exit();
}

//Updates / insert user stats
if ($_POST['type'] == 'UpdateStats'){
    if (!isset($_POST['apikey']) || !isset($_POST['product_id'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "UpdateStats",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = sanitizeInput($_POST['apikey']);
    $user_id = authenticate($conn, $apikey);
    $product_id = sanitizeInput($_POST['product_id']);

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("INSERT INTO clicks (user_id, product_id, amount) VALUES (?, ?, 1)
        ON DUPLICATE KEY UPDATE amount = amount + 1");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Stats successfully updated"
        ]);
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "UpdateStats", __LINE__, true);
    } catch (Exception $e) {
        catchError($conn, $e, "UpdateStats", __LINE__, true);
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
        $stmt = $conn->prepare("INSERT INTO brand (name) VALUES (?)");
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

//now we have the following for products(add/delete/edit/remove)
if ($_POST['type'] == 'GetAllProducts')
{
    try {
        $stmt = $conn->prepare("SELECT p.*, average_rating 
        FROM products AS p
        LEFT JOIN (SELECT AVG(r.rating) AS average_rating, product_id FROM ratings AS r GROUP BY r.product_id) AS r ON p.product_id = r.product_id
        ORDER BY average_rating DESC;");
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
                'brand_name' => $brand,
                'average_rating' => $row['average_rating']
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

if ($_POST['type'] == 'SavePreferences')
{

    if (!isset($_POST['theme']) || !isset($_POST['min_price']) || !isset($_POST['max_price']) || !isset($_POST['apikey']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['current_email']) || !isset($_POST['current_password'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
        exit();
    }

    $theme = sanitizeInput($_POST['theme']);
    $min_price = sanitizeInput($_POST['min_price']);
    $max_price = sanitizeInput($_POST['max_price']);
    $apikey = sanitizeInput($_POST['apikey']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $current_email = sanitizeInput($_POST['current_email']);
    $current_password = sanitizeInput($_POST['current_password']);

    $user_id = authenticate($conn, $apikey);

    try {
        $getUserStmt = $conn->prepare("SELECT * FROM users WHERE apikey = ?");
        $getUserStmt->bind_param("s", $apikey);
        $getUserStmt->execute();
        $result = $getUserStmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid API key",
                "Type Handler" => "SubmitPreferences",
                "API Line" => __LINE__
            ]);
            exit();
        }

        $salt = $user['salt'];
        $currentHashed = hash_pbkdf2("sha256", $current_password, $salt, 10000, 127);

        if ($currentHashed !== $user['password'] || $current_email !== $user['email']) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Incorrect credentials",
                "Type Handler" => "SubmitPreferences",
                "API Line" => __LINE__
            ]);
            exit();
        }

        $getUserStmt->close();
    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "SavePreferences", __LINE__);
    } catch (Exception $e) {
        catchErrorSQL($conn, $e, "SavePreferences", __LINE__);
    }

    $hashedPassword = hash_pbkdf2("sha256", $password, $salt, 10000, 127);

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("UPDATE users SET theme = ?, min_price = ? , max_price = ?, email = ?, password = ? WHERE apikey = ?");
        $stmt->bind_param("sddsss", $theme, $min_price, $max_price, $email, $hashedPassword, $apikey);
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

//Gets the preferences of the user
if ($_POST['type'] == 'GetPreferences')
{
    if (!isset($_POST['apikey'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields",
            "Type Handler" => "GetPreferences",
            "API Line" => __LINE__
        ]);
        exit();
    }

    $apikey = sanitizeInput($_POST['apikey']);
    $user_id = authenticate($conn, $apikey);

    try {
        $stmt = $conn->prepare("SELECT theme, min_price, max_price FROM users WHERE apikey = ?");
        $stmt->bind_param("s", $apikey);
        $stmt->execute();
        $prefResult = $stmt->get_result();
        $result = $prefResult->fetch_assoc();
        $stmt->close();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Preferences successfully retrieved",
            "data" => $result
        ]);

    } catch (mysqli_sql_exception $e) {
        catchErrorSQL($conn, $e, "GetPreferences", __LINE__);
    } catch (Exception $e) {
        catchError($conn, $e, "GetPreferences", __LINE__);
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
            return $row['user_id'];
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

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

?>

