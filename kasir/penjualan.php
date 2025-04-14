<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penjualan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tambahan agar dropdown produk bisa di-scroll jika banyak */
        #produk {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Form Penjualan</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="proses_penjualan.php" method="post">
                    <div class="mb-3">
                        <label for="pelanggan" class="form-label">Pilih Pelanggan:</label>
                        <select id="pelanggan" name="pelanggan" class="form-select" required>
                            <?php
                            $conn = new mysqli("localhost", "root", "", "pra_ukk_andre");
                            if ($conn->connect_error) {
                                die("Koneksi gagal: " . $conn->connect_error);
                            }
                            $sql = "SELECT PelangganID, NamaPelanggan FROM pelanggan";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["PelangganID"] . "'>" . $row["NamaPelanggan"] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Tidak ada pelanggan</option>";
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="produk" class="form-label">Pilih Produk:</label>
                        <select id="produk" name="produk" class="form-select" size="5" required>
                            <?php
                            $conn = new mysqli("localhost", "root", "", "pra_ukk_andre");
                            if ($conn->connect_error) {
                                die("Koneksi gagal: " . $conn->connect_error);
                            }
                            $sql = "SELECT ProdukID, NamaProduk, Harga FROM produk";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["ProdukID"] . "' data-harga='" . $row["Harga"] . "'>" . $row["NamaProduk"] . " (Rp " . number_format($row["Harga"], 2, ',', '.') . ")</option>";
                                }
                            } else {
                                echo "<option value=''>Tidak ada produk</option>";
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah:</label>
                        <input type="number" id="jumlah" name="jumlah" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="uang" class="form-label">Uang yang Diberikan:</label>
                        <input type="number" id="uang" name="uang" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Penjualan</button>
                </form>

                <div id="kembalian" class="alert alert-info mt-4 text-center d-none fw-bold">
                    <!-- Kembalian akan muncul di sini -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Hitung kembalian ketika input uang atau jumlah diubah
        const uangInput = document.getElementById('uang');
        const jumlahInput = document.getElementById('jumlah');
        const produkSelect = document.getElementById('produk');
        const kembalianDiv = document.getElementById('kembalian');

        function hitungKembalian() {
            const uang = parseFloat(uangInput.value);
            const harga = parseFloat(produkSelect.options[produkSelect.selectedIndex]?.getAttribute('data-harga') || 0);
            const jumlah = parseFloat(jumlahInput.value);

            if (!isNaN(uang) && !isNaN(harga) && !isNaN(jumlah)) {
                const total = harga * jumlah;
                const kembalian = uang - total;

                if (kembalian >= 0) {
                    kembalianDiv.classList.remove('d-none', 'alert-danger');
                    kembalianDiv.classList.add('alert-info');
                    kembalianDiv.textContent = "Kembalian: Rp " + kembalian.toLocaleString('id-ID', {minimumFractionDigits: 2});
                } else {
                    kembalianDiv.classList.remove('d-none', 'alert-info');
                    kembalianDiv.classList.add('alert-danger');
                    kembalianDiv.textContent = "Uang tidak cukup!";
                }
            } else {
                kembalianDiv.classList.add('d-none');
            }
        }

        uangInput.addEventListener('input', hitungKembalian);
        jumlahInput.addEventListener('input', hitungKembalian);
        produkSelect.addEventListener('change', hitungKembalian);
    </script>
</body>
</html>
