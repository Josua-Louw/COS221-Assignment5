<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/auth_functions.php';

if (empty($_POST['email']) || empty($_POST['password'])) {
  http_response_code(400);
  die(json_encode(['error' => 'Email and password are required.']));
}

$email = $_POST['email'];
$password = $_POST['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  die(json_encode(['error' => 'Invalid email format.']));
}

$stmt = $pdo->prepare("SELECT id, password, role FROM user WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
  http_response_code(401);
  die(json_encode(['error' => 'Invalid email or password.']));
}

// Success
session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];
echo json_encode(['success' => true, 'role' => $user['role']]);
?>
