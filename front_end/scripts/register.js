const typeEl = document.getElementById('user_type');
const regNoField = document.getElementById('registrationNoField');
typeEl.addEventListener('change', () => {
  if (typeEl.value === 'Store Owner') {
    regNoField.style.display = 'block';
    document.getElementById('registrationNo').setAttribute('required', '');
  } else {
    regNoField.style.display = 'none';
    document.getElementById('registrationNo').removeAttribute('required');
  }
});

function showError(message) {
  const errorEl = document.getElementById('errorMessage');
  errorEl.textContent = message;
}

document.getElementById('registerForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const name  = document.getElementById('name').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const user_type = typeEl.value;
  const registrationNo = (user_type === 'Store Owner') 
    ? document.getElementById('registrationNo').value.trim()
    : '';
  showError('');

  if (!name || !email || !password || !user_type) {
    showError('Please fill in all required fields.');
    return;
  }

  const formData = new FormData();
  formData.append('type', 'Register');
  formData.append('name', name);
  formData.append('email', email);
  formData.append('password', password);
  formData.append('user_type', user_type);
  if (user_type === 'Store Owner') {
    formData.append('registrationNo', registrationNo);
  }

  try {
    const res = await fetch('api.php', {
      method: 'POST',
      body: formData
    });
    const data = await res.json();

    if (data.status === 'success') {
      alert('Registration successful! Please login.');
      window.location.href = 'login.php';
    } else {
      showError(data.message || 'Registration failed');
    }
  } catch (err) {
    console.error(err);
    showError('Unable to connect to the server. Please try again later.');
  }
});
