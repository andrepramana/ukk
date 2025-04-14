<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Produk</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Update Produk</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="proses_update_produk.php" method="post">
                    <div class="mb-3">
                        <label for="namaProduk" class="form-label">Nama Produk:</label>
                        <select id="namaProduk" name="namaProduk" class="form-select" required>
                            <?php
                            // Koneksi ke database
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "pra_ukk_andre";

                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("Koneksi gagal: " . $conn->connect_error);
                            }

                            $sql = "SELECT NamaProduk, Harga FROM produk";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["NamaProduk"] . "' data-harga='" . $row["Harga"] . "'>" . $row["NamaProduk"] . " (Rp " . number_format($row["Harga"], 2, ',', '.') . ")</option>";
                                }
                            } else {
                                echo "<option value=''>Tidak ada produk</option>";
                            }

                            $conn->close();
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga Baru:</label>
                        <input type="number" class="form-control" id="harga" name="harga" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="stok" class="form-label">Jumlah Stok yang Ditambahkan:</label>
                        <input type="number" class="form-control" id="stok" name="stok" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Update Produk</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (jika ingin gunakan komponen interaktif Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script untuk autofill harga dari produk terpilih -->
    <script>
        document.getElementById('namaProduk').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var harga = selectedOption.getAttribute('data-harga');
            document.getElementById('harga').value = harga;
        });
    </script>
</body>
</html>
