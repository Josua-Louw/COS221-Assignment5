const typeEl = document.getElementById('user_type');
const regNoField = document.getElementById('registrationNoField');

// Initialize visibility based on current value
updateRegistrationNoVisibility();

typeEl.addEventListener('change', updateRegistrationNoVisibility);

function updateRegistrationNoVisibility() {
    if (typeEl.value === 'store_owner') {
        regNoField.style.display = 'block';
        document.getElementById('registrationNo').setAttribute('required', '');
    } else {
        regNoField.style.display = 'none';
        document.getElementById('registrationNo').removeAttribute('required');
    }
}

function showError(message) {
    const errorEl = document.getElementById('errorMessage');
    errorEl.textContent = message;
    errorEl.style.display = message ? 'block' : 'none';
}

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const user_type = typeEl.value;
    const registrationNo = (user_type === 'store_owner') 
        ? document.getElementById('registrationNo').value.trim()
        : '';
    
    showError('');

    // Basic validation
    if (!name || !email || !password || !user_type) {
        showError('Please fill in all required fields.');
        return;
    }

    // Additional validation for store owners
    if (user_type === 'store_owner' && !registrationNo) {
        showError('Registration number is required for store owners.');
        return;
    }

    const registerBody = {
        'type': 'Register',
        'name': name,
        'email': email,
        'password': password,
        'user_type': user_type
    };

    if (user_type === 'store_owner') {
        registerBody.registrationNo = registrationNo;
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
