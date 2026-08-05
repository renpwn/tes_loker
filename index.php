<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Pengeluaran Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Report Transaksi Pengeluaran Barang</h2>
    <a href="master_barang.php">Kelola Master Barang</a> | <a href="transaksi.php">Buat Transaksi Baru</a>
    <hr>

    <h3>Data Laporan (Berdasarkan View `dt_trans`)</h3>
    <table>
        <tr>
            <th>No. Transaksi</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga Satuan</th>
            <th>Total</th>
        </tr>
        <?php
        // Menggunakan view dt_trans yang sudah didefinisikan pada struktur database
        $sql = "SELECT * FROM dt_trans";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['No. Transaksi']}</td>
                    <td>{$row['Tanggal']}</td>
                    <td>{$row['Nama Barang']}</td>
                    <td>{$row['Jumlah']}</td>
                    <td>{$row['Satuan']}</td>
                    <td>" . number_format($row['Harga Satuan']) . "</td>
                    <td>" . number_format($row['Total']) . "</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data transaksi.</td></tr>";
        }
        ?>
    </table>

</body>
</html>