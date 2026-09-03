const form = document.getElementById('registrationForm');
const modal = document.getElementById('successModal');
const summary = document.getElementById('registrationSummary');

if (typeof requireUserAuth === 'function') {
  requireUserAuth();
}

const currentUser = typeof getAuthenticatedUser === 'function' ? getAuthenticatedUser() : '';

if (form) {
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const data = new FormData(form);
    const payload = Object.fromEntries(data.entries());
    payload.id = Date.now();
    payload.status = 'Pending';
    payload.userEmail = currentUser;

    const oldRegistrations = JSON.parse(localStorage.getItem('bloodconnect-registrations') || '[]');
    oldRegistrations.push(payload);
    localStorage.setItem('bloodconnect-registrations', JSON.stringify(oldRegistrations));
    localStorage.setItem('bloodconnect-panitia-data', JSON.stringify(oldRegistrations));

    const summaryHtml = `
      <div class="summary-box">
        <h3>Registrasi Berhasil</h3>
        <p><strong>${payload['full-name'] || 'Pendonor'}</strong>, pendaftaran Anda telah diterima dan dikirim ke panitia.</p>
        <p>Silakan tunggu konfirmasi dari panitia melalui email atau telepon.</p>
        <p>Golongan darah: <strong>${payload['blood-type'] || '-'}</strong></p>
        <p>Lokasi: <strong>${payload['location'] || '-'}</strong></p>
        <p>Tanggal: <strong>${payload['donation-date'] || '-'}</strong></p>
      </div>
    `;
    summary.innerHTML = summaryHtml;
    form.reset();
    modal.classList.add('show');
    showToast('Pendaftaran berhasil dikirim ke panitia');
  });
}

if (modal) {
  modal.addEventListener('click', (event) => {
    if (event.target === modal) modal.classList.remove('show');
  });
  document.getElementById('closeModal').addEventListener('click', () => modal.classList.remove('show'));
}
