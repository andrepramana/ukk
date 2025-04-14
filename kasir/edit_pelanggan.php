<?php
// Koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pra_ukk_andre";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek jika ID dikirim via URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID pelanggan tidak ditemukan!'); window.location='tampil_pelanggan.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// Ambil data pelanggan berdasarkan ID
$sql = "SELECT * FROM pelanggan WHERE PelangganID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Pelanggan tidak ditemukan!'); window.location='tampil_pelanggan.php';</script>";
    exit;
}

$row = $result->fetch_assoc();

// Proses update ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    $sql_update = "UPDATE pelanggan SET NamaPelanggan = ?, Alamat = ?, NomorTelepon = ? WHERE PelangganID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $nama, $alamat, $telepon, $id);

    if ($stmt_update->execute()) {
        echo "<script>alert('Data pelanggan berhasil diperbarui.'); window.location='tampil_pelanggan.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!-- Tampilan Form Edit dengan Bootstrap -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 30px;
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="form-container">
    <h3 class="mb-4">✏️ Edit Data Pelanggan</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row['NamaPelanggan']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" rows="3" required><?php echo htmlspecialchars($row['Alamat']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" name="telepon" value="<?php echo htmlspecialchars($row['NomorTelepon']); ?>" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="tampil_pelanggan.php" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
