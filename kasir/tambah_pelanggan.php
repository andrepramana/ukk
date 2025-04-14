<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelanggan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Tambah Pelanggan Baru</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="proses_tambah_pelanggan.php" method="post">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pelanggan:</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat:</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="telepon" class="form-label">Nomor Telepon:</label>
                        <input type="text" class="form-control" id="telepon" name="telepon" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Simpan Pelanggan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional, hanya jika butuh interaksi seperti modal, dropdown, dsb) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
