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

// Query untuk menyimpan data ke tabel Produk
$sql = "INSERT INTO produk (NamaProduk, Harga, Stok) VALUES ('$namaProduk', $harga, $stok)";

if ($conn->query($sql) === TRUE) {
    echo "Produk berhasil ditambahkan. <br> <a href='tambah_produk.php' class='btn-custom'>Kembali</a> " ;
    echo "<a href='index.html' class='btn-custom'>Kembali ke halaman utama</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Menutup koneksi
$conn->close();
?>