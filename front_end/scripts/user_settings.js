function showMessage(msg, isError = false) {
  const el = document.getElementById('settingsMessage');
  el.textContent = msg;
  el.style.color = isError ? 'red' : 'green';
}

document.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme') || 'light';
  document.getElementById('theme').value = savedTheme;
  document.body.classList.toggle('dark-theme', savedTheme === 'dark');
  const prefs = JSON.parse(localStorage.getItem('preferences') || '{}');
  if (prefs.price_min != null && prefs.price_max != null) {
    document.getElementById('price-filter').value =
      `${parseFloat(prefs.price_min)}-${parseFloat(prefs.price_max)}`;
  }
});

document.getElementById('settingsForm').addEventListener('submit', async e => {
  e.preventDefault();
  const apiKey = localStorage.getItem('apikey');
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
    type:             'update_settings',
    apikey:           apiKey,
    preferred_theme:  theme,
    price_min:        priceMin,
    price_max:        priceMax,
  };

  if (email) payload.new_email = email;
  if (password) payload.new_password = password;

  localStorage.setItem('theme', theme);
  localStorage.setItem('preferences', JSON.stringify({
    price_min: priceMin, price_max: priceMax
  }));
  document.body.classList.toggle('dark-theme', theme === 'dark');
  
  try {
    const data = await sendRequest(payload);

    if (data.status === 'success') {
        showMessage('Settings saved!');
        if (data.data && data.data.preferences) {
            const p = data.data.preferences;
            if (p.price_min != null && p.price_max != null) {
                p.price_min = parseFloat(p.price_min).toFixed(2);
                p.price_max = parseFloat(p.price_max).toFixed(2);
            }
            Cookies.set('preferences', JSON.stringify(p), { expires: 7, path: '/' });
            console.log('Updated preferences in cookies:', p);
        }
    } else {
      showMessage(data.message || 'Save failed', true);
    }
  } catch (err) {
    console.error(err);
    showMessage('Server error – try again later', true);
  }
});
