<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 250px;
            font-size: 16px;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
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
        .btn-edit, .btn-delete {
            color: white;
            padding: 8px 12px;
            margin-right: 5px;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-edit {
            background-color: orange;
        }
        .btn-delete {
            background-color: red;
        }
        .btn-edit:hover {
            background-color: darkorange;
        }
        .btn-delete:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h1>Daftar Produk</h1>

    <!-- Form Search -->
    <form method="GET">
        <input type="text" name="search" placeholder="Cari nama produk..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="btn-custom">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Koneksi ke database
            $conn = new mysqli("localhost", "root", "", "pra_ukk_andre");

            // Cek koneksi
            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }

            // Tangkap keyword search
            $search = isset($_GET['search']) ? '%' . $conn->real_escape_string($_GET['search']) . '%' : '%';

            // Query produk dengan filter nama
            $sql = "SELECT ProdukID, NamaProduk, Harga, Stok FROM produk 
                    WHERE Status = 1 AND deleted_at IS NULL AND NamaProduk LIKE ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $search);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . htmlspecialchars($row["NamaProduk"]) . "</td>
                            <td>Rp " . number_format($row["Harga"], 2, ',', '.') . "</td>
                            <td>" . $row["Stok"] . "</td>
                            <td>
                                <a href='edit_produk.php?id=" . $row["ProdukID"] . "' class='btn-edit'>
                                    <i class='fas fa-edit'></i> Edit
                                </a>
                                <a href='delete_produk.php?id=" . $row["ProdukID"] . "' 
                                   class='btn-delete' 
                                   onclick='return confirm(\"Apakah Anda yakin ingin menghapus produk ini?\")'>
                                    <i class='fas fa-trash-alt'></i> Hapus
                                </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada produk ditemukan.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
    <br><br>
    <div style="text-align: center;">
        <a href='index.html' class='btn-custom'>Kembali ke halaman utama</a>
    </div>
</body>
</html>
