<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
        }
        input, select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background-color: #218838;
        }
        .error-message {
            color: red;
            margin-top: 1rem;
            text-align: center;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        #registrationNoField {
            display: none;
        }
    </style>
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
                    <option value="Customer">Customer</option>
                    <option value="Store Owner">Store Owner</option>
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
            Already have an account? <a href="login.html">Login here</a>
        </div>
    </div>

    <script>
        document.getElementById('user_type').addEventListener('change', function() {
            const registrationNoField = document.getElementById('registrationNoField');
            if (this.value === 'Store Owner') {
                registrationNoField.style.display = 'block';
                document.getElementById('registrationNo').setAttribute('required', '');
            } else {
                registrationNoField.style.display = 'none';
                document.getElementById('registrationNo').removeAttribute('required');
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const user_type = document.getElementById('user_type').value;
            const registrationNo = user_type === 'Store Owner' ? document.getElementById('registrationNo').value : '';
            const errorMessage = document.getElementById('errorMessage');
            
            errorMessage.textContent = '';
            
            // Prepare form data
            const formData = new FormData();
            formData.append('type', 'Register');
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('user_type', user_type);
            if (user_type === 'Store Owner') {
                formData.append('registrationNo', registrationNo);
            }
            
            fetch('api.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Registration successful! Please login.');
                    window.location.href = 'login.html';
                } else {
                    errorMessage.textContent = data.message || 'Registration failed';
                }
            })
            .catch(error => {
                errorMessage.textContent = 'An error occurred. Please try again.';
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
