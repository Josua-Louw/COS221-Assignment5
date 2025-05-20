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

  try {
    const res = await fetch('api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `type=Login&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    });
    const data = await res.json();

    if (data.status === 'success') {
      localStorage.setItem('user', JSON.stringify(data.user));
      window.location.href = 'index.php';
    } else {
      showError(data.message || 'Login failed');
    }
  } catch (err) {
    console.error(err);
    showError('Unable to connect to the server. Please try again later.');
  }
});
