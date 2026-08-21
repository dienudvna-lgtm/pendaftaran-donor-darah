document.addEventListener('DOMContentLoaded', function () {
  const logoutButtons = document.querySelectorAll('.logout-link');
  const editProfileButton = document.getElementById('editProfileButton');
  const saveProfileButton = document.getElementById('saveProfileButton');
  const profileForm = document.getElementById('profileForm');
  const suggestionForm = document.getElementById('suggestionForm');
  const typingText = document.querySelector('[data-type-text]');

  if (logoutButtons.length) {
    logoutButtons.forEach((button) => {
      button.addEventListener('click', function (event) {
        event.preventDefault();
        const confirmed = window.confirm('Apakah Anda yakin ingin keluar?');
        if (confirmed) {
          if (typeof logoutUser === 'function') {
            logoutUser();
          } else {
            localStorage.removeItem('bloodconnect-auth');
            localStorage.removeItem('bloodconnect-user');
            localStorage.removeItem('bloodconnect-user-profile');
          }
          window.location.href = 'auth/logout.php';
        }
      });
    });
  }

  if (typingText) {
    const text = typingText.dataset.typeText;
    const speed = Number(typingText.dataset.typeSpeed) || 80;
    let index = 0;
    function type() {
      typingText.textContent = text.slice(0, index++);
      if (index <= text.length) {
        setTimeout(type, speed);
      }
    }
    type();
  }

  if (profileForm) {
    const currentProfile = getAuthenticatedUserProfile();
    const currentUserEmail = typeof getAuthenticatedUser === 'function' ? getAuthenticatedUser() : (localStorage.getItem('bloodconnect-user') || '');
    const defaultName = currentProfile.name || (currentUserEmail ? currentUserEmail.split('@')[0] : 'Relawan');

    const profileNameEl = document.getElementById('profileName');
    const profileEmailEl = document.getElementById('profileEmail');
    const profilePhoneEl = document.getElementById('profilePhone');
    const inputNameEl = document.getElementById('inputName');
    const inputEmailEl = document.getElementById('inputEmail');
    const inputPhoneEl = document.getElementById('inputPhone');

    if (profileNameEl) profileNameEl.textContent = currentProfile.name || defaultName;
    if (profileEmailEl) profileEmailEl.textContent = currentProfile.email || currentUserEmail;
    if (profilePhoneEl) profilePhoneEl.textContent = currentProfile.phone || 'Belum ditambahkan';
    if (inputNameEl) inputNameEl.value = currentProfile.name || defaultName;
    if (inputEmailEl) inputEmailEl.value = currentProfile.email || currentUserEmail;
    if (inputPhoneEl) inputPhoneEl.value = currentProfile.phone || '';

    profileForm.addEventListener('submit', function (event) {
      event.preventDefault();
      const profile = {
        name: inputNameEl ? inputNameEl.value.trim() : defaultName,
        email: inputEmailEl ? inputEmailEl.value.trim() : currentUserEmail,
        phone: inputPhoneEl ? inputPhoneEl.value.trim() : '',
      };
      saveAuthenticatedUserProfile(profile);
      if (profileNameEl) profileNameEl.textContent = profile.name;
      if (profileEmailEl) profileEmailEl.textContent = profile.email;
      if (profilePhoneEl) profilePhoneEl.textContent = profile.phone || 'Belum ditambahkan';
      if (typeof showToast === 'function') {
        showToast('Profil berhasil diperbarui');
      } else {
        alert('Profil berhasil diperbarui');
      }
    });
  }

  if (suggestionForm) {
    suggestionForm.addEventListener('submit', function (event) {
      event.preventDefault();
      if (typeof showToast === 'function') {
        showToast('Terima kasih, saran Anda berhasil dikirim.');
      } else {
        alert('Terima kasih, saran Anda berhasil dikirim.');
      }
      suggestionForm.reset();
    });
  }
});

function getAuthenticatedUserProfile() {
  const profile = localStorage.getItem('bloodconnect-user-profile');
  return profile ? JSON.parse(profile) : {};
}

function saveAuthenticatedUserProfile(profile) {
  localStorage.setItem('bloodconnect-user-profile', JSON.stringify(profile));
}

