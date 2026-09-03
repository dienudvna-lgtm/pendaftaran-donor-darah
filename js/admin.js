(function () {
  const ADMIN_USER = 'panitiapmi26';
  const ADMIN_PASS = 'BloodConnect@2026Secure!';
  const AUTH_KEY = 'bloodconnect-admin-auth';

  function isAdminAuthenticated() {
    return localStorage.getItem(AUTH_KEY) === 'true';
  }

  function authenticateAdmin() {
    localStorage.setItem(AUTH_KEY, 'true');
  }

  function logoutAdmin() {
    localStorage.removeItem(AUTH_KEY);
  }

  function renderLoginPage() {
    const form = document.getElementById('adminLoginForm');
    if (!form) return;

    const error = document.getElementById('adminLoginError');
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      const user = document.getElementById('adminUser').value.trim();
      const pass = document.getElementById('adminPass').value;
      if (user === ADMIN_USER && pass === ADMIN_PASS) {
        authenticateAdmin();
        window.location.href = 'dashboard.html';
        return;
      }
      if (error) {
        error.textContent = 'Username atau password panitia salah.';
      }
    });
  }

  function protectAdminPages() {
    if (isAdminAuthenticated()) {
      document.body.classList.add('admin-authenticated');
      return;
    }
    window.location.href = 'login.html';
  }

  function addAdminLogoutLink() {
    const navActions = document.querySelector('.nav-actions');
    if (!navActions) return;
    const logoutButton = document.createElement('button');
    logoutButton.className = 'btn btn-secondary';
    logoutButton.type = 'button';
    logoutButton.textContent = 'Logout Panitia';
    logoutButton.addEventListener('click', () => {
      logoutAdmin();
      window.location.href = 'login.html';
    });
    navActions.appendChild(logoutButton);
  }

  function isAdminLoginPage() {
    return document.getElementById('adminLoginForm') !== null;
  }

  document.addEventListener('DOMContentLoaded', function () {
    if (isAdminLoginPage()) {
      renderLoginPage();
      return;
    }

    protectAdminPages();
    addAdminLogoutLink();
  });
})();
