<style>
    .btn-custom {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: bold;
        color: white;
        background-color: blue;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-custom:hover {
        background-color: darkblue;
    }
</style>

<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "pra_ukk_andre";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data dari form
$namaProduk = $_POST['namaProduk'];
$harga = $_POST['harga'];
$stok = $_POST['stok'];

// Query untuk mengambil stok saat ini
$sql = "SELECT Stok FROM produk WHERE NamaProduk = '$namaProduk'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stokBaru = $row["Stok"] + $stok;

    // Query untuk update stok dan harga
    $sql = "UPDATE produk SET Stok = $stokBaru, Harga = $harga WHERE NamaProduk = '$namaProduk'";
    if ($conn->query($sql) === TRUE) {
        echo "Produk berhasil diperbarui. <br> <a href='update_produk.php' class='btn-custom'>Kembali</a> ";
        echo "<a href='index.html' class='btn-custom'>Kembali ke halaman utama</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Produk tidak ditemukan. <a href='update_produk.php'>Kembali</a>";
}

// Menutup koneksi
$conn->close();
?>