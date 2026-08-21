document.addEventListener('DOMContentLoaded', function () {
  const table = document.getElementById('participantTable');
  const detailModal = document.getElementById('detailModal');
  const detailModalContent = document.getElementById('detailModalContent');
  const detailModalClose = document.getElementById('detailModalClose');

  if (!table) return;

  function getRegistrations() {
    return JSON.parse(localStorage.getItem('bloodconnect-panitia-data') || '[]');
  }

  function saveRegistrations(registrations) {
    localStorage.setItem('bloodconnect-panitia-data', JSON.stringify(registrations));
    localStorage.setItem('bloodconnect-registrations', JSON.stringify(registrations));
  }

  function renderTable() {
    const registrations = getRegistrations();
    if (!registrations.length) {
      table.innerHTML = '<tr><td colspan="8">Belum ada data pendaftar.</td></tr>';
      return;
    }

    table.innerHTML = registrations.map((item, index) => `
      <tr>
        <td>${item.id || '-'}</td>
        <td>${item['full-name'] || '-'}</td>
        <td>${item.email || '-'}</td>
        <td>${item.phone || '-'}</td>
        <td>${item['blood-type'] || '-'}</td>
        <td>${item['donation-date'] || '-'}</td>
        <td>${item.status || 'Pending'}</td>
        <td>
          <button class="btn btn-primary" data-action="approve" data-index="${index}">Approve</button>
          <button class="btn btn-outline" data-action="reject" data-index="${index}" style="margin-left:8px;">Reject</button>
          <button class="btn btn-secondary" data-action="detail" data-index="${index}" style="margin-left:8px;">View Detail</button>
        </td>
      </tr>
    `).join('');
  }

  function showAlert(message) {
    alert(message);
  }

  function showToast(message) {
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('show'));
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
    }, 2600);
  }

  async function sendApprovalEmail(registration) {
    const payload = {
      id: registration.id,
      'full-name': registration['full-name'],
      email: registration.email,
      phone: registration.phone,
      'blood-type': registration['blood-type'],
      'donation-date': registration['donation-date'],
      location: registration.location,
    };

    try {
      const response = await fetch('../mail/send-approve.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      const data = await response.json();
      if (!response.ok || !data.success) {
        throw new Error(data.message || 'Failed to send approval email.');
      }
      return true;
    } catch (error) {
      console.error(error);
      showAlert('Failed to send approval email.');
      return false;
    }
  }

  async function handleAction(action, index) {
    const registrations = getRegistrations();
    const registration = registrations[index];
    if (!registration) return;

    if (action === 'detail') {
      detailModalContent.innerHTML = `
        <h3>Detail Pendaftaran</h3>
        <table style="width:100%;border-collapse:collapse;">
          <tbody>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">ID</td><td style="padding:8px 0;">${registration.id || '-'}</td></tr>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">Nama</td><td style="padding:8px 0;">${registration['full-name'] || '-'}</td></tr>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">Email</td><td style="padding:8px 0;">${registration.email || '-'}</td></tr>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">Telepon</td><td style="padding:8px 0;">${registration.phone || '-'}</td></tr>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">Gol Darah</td><td style="padding:8px 0;">${registration['blood-type'] || '-'}</td></tr>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">Tanggal Donor</td><td style="padding:8px 0;">${registration['donation-date'] || '-'}</td></tr>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">Lokasi</td><td style="padding:8px 0;">${registration.location || '-'}</td></tr>
            <tr><td style="padding:8px 0;font-weight:700;color:#b71c1c;">Status</td><td style="padding:8px 0;">${registration.status || 'Pending'}</td></tr>
          </tbody>
        </table>
      `;
      detailModal.classList.add('show');
      return;
    }

    const confirmed = confirm(`Are you sure you want to ${action} this registration?`);
    if (!confirmed) return;

    if (action === 'approve') {
      const emailSent = await sendApprovalEmail(registration);
      if (!emailSent) return;
      registrations[index].status = 'Approved';
      saveRegistrations(registrations);
      renderTable();
      showToast('Approval email has been sent successfully.');
      return;
    }

    if (action === 'reject') {
      registrations.splice(index, 1);
      saveRegistrations(registrations);
      renderTable();
      showToast('Registration has been rejected and removed.');
      return;
    }
  }

  table.addEventListener('click', function (event) {
    const button = event.target.closest('button');
    if (!button) return;

    const action = button.dataset.action;
    const index = Number(button.dataset.index);
    if (!action || Number.isNaN(index)) return;

    handleAction(action, index);
  });

  if (detailModalClose) {
    detailModalClose.addEventListener('click', () => detailModal.classList.remove('show'));
  }

  detailModal.addEventListener('click', (event) => {
    if (event.target === detailModal) {
      detailModal.classList.remove('show');
    }
  });

  renderTable();
});
