/**
 * PMI Connect Authentication Module
 * Handles login and registration forms, validation, and persistent storage.
 */

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    // Check if user is already logged in
    checkAuthStatus();

    // Handle password visibility toggle buttons
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', togglePasswordVisibility);
    });

    // Handle login form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmit);

        // Real-time validation for login fields
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', validateEmail);
            emailInput.addEventListener('input', function () {
                hideFieldError('emailError');
            });
        }

        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                hideFieldError('passwordError');
            });
        }
    }

    // Handle register form
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegisterSubmit);

        // Real-time validation for register fields
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirm');
        const agreeTermsInput = document.getElementById('agree_terms');

        if (usernameInput) {
            usernameInput.addEventListener('blur', validateUsername);
            usernameInput.addEventListener('input', function () {
                hideFieldError('usernameError');
            });
        }

        if (emailInput) {
            emailInput.addEventListener('blur', validateEmail);
            emailInput.addEventListener('input', function () {
                hideFieldError('emailError');
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('input', function (e) {
                validatePasswordStrength(e);
                hideFieldError('passwordError');
            });
            passwordInput.addEventListener('blur', validatePassword);
        }

        if (passwordConfirmInput) {
            passwordConfirmInput.addEventListener('blur', validatePasswordMatch);
            passwordConfirmInput.addEventListener('input', function () {
                hideFieldError('passwordConfirmError');
            });
        }

        if (agreeTermsInput) {
            agreeTermsInput.addEventListener('change', function () {
                if (agreeTermsInput.checked) {
                    hideFieldError('agreeTermsError');
                }
            });
        }
    }
});

/**
 * Toggle password input visibility (show/hide)
 */
function togglePasswordVisibility(e) {
    e.preventDefault();
    const button = e.currentTarget;
    const wrapper = button.closest('.password-wrapper');
    if (!wrapper) return;

    const input = wrapper.querySelector('input');
    const icon = button.querySelector('i');

    if (input) {
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    }
}

/**
 * Show error on specific field
 */
function showFieldError(elementId, message) {
    const errorEl = document.getElementById(elementId);
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.add('show');
        errorEl.style.display = 'block';
    }
}

/**
 * Hide error on specific field
 */
function hideFieldError(elementId) {
    const errorEl = document.getElementById(elementId);
    if (errorEl) {
        errorEl.textContent = '';
        errorEl.classList.remove('show');
        errorEl.style.display = 'none';
    }
}

/**
 * Clear all form errors and alerts
 */
function clearErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(el => {
        el.textContent = '';
        el.classList.remove('show');
        el.style.display = 'none';
    });

    const generalError = document.getElementById('generalError');
    if (generalError) {
        generalError.textContent = '';
        generalError.classList.remove('show');
        generalError.style.display = 'none';
    }

    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        successMessage.textContent = '';
        successMessage.classList.remove('show');
        successMessage.style.display = 'none';
    }
}

/**
 * Show loading overlay and disable submit button
 */
function showLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.add('show');
        loadingOverlay.style.display = 'flex';
    }
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = true;
    }
}

/**
 * Hide loading overlay and enable submit button
 */
function hideLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('show');
        loadingOverlay.style.display = 'none';
    }
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = false;
    }
}

/**
 * Validate email field
 */
function validateEmail(e) {
    let value = '';
    if (e && e.target && typeof e.target.value !== 'undefined') {
        value = e.target.value.trim();
    } else if (typeof e === 'string') {
        value = e.trim();
    } else {
        const input = document.getElementById('email');
        value = input ? input.value.trim() : '';
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!value) {
        showFieldError('emailError', 'Email tidak boleh kosong.');
        return false;
    }

    if (!emailRegex.test(value)) {
        showFieldError('emailError', 'Format email tidak valid (contoh: nama@domain.com).');
        return false;
    }

    hideFieldError('emailError');
    return true;
}

/**
 * Validate username field
 */
function validateUsername(e) {
    let value = '';
    if (e && e.target && typeof e.target.value !== 'undefined') {
        value = e.target.value.trim();
    } else if (typeof e === 'string') {
        value = e.trim();
    } else {
        const input = document.getElementById('username');
        value = input ? input.value.trim() : '';
    }

    if (!value) {
        showFieldError('usernameError', 'Username tidak boleh kosong.');
        return false;
    }

    if (value.length < 4) {
        showFieldError('usernameError', 'Username minimal 4 karakter.');
        return false;
    }

    hideFieldError('usernameError');
    return true;
}

/**
 * Validate password field
 */
function validatePassword(e) {
    let value = '';
    if (e && e.target && typeof e.target.value !== 'undefined') {
        value = e.target.value;
    } else if (typeof e === 'string') {
        value = e;
    } else {
        const input = document.getElementById('password');
        value = input ? input.value : '';
    }

    if (!value) {
        showFieldError('passwordError', 'Password tidak boleh kosong.');
        return false;
    }

    if (value.length < 8) {
        showFieldError('passwordError', 'Password minimal 8 karakter.');
        return false;
    }

    hideFieldError('passwordError');
    return true;
}

/**
 * Validate password strength and update UI indicator
 */
function validatePasswordStrength(e) {
    let value = '';
    if (e && e.target && typeof e.target.value !== 'undefined') {
        value = e.target.value;
    } else {
        const input = document.getElementById('password');
        value = input ? input.value : '';
    }

    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    if (!strengthBar || !strengthText) return;

    strengthBar.classList.remove('weak', 'medium', 'strong');

    if (!value) {
        strengthText.classList.remove('show');
        strengthText.textContent = '';
        return;
    }

    let score = 0;
    if (value.length >= 8) score += 1;
    if (value.length >= 12) score += 1;
    if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score += 1;
    if (/[0-9]/.test(value)) score += 1;
    if (/[^a-zA-Z0-9]/.test(value)) score += 1;

    strengthText.classList.add('show');

    if (score <= 2) {
        strengthBar.classList.add('weak');
        strengthText.textContent = 'Kekuatan: Lemah (minimal 8 karakter)';
        strengthText.style.color = '#ef4444';
    } else if (score <= 4) {
        strengthBar.classList.add('medium');
        strengthText.textContent = 'Kekuatan: Sedang';
        strengthText.style.color = '#f59e0b';
    } else {
        strengthBar.classList.add('strong');
        strengthText.textContent = 'Kekuatan: Kuat & Aman';
        strengthText.style.color = '#10b981';
    }
}

/**
 * Validate password match (confirmation)
 */
function validatePasswordMatch(e) {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirm');

    const password = passwordInput ? passwordInput.value : '';
    const confirm = confirmInput ? confirmInput.value : '';

    if (!confirm) {
        showFieldError('passwordConfirmError', 'Konfirmasi password tidak boleh kosong.');
        return false;
    }

    if (password !== confirm) {
        showFieldError('passwordConfirmError', 'Password dan konfirmasi password tidak sama.');
        return false;
    }

    hideFieldError('passwordConfirmError');
    return true;
}

/**
 * Check if user is already logged in
 */
function checkAuthStatus() {
    const isAuth = localStorage.getItem('bloodconnect-auth') === 'true';
    const path = (window.location.pathname || '').toLowerCase();
    const isAuthPage = path.endsWith('login.html') || path.endsWith('login.php') || path.endsWith('register.html') || path.endsWith('register.php');

    if (isAuth && isAuthPage) {
        window.location.href = 'home.php';
    }
}

/**
 * Persistent registered users storage for client fallback
 */
function getRegisteredUsersList() {
    try {
        const users = localStorage.getItem('bloodconnect-registered-users');
        return users ? JSON.parse(users) : [];
    } catch (e) {
        return [];
    }
}

function saveRegisteredUserLocally(username, email, password) {
    const users = getRegisteredUsersList();
    const existingIndex = users.findIndex(u => u.email.toLowerCase() === email.toLowerCase());
    const userData = {
        username: username,
        email: email,
        password: password,
        createdAt: new Date().toISOString()
    };
    if (existingIndex >= 0) {
        users[existingIndex] = userData;
    } else {
        users.push(userData);
    }
    localStorage.setItem('bloodconnect-registered-users', JSON.stringify(users));
}

function findRegisteredUserLocally(email) {
    const users = getRegisteredUsersList();
    return users.find(u => u.email.toLowerCase() === email.toLowerCase()) || null;
}

/**
 * Save auth state to localStorage
 */
function saveClientAuth(email, username) {
    localStorage.setItem('bloodconnect-auth', 'true');
    localStorage.setItem('bloodconnect-user', email);
    const profile = JSON.parse(localStorage.getItem('bloodconnect-user-profile') || '{}');
    profile.name = username || profile.name || email.split('@')[0];
    profile.email = email;
    localStorage.setItem('bloodconnect-user-profile', JSON.stringify(profile));
}

/**
 * Check required auth on protected client pages
 */
function requireUserAuth() {
    const auth = localStorage.getItem('bloodconnect-auth');
    if (auth !== 'true') {
        window.location.href = 'login.html';
    }
}

/**
 * Get current logged in user email
 */
function getAuthenticatedUser() {
    return localStorage.getItem('bloodconnect-user') || '';
}

/**
 * Logout user from client storage
 */
function logoutUser() {
    localStorage.removeItem('bloodconnect-auth');
    localStorage.removeItem('bloodconnect-user');
    localStorage.removeItem('bloodconnect-user-profile');
}

/**
 * Handle login form submission
 */
async function handleLoginSubmit(e) {
    e.preventDefault();
    clearErrors();

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    const email = emailInput ? emailInput.value.trim() : '';
    const password = passwordInput ? passwordInput.value : '';

    // Validate inputs
    let isValid = true;
    if (!validateEmail(email)) isValid = false;
    if (!password) {
        showFieldError('passwordError', 'Password tidak boleh kosong.');
        isValid = false;
    }

    if (!isValid) return;

    showLoading();

    let loginSuccess = false;
    let redirectUrl = 'home.php';
    let message = 'Login berhasil!';

    try {
        const formData = new URLSearchParams();
        formData.append('email', email);
        formData.append('password', password);

        const response = await fetch('auth/process_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString()
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                loginSuccess = true;
                if (data.redirect) redirectUrl = data.redirect;
                if (data.message) message = data.message;
                const displayName = (data.user && data.user.username) ? data.user.username : email.split('@')[0];
                saveClientAuth(email, displayName);
            } else {
                hideLoading();
                if (data.errors) {
                    if (data.errors.email) showFieldError('emailError', data.errors.email);
                    if (data.errors.password) showFieldError('passwordError', data.errors.password);
                    if (data.errors.general) {
                        const generalError = document.getElementById('generalError');
                        if (generalError) {
                            generalError.textContent = data.errors.general;
                            generalError.classList.add('show');
                            generalError.style.display = 'block';
                        }
                    }
                }
                return;
            }
        } else {
            throw new Error('Backend HTTP ' + response.status);
        }
    } catch (error) {
        // Persistent client fallback if backend is unavailable
        const localUser = findRegisteredUserLocally(email);
        if (!localUser) {
            hideLoading();
            showFieldError('emailError', 'Email belum terdaftar.');
            return;
        } else if (localUser.password !== password) {
            hideLoading();
            showFieldError('passwordError', 'Password yang Anda masukkan salah.');
            return;
        } else {
            loginSuccess = true;
            redirectUrl = 'home.php';
            saveClientAuth(email, localUser.username);
        }
    }

    if (loginSuccess) {
        hideLoading();

        const successElement = document.getElementById('successMessage');
        if (successElement) {
            successElement.textContent = message;
            successElement.classList.add('show');
            successElement.style.display = 'block';
        }

        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 600);
    }
}

/**
 * Handle register form submission
 */
async function handleRegisterSubmit(e) {
    e.preventDefault();
    clearErrors();

    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirm');
    const agreeTermsInput = document.getElementById('agree_terms');

    const username = usernameInput ? usernameInput.value.trim() : '';
    const email = emailInput ? emailInput.value.trim() : '';
    const password = passwordInput ? passwordInput.value : '';
    const passwordConfirm = passwordConfirmInput ? passwordConfirmInput.value : '';
    const agreeTerms = agreeTermsInput ? agreeTermsInput.checked : false;

    // Validate all fields
    let isValid = true;
    if (!validateUsername(username)) isValid = false;
    if (!validateEmail(email)) isValid = false;
    if (!validatePassword(password)) isValid = false;
    if (!validatePasswordMatch()) isValid = false;

    if (!agreeTerms) {
        showFieldError('agreeTermsError', 'Anda harus menyetujui syarat dan ketentuan.');
        isValid = false;
    }

    if (!isValid) return;

    showLoading();

    let registerSuccess = false;
    let redirectUrl = 'login.html';
    let message = 'Registrasi berhasil! Silakan Login.';

    try {
        const formData = new FormData();
        formData.append('username', username);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('password_confirm', passwordConfirm);
        formData.append('agree_terms', agreeTerms ? '1' : '0');

        const response = await fetch('auth/process_register.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                registerSuccess = true;
                if (data.redirect) redirectUrl = data.redirect;
                if (data.message) message = data.message;
                saveRegisteredUserLocally(username, email, password);
            } else {
                hideLoading();
                if (data.errors) {
                    if (data.errors.username) showFieldError('usernameError', data.errors.username);
                    if (data.errors.email) showFieldError('emailError', data.errors.email);
                    if (data.errors.password) showFieldError('passwordError', data.errors.password);
                    if (data.errors.password_confirm) showFieldError('passwordConfirmError', data.errors.password_confirm);
                    if (data.errors.agree_terms) showFieldError('agreeTermsError', data.errors.agree_terms);
                    if (data.errors.general) {
                        const generalError = document.getElementById('generalError');
                        if (generalError) {
                            generalError.textContent = data.errors.general;
                            generalError.classList.add('show');
                            generalError.style.display = 'block';
                        }
                    }
                }
                return;
            }
        } else {
            throw new Error('Backend HTTP ' + response.status);
        }
    } catch (error) {
        // Persistent client fallback if backend is unavailable
        const existing = findRegisteredUserLocally(email);
        if (existing) {
            hideLoading();
            showFieldError('emailError', 'Email sudah terdaftar. Silakan gunakan email lain.');
            return;
        }
        saveRegisteredUserLocally(username, email, password);
        registerSuccess = true;
        redirectUrl = 'login.html';
        message = 'Registrasi berhasil! Silakan Login.';
    }

    if (registerSuccess) {
        hideLoading();

        const successElement = document.getElementById('successMessage');
        if (successElement) {
            successElement.textContent = message;
            successElement.classList.add('show');
            successElement.style.display = 'block';
        }

        const registerForm = document.getElementById('registerForm');
        if (registerForm) registerForm.reset();

        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 1000);
    }
}

// Attach utilities to window for global access
window.saveClientAuth = saveClientAuth;
window.logoutUser = logoutUser;
window.requireUserAuth = requireUserAuth;
window.getAuthenticatedUser = getAuthenticatedUser;


