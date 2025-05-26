function showMessage(msg, isError = false) {
  const el = document.getElementById('settingsMessage');
  el.textContent = msg;
  el.style.color = isError ? 'red' : 'green';
}

document.addEventListener('DOMContentLoaded', () => {
  const savedTheme = sessionStorage.getItem('theme') || 'light';
  document.getElementById('theme').value = savedTheme;
  document.documentElement.classList.toggle('dark-theme', savedTheme === 'dark');
  const checkbox = document.getElementById('themeToggle');
  if (checkbox) checkbox.checked = (savedTheme === 'dark');
  const prefs = JSON.parse(localStorage.getItem('preferences') || '{}');
  if (prefs.price_min != null && prefs.price_max != null) {
    document.getElementById('price-filter').value =
      `${parseFloat(prefs.price_min)}-${parseFloat(prefs.price_max)}`;
  }
});

document.getElementById('settingsForm').addEventListener('submit', async e => {
  e.preventDefault();
  const apiKey = sessionStorage.getItem('apikey');
  if (!apiKey) {
    showMessage('You must be logged in to alter user settings.', true);
    return;
  }
  const theme = document.getElementById('theme').value;
  const priceRange= document.getElementById('price-filter').value;
  let priceMin = null, priceMax = null;
  if (priceRange && priceRange !== 'all') {
    const [min, max] = priceRange.split('-').map(parseFloat);
    priceMin = min; priceMax = max;
  }
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;

  const payload = {
    type:             'SavePreferences',
    apikey:           apiKey,
    theme:            theme,
    min_price:        priceMin,
    max_price:        priceMax,
  };

  if (document.getElementById('current-email')) {
    const currEmail = document.getElementById('current-email').value.trim();
    const currPassword = document.getElementById('current-password').value;
    const newEmail = document.getElementById('new-email').value.trim();
    const newPassword = document.getElementById('new-password').value;

    if (!currEmail || !currPassword || !newEmail || !newPassword) {
      showMessage('Please fill in all email/password fields.', true);
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(newEmail)) {
      showMessage('Please enter a valid new email address.', true);
      return;
    }
    if (newPassword.length < 8) {
      showMessage('Password does not meet specifications.', true);
      return;
    }

    payload.current_email    = currEmail;
    payload.current_password = currPassword;
    payload.new_email        = newEmail;
    payload.new_password     = newPassword;
  }

  localStorage.setItem('theme', theme);
  sessionStorage.setItem('theme', theme);
  localStorage.setItem('preferences', JSON.stringify({
    price_min: priceMin, price_max: priceMax
  }));
  document.documentElement.classList.toggle('dark-theme', theme === 'dark');
  
  try {
    const data = await fetch('api/api.php', {
      method: 'POST',
      headers: { 'Content-Type':'application/json' },
      body: JSON.stringify(payload)
    }).then(r => r.json());

    if (data.status === 'success') {
        showMessage('Settings saved!');
        if (data.data && data.data.preferences) {
            const p = data.data.preferences;
            if (p.price_min != null && p.price_max != null) {
                p.price_min = parseFloat(p.price_min).toFixed(2);
                p.price_max = parseFloat(p.price_max).toFixed(2);
            }
            localStorage.setItem('preferences', JSON.stringify(p));
        }
    } else {
      showMessage(data.message || 'Save failed', true);
    }
  } catch (err) {
    console.error(err);
    showMessage('Server error – try again later', true);
  }
});
