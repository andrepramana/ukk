<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti jika pakai username lain
$password = ""; // Ganti jika pakai password
$dbname = "pra_ukk_andre";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah parameter id dikirimkan
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    // Jika id tidak ada, redirect ke halaman tampil_produk.php dengan pesan error
    header("Location: tampil_produk.php?error=ID produk tidak ditemukan!");
    exit;
}

// Ambil dan filter ID produk
$id = intval($_GET["id"]);

// Pastikan ID produk valid
if ($id <= 0) {
    header("Location: tampil_produk.php?error=ID produk tidak valid!");
    exit;
}

// Hapus data terkait di tabel detailpenjualan terlebih dahulu
$sql_delete_detail = "DELETE FROM detailpenjualan WHERE ProdukID = ?";
$stmt_detail = $conn->prepare($sql_delete_detail);
$stmt_detail->bind_param("i", $id);
$stmt_detail->execute();
$stmt_detail->close();

// Hapus produk dari tabel produk
$sql_delete_product = "DELETE FROM produk WHERE ProdukID = ?";
$stmt_product = $conn->prepare($sql_delete_product);
$stmt_product->bind_param("i", $id);

// Eksekusi perintah
if ($stmt_product->execute()) {
    // Redirect ke tampil_produk.php dengan pesan sukses
    header("Location: tampil_produk.php?success=Produk berhasil dihapus!");
} else {
    // Redirect ke tampil_produk.php dengan pesan error
    header("Location: tampil_produk.php?error=Gagal menghapus produk!");
}

// Tutup koneksi
$stmt_product->close();
$conn->close();
?>
