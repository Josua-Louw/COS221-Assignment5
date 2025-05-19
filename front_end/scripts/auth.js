
async function handleLogin(email, password) {
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
        const response = await fetch('/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            window.location.href = data.role === 'store_owner' ? '/dashboard' : '/products';
        } 
        else {
            showError(data.error || 'Invalid email or password.');
        }
    } 

    catch (error) {
        console.error('Login error:', error);
        showError('Unable to connect to the server. Please try again later.');
    }
}

function showError(message) {
    const errorElement = document.getElementById('error-message');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = message ? 'block' : 'none';
    }
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

document.getElementById('login-form')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    handleLogin(email, password);
});
