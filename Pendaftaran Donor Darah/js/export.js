function exportToExcel() {
  const registrations = JSON.parse(localStorage.getItem('bloodconnect-panitia-data') || '[]');
  if (!registrations.length) {
    alert('Belum ada data pendaftar untuk diekspor.');
    return;
  }

  const rows = registrations.map((item) => ({
    Nama: item['full-name'] || '-',
    Usia: item.age || '-',
    JenisKelamin: item.gender || '-',
    GolonganDarah: item['blood-type'] || '-',
    BeratBadan: item.weight || '-',
    Telepon: item.phone || '-',
    Email: item.email || '-',
    Alamat: item.address || '-',
    RiwayatPenyakit: item.history || '-',
    TanggalDonor: item['donation-date'] || '-',
    Lokasi: item.location || '-',
    Status: item.status || 'Pending'
  }));

  const headers = Object.keys(rows[0]);
  const csvContent = [headers.join(','), ...rows.map((row) => headers.map((header) => `"${String(row[header]).replace(/"/g, '""')}"`).join(','))].join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const link = document.createElement('a');
  link.href = url;
  link.download = 'data-pendaftar-bloodconnect.csv';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(url);
}
