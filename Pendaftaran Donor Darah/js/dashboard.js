document.addEventListener('DOMContentLoaded', () => {
  requireUserAuth();

  const regList = document.getElementById('registrationList');
  const historyList = document.getElementById('historyList');
  const profileName = document.getElementById('profileName');
  const profileEmail = document.getElementById('profileEmail');
  const profileStatus = document.getElementById('profileStatus');
  const profileNameDetail = document.getElementById('profileNameDetail');
  const selectedSchedule = document.getElementById('selectedSchedule');
  const countdown = document.getElementById('countdown');
  const logoutButton = document.getElementById('logoutButton');

  const currentUser = getAuthenticatedUser();
  const registrations = JSON.parse(localStorage.getItem('bloodconnect-registrations') || '[]');
  const userRegistrations = registrations.filter((item) => item.userEmail === currentUser);

  if (profileName) profileName.textContent = 'Selamat datang, ' + (currentUser || 'Pendonor');
  if (profileEmail) profileEmail.textContent = 'Email: ' + (currentUser || 'Tidak tersedia');
  if (profileNameDetail) profileNameDetail.textContent = currentUser || 'Pendonor';
  if (profileStatus) profileStatus.textContent = userRegistrations.length ? 'Terdaftar aktif' : 'Belum melakukan pendaftaran';

  if (regList) {
    regList.innerHTML = userRegistrations.length ? userRegistrations.map((item) => `
      <li><strong>${item['full-name']}</strong> — ${item.location} • ${item['donation-date']} • <span class="chip">${item.status}</span></li>
    `).join('') : '<li>Belum ada pendaftaran.</li>';
  }

  if (historyList) {
    historyList.innerHTML = userRegistrations.length ? userRegistrations.slice(0, 3).map((item) => `
      <tr><td>${item['full-name']}</td><td>${item['blood-type']}</td><td>${item['donation-date']}</td><td>${item.status}</td></tr>
    `).join('') : '<tr><td colspan="4">Belum ada riwayat.</td></tr>';
  }

  if (selectedSchedule) {
    selectedSchedule.textContent = userRegistrations[0] ? userRegistrations[0].location : 'Jadwal belum dipilih';
  }

  if (countdown) {
    const target = new Date();
    target.setDate(target.getDate() + 6);
    const update = () => {
      const diff = target - new Date();
      if (diff <= 0) {
        countdown.textContent = 'Siap donor';
        return;
      }
      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
      const minutes = Math.floor((diff / (1000 * 60)) % 60);
      countdown.textContent = `${days}h ${hours}j ${minutes}m`;
    };
    update();
    setInterval(update, 60000);
  }

  if (logoutButton) {
    logoutButton.addEventListener('click', () => {
      logoutUser();
      window.location.href = 'login.html';
    });
  }
});
