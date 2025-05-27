// All JavaScript files that use the API must be in a PHP file that requires header.php
// Call the API and return a JSON object of whatever the API returns
async function sendRequest(body) {
  try {
    const response = await fetch("http://localhost/COS221-Assignment5/api/api.php", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(body)
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
  } catch (error) {
    console.error('Error in sendRequest:', error);
    return {
      status: 'error',
      message: error.message
    };
  }
}

document.addEventListener('DOMContentLoaded', function () {

  function setTheme(theme) {
    document.documentElement.classList.remove('light-theme', 'dark-theme');
    document.documentElement.classList.add(`${theme}-theme`);
    sessionStorage.setItem('theme', theme);
  
    const lbl = document.getElementById('themeLabel');
    if (lbl) lbl.textContent = theme[0].toUpperCase() + theme.slice(1);
  
    const logoLight = document.getElementById('logoLight');
    const logoDark = document.getElementById('logoDark');
    if (logoLight && logoDark) {
      if (theme === 'dark') {
        logoLight.style.display = 'none';
        logoDark.style.display = 'block';
      } else {
        logoLight.style.display = 'block';
        logoDark.style.display = 'none';
      }
    }
  
    const storedUser = sessionStorage.getItem('user') || localStorage.getItem('user');
    if (storedUser) {
      try {
        const userObj = JSON.parse(storedUser);
        userObj.theme = theme;
        sessionStorage.setItem('user', JSON.stringify(userObj));
        if (localStorage.getItem('user')) {
          localStorage.setItem('user', JSON.stringify(userObj));
        }
      } catch (e) {
        console.error('Error updating user theme:', e);
      }
    }
  }
  
  let theme = sessionStorage.getItem('theme');
  if (!theme) {
    const storedUser = sessionStorage.getItem('user') || localStorage.getItem('user');
    if (storedUser) {
      try {
        const userObj = JSON.parse(storedUser);
        theme = userObj.theme || 'light';
      } catch (e) {
        theme = 'light';
      }
    } else {
      theme = 'light';
    }
  }
  setTheme(theme);

  const checkbox = document.getElementById('themeToggle');
  if (checkbox) {
    checkbox.checked = (theme === 'dark');
    checkbox.addEventListener('change', () => {
      const newTheme = checkbox.checked ? 'dark' : 'light';
      setTheme(newTheme);
    });
  } else {
    console.warn('themeToggle checkbox not found');
  }

  const currentPage = window.location.pathname.split('/').pop() || 'index.php';
  const links = document.querySelectorAll('.nav-link, .auth-link');
  
  links.forEach(link => {
    const href = link.getAttribute('href');
   
    const linkPage = href?.replace('.php', '');
    const currentPageBase = currentPage.replace('.php', '');
    
    if (linkPage === currentPageBase) {
      link.classList.add('active');
    }
  });
});