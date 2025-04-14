<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn-back {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .btn-delete {
            color: white;
            background-color: red;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin-left: 10px; /* Menambahkan jarak kiri */
        }
        .btn-delete:hover {
            background-color: darkred;
        }
        .btn-edit {
            color: white;
            background-color: orange;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-edit:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <h1>Daftar Pelanggan</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
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

            // Query untuk mengambil data pelanggan
            $sql = "SELECT PelangganID, NamaPelanggan, Alamat, NomorTelepon FROM pelanggan";
            $result = $conn->query($sql);

            // Cek apakah ada data
            if ($result->num_rows > 0) {
                $no = 1;
                // Output data setiap baris
                while($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>" . $no++ . "</td>
                        <td>" . $row["NamaPelanggan"] . "</td>
                        <td>" . $row["Alamat"] . "</td>
                        <td>" . $row["NomorTelepon"] . "</td> 
                        <td>
                        <a href='edit_pelanggan.php?id=" . $row["PelangganID"] . "' 
                        class='btn-edit'>
                        <i class='fas fa-edit'></i> Edit
                        </a>
                        </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align: center;'>Tidak ada data pelanggan.</td></tr>";
            }

            // Menutup koneksi
            $conn->close();
            ?>
        </tbody>
    </table>

    <!-- Tombol Kembali ke Halaman Utama -->
    <a href="index.html" class="btn-back">Kembali ke Halaman Utama</a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
