
function showError(message) {
  const errorEl = document.getElementById('errorMessage');
  errorEl.textContent = message;
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

  const register = new XMLHttpRequest;

  register.onload = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        alert('Registration successful! Please login.');
        window.location.href = 'login.php';
      } else {
        try {
          const repsonse = JSON.parse(this.responseText)
          showError(repsonse.message || 'Registration failed');
        } catch {
          showError('Registration failed');
        }
      }
    }
  }

  register.onerror = function () {
    showError('Unable to connect to the server. Please try again later.');
  }

  register.open("POST", "http://localhost/COS221-Assignment5/api/api.php", true);
  register.setRequestHeader("Content-Type","application/json");
  register.send(JSON.stringify(registerBody));
});
