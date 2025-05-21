
function showMessage(msg, isError = false) {
  const el = document.getElementById('settingsMessage');
  el.textContent = msg;
  el.style.color = isError ? 'red' : 'green';
}

document.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme') || 'light';
  document.getElementById('theme').value = savedTheme;
  document.body.classList.toggle('dark-theme', savedTheme === 'dark');
  
  // maybe also pre‐select stores based on saved prefs??
});

document.getElementById('settingsForm').addEventListener('submit', async e => {
  e.preventDefault();

  const theme = document.getElementById('theme').value;
  const storeOptions = Array.from(document.getElementById('stores').selectedOptions);
  const stores = storeOptions.map(o => o.value);

  localStorage.setItem('theme', theme);
  document.body.classList.toggle('dark-theme', theme === 'dark');

  const payload = { type: 'SaveSettings', theme, stores };
  try {
    const res = await fetch('api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    const data = await res.json();

    if (data.status === 'success') {
      showMessage('Settings saved!');
    } else {
      showMessage(data.message || 'Save failed', true);
    }
  } catch (err) {
    console.error(err);
    showMessage('Server error – try again later', true);
  }
});
