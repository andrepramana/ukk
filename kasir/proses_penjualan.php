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
$username = "root";
$password = "";
$dbname = "pra_ukk_andre";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form POST
$PelangganID = isset($_POST['pelanggan']) ? intval($_POST['pelanggan']) : 0;
$ProdukID = isset($_POST['produk']) ? intval($_POST['produk']) : 0;
$jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;
$uang = isset($_POST['uang']) ? floatval($_POST['uang']) : 0;

// Validasi input
if ($PelangganID <= 0 || $ProdukID <= 0 || $jumlah <= 0 || $uang <= 0) {
    echo "Input tidak valid. <br><a href='penjualan.php' class='btn-custom'>Kembali</a>";
    exit;
}

// Ambil harga dan stok produk
$sql = "SELECT Harga, Stok FROM produk WHERE ProdukID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ProdukID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $harga = $row["Harga"];
    $stok = $row["Stok"];

    $subtotal = $harga * $jumlah;

   
    if ($stok < $jumlah && $uang < ($harga * $jumlah)) {
        echo "Stok tidak mencukupi. Stok tersedia: $stok<br>";
        echo "Uang tidak cukup. Total harga: Rp " . number_format($harga * $jumlah, 2, ',', '.') . ", Uang diberikan: Rp " . number_format($uang, 2, ',', '.');
        echo "<br><a href='index.html' class='btn-custom'>Kembali</a>";
        exit;
    }

    if ($uang < $subtotal) {
        echo "Uang tidak cukup. Total: Rp " . number_format($subtotal, 2, ',', '.') . ", Uang diberikan: Rp " . number_format($uang, 2, ',', '.');
        echo "<br><a href='index.html' class='btn-custom'>Kembali</a>";
        exit;
    }

    
    if ($stok < $jumlah) {
        echo "Stok tidak mencukupi. Stok tersedia: $stok";
        echo "<br><a href='index.html' class='btn-custom'>Kembali</a>";
        exit;
    }


    $kembalian = $uang - $subtotal;

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Simpan ke tabel penjualan
        $sql = "INSERT INTO penjualan (TanggalPenjualan, TotalHarga, PelangganID) VALUES (NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $subtotal, $PelangganID);
        $stmt->execute();
        $PenjualanID = $conn->insert_id;

        // Simpan detail penjualan
        $sql = "INSERT INTO detailpenjualan (PenjualanID, ProdukID, JumlahProduk, Subtotal) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $PenjualanID, $ProdukID, $jumlah, $subtotal);
        $stmt->execute();

        // Update stok
        $sql = "UPDATE produk SET Stok = Stok - ? WHERE ProdukID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $jumlah, $ProdukID);
        $stmt->execute();

        // Commit transaksi
        $conn->commit();

        echo "<div style='font-family: sans-serif; font-size: 18px;'>";
        echo "<strong>Penjualan berhasil disimpan!</strong><br>";
        echo "Total: Rp " . number_format($subtotal, 2, ',', '.') . "<br>";
        echo "Uang Diberikan: Rp " . number_format($uang, 2, ',', '.') . "<br>";
        echo "Kembalian: Rp " . number_format($kembalian, 2, ',', '.') . "<br><br>";
        echo "<a href='penjualan.php' class='btn-custom'>Kembali</a> ";
        echo "<a href='index.html' class='btn-custom'>Halaman Utama</a>";
        echo "</div>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Gagal menyimpan penjualan: " . $e->getMessage();
        echo "<br><a href='index.html' class='btn-custom'>Kembali</a>";
    }

} else {
    echo "Produk tidak ditemukan.";
    echo "<br><a href='index.html' class='btn-custom'>Kembali</a>";
}

$conn->close();
?>
