<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form id="registerForm">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="user_type">User Type</label>
                <select id="user_type" name="user_type" required>
                    <option value="">Select user type</option>
                    <option value="customer">Customer</option>
                    <option value="store_owner">Store Owner</option>
                </select>
            </div>
            <div class="form-group" id="registrationNoField">
                <label for="registrationNo">Registration Number</label>
                <input type="text" id="registrationNo" name="registrationNo">
            </div>
            <button type="submit">Register</button>
            <div id="errorMessage" class="error-message"></div>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
<script src="scripts/register.js"></script>
