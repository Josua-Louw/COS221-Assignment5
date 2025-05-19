async function handleLogin(email, password) {
    const response = await fetch('/api/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
    });
    const data = await response.json();
    if (data.success) {
        window.location.href = data.role === 'store_owner' ? '/dashboard' : '/products';
    } else {
        alert('Login failed: ' + data.error);
    }
}

// Attach to login form
document.getElementById('login-form').addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    handleLogin(email, password);
});
