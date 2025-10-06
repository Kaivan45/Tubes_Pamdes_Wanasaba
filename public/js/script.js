function toggleMenu() {
  document.getElementById("menu").classList.toggle("show");
}


const hargaPerMeter = 1000;
const meteranInput = document.getElementById('meteran');
const hargaDisplay = document.getElementById('hargaDisplay');
const hargaHidden = document.getElementById('harga');

meteranInput.addEventListener('input', function() {
  const meter = parseFloat(meteranInput.value) || 0;
  const totalHarga = meter * hargaPerMeter;

  // Tampilkan di format Rupiah
  hargaDisplay.value = "Rp " + totalHarga.toLocaleString('id-ID');

  // Simpan nilai asli untuk form submit
  hargaHidden.value = totalHarga;
});