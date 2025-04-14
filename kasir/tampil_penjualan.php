<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penjualan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jsPDF dan AutoTable CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
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
        .total {
            font-weight: bold;
            background-color: #e9ecef;
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
    </style>
</head>
<body>
    <h1>Daftar Penjualan</h1>
    <table id="tabelPenjualan">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Penjualan</th>
                <th>Nama Pelanggan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
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

            // Query untuk mengambil data penjualan beserta detailnya
            $sql = "
    SELECT 
        p.PenjualanID,
        p.TanggalPenjualan,
        pl.NamaPelanggan,
        pr.NamaProduk,
        dp.JumlahProduk,
        dp.SubTotal
    FROM 
        penjualan p
    JOIN 
        pelanggan pl ON p.PelangganID = pl.PelangganID
    JOIN 
        detailpenjualan dp ON p.PenjualanID = dp.PenjualanID
    JOIN 
        produk pr ON dp.ProdukID = pr.ProdukID
    ORDER BY 
        p.TanggalPenjualan DESC
";
            $result = $conn->query($sql);

            // Variabel untuk menyimpan total subtotal
            $totalSubtotal = 0;

            // Cek apakah ada data
            if ($result->num_rows > 0) {
                $no = 1;
                // Output data setiap baris
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . $row["TanggalPenjualan"] . "</td>
                            <td>" . $row["NamaPelanggan"] . "</td>
                            <td>" . $row["NamaProduk"] . "</td>
                            <td>" . $row["JumlahProduk"] . "</td>
                            <td>Rp " . number_format($row["SubTotal"], 2, ',', '.') . "</td>
                          </tr>";

                    // Menambahkan subtotal ke totalSubtotal
                    $totalSubtotal += $row["SubTotal"];
                }
            } else {
                echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data penjualan.</td></tr>";
            }

            // Menutup koneksi
            $conn->close();
            ?>
        </tbody>
        <!-- Baris untuk menampilkan total subtotal -->
        <tfoot>
            <tr class="total">
                <td colspan="5" style="text-align: right; font-weight: bold;">Total Subtotal:</td>
                <td>Rp <?php echo number_format($totalSubtotal, 2, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>

    <!-- Tombol Cetak PDF -->
    <button onclick="cetakPDF()" class="btn-back">Cetak PDF</button>

    <!-- Tombol Kembali ke Halaman Utama -->
    <a href="index.html" class="btn-back">Kembali ke Halaman Utama</a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi untuk mencetak PDF
        function cetakPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');

            // Judul laporan
            doc.setFontSize(18);
            doc.text("Laporan Penjualan", 14, 20);

            // Data tabel
            const table = document.getElementById("tabelPenjualan");
            const rows = table.querySelectorAll("tr");

            // Data untuk AutoTable
            const data = [];
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.querySelectorAll("td");
                const rowData = [];
                cells.forEach(cell => {
                    rowData.push(cell.innerText);
                });
                data.push(rowData);
            }

            // Header tabel
            const headers = [];
            rows[0].querySelectorAll("th").forEach(header => {
                headers.push(header.innerText);
            });

            // Buat tabel menggunakan AutoTable
            doc.autoTable({
                head: [headers],
                body: data,
                startY: 30, // Posisi awal tabel
                theme: 'grid', // Tema tabel
                styles: {
                    fontSize: 10,
                    cellPadding: 2,
                    halign: 'left'
                },
                headStyles: {
                    fillColor: [41, 128, 185], // Warna header
                    textColor: [255, 255, 255] // Warna teks header
                },
                footStyles: {
                    fillColor: [41, 128, 185], // Warna footer
                    textColor: [255, 255, 255] // Warna teks footer
                }
            });

            // Simpan PDF
            doc.save("laporan_penjualan.pdf");
        }
    </script>
</body>
</html>
