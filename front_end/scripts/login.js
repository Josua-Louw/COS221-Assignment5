const API_Location = "http://localhost/COS221-Assignment5/api/api.php";

function showError(message) {
  const errorEl = document.getElementById('errorMessage');
  errorEl.textContent = message;
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

const loginForm = document.getElementById('loginForm');

if (loginForm) {
  loginForm.addEventListener('submit', async function(e) {
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
    };

    const login = new XMLHttpRequest();

login.onload = function () {
  if (this.readyState === 4) {
    if (this.status === 200) {
      try {
        const data = JSON.parse(this.responseText); // ← CORRECT
        if (data.status === 'success' && data.user) {
          localStorage.setItem('user', JSON.stringify(data.user)); // ← CORRECT
          window.location.href = 'index.php';
        } else {
          showError(data.message || 'Login failed');
        }
      } catch (e) {
        showError('Invalid server response');
        console.error('Parse error:', e, this.responseText);
      }
    } else {
      try {
        const data = JSON.parse(this.responseText);
        showError(data.message || 'Login failed');
      } catch {
        showError('Login failed');
      }
    }
  }
};


    login.onerror = function () {
      showError('Unable to connect to the server. Please try again later.');
    };

    login.open("POST", API_Location, true);
    login.setRequestHeader("Content-Type", "application/json");
    login.send(JSON.stringify(loginBody));
  });
}
