function showError(message) {
  const errorEl = document.getElementById('errorMessage');
  errorEl.textContent = message;
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

document.getElementById('loginForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  showError('');

  if (!email || !password) {
    showError('Please fill in both email and password.');
    return;
  }
  if (!validateEmail(email)) {
    showError('Please enter a valid email address.');
    return;
  }

  const loginBody = {
    'type': 'Login',
    'email': email,
    'password': password,
  }

  const login = new XMLHttpRequest;

  login.onload = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        const data = JSON.stringify(this.responseText);
        localStorage.setItem('user', JSON.stringify(data.user));
        window.location.href = 'index.php';
      } else {
        try {
          const data = JSON.parse(this.responseText)
          showError(data.message || 'Login failed');
        } catch {
          showError('Login failed');
        }
      }
    }
  }

  login.onerror = function () {
    showError('Unable to connect to the server. Please try again later.');
  }

  login.open("POST", process.env.API_Location, true);
  login.setRequestHeader("Content-Type","application/json");
  login.send(JSON.stringify(loginBody));
});
