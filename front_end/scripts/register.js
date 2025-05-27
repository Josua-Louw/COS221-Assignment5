

function showError(message) {
    const errorEl = document.getElementById('errorMessage');
    errorEl.textContent = message;
    errorEl.style.display = message ? 'block' : 'none';
}

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

  const name  = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
 
  showError('');

  if (!name || !email || !password) {
    showError('Please fill in all required fields.');
    return;
  }

  const registerBody = {
    'type': 'Register',
    'name': name,
    'email': email,
    'password': password,
    'user_type': 'Customer'
  }

    const register = new XMLHttpRequest();

    register.onload = function() {
        if (this.readyState === 4) {
            try {
                const response = JSON.parse(this.responseText);
                if (this.status === 200 || this.status === 201) {
                    alert(response.message || 'Registration successful! Please login.');
                    window.location.href = 'login.php';
                } else {
                    showError(response.message || 'Registration failed');
                }
            } catch (e) {
                showError('Invalid server response');
                console.error('Failed to parse response:', this.responseText);
            }
        }
    };

    register.onerror = function() {
        showError('Unable to connect to the server. Please try again later.');
    };

    register.open("POST", "http://localhost/COS221-Assignment5/api/api.php", true);
    register.setRequestHeader("Content-Type", "application/json");
    register.send(JSON.stringify(registerBody));
});
