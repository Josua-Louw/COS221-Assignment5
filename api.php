
<?php
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
if ($_POST['type'] == 'SubmitRating') {
    header('Content-Type: application/json');

    $user_id = $_POST['user_id'];
    $prod_id = $_POST['prod_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO Rating (user_id, prod_id, rating, comment) VALUES (?, ?, ?, ?)");
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





?>