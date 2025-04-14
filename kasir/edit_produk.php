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

// Cek apakah ID produk ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID produk tidak ditemukan!'); window.location='tampil_produk.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// Ambil data produk berdasarkan ID
$sql = "SELECT * FROM produk WHERE ProdukID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='tampil_produk.php';</script>";
    exit;
}

$row = $result->fetch_assoc();

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];  // Nama produk yang baru

    $sql_update = "UPDATE produk SET NamaProduk = ? WHERE ProdukID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("si", $nama, $id);

    if ($stmt_update->execute()) {
        echo "<script>alert('Produk berhasil diperbarui.'); window.location='tampil_produk.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui produk.');</script>";
    }
}
?>

<!-- Tampilan Form Edit Produk -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
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
    <h3 class="mb-4">✏️ Edit Nama Produk</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row['NamaProduk']); ?>" required>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="tampil_produk.php" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
