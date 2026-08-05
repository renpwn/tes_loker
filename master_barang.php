<?php
include 'koneksi.php';

// Proses Tambah Barang
if (isset($_POST['tambah'])) {
    $id_barang   = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah      = $_POST['jumlah'];
    $satuan      = $_POST['satuan']; // Mengambil dari pilihan dropdown
    $harga       = $_POST['harga_satuan'];
    $status      = '1';

    $query = "INSERT INTO items (id_barang, nama_barang, jumlah, satuan, harga_satuan, status) 
              VALUES ('$id_barang', '$nama_barang', '$jumlah', '$satuan', '$harga', '$status')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: master_barang.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Master Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Master Barang</h2>
    <a href="index.php">Kembali ke Report</a> | <a href="transaksi.php">Form Transaksi</a>
    <br><br>

    <form method="POST" action="">
        <h3>Tambah Barang Baru</h3>
        <label>ID Barang (Angka):</label>
        <input type="number" name="id_barang" required>

        <label>Nama Barang:</label>
        <input type="text" name="nama_barang" required>

        <label>Jumlah Stok:</label>
        <input type="number" step="any" name="jumlah" required>

        <!-- Pembaruan: Pilihan Satuan Menggunakan Dropdown -->
        <label>Satuan Barang:</label>
        <select name="satuan" required>
            <option value="">-- Pilih Satuan --</option>
            <option value="EA">EA (Each / Buah)</option>
            <option value="PCS">PCS (Pieces)</option>
            <option value="BOX">BOX (Kardus)</option>
            <option value="PACK">PACK (Bungkusan)</option>
            <option value="SET">SET (Paket)</option>
            <option value="LSN">LSN (Lusin / 12 pcs)</option>
            <option value="KG">KG (Kilogram)</option>
            <option value="GR">GR (Gram)</option>
            <option value="MTR">MTR (Meter)</option>
            <option value="LTR">LTR (Liter)</option>
        </select>

        <label>Harga Satuan:</label>
        <input type="number" step="any" name="harga_satuan" required>

        <button type="submit" name="tambah">Simpan Barang</button>
    </form>

    <h3>Daftar Stok Barang</h3>
    <table>
        <tr>
            <th>ID Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga Satuan</th>
        </tr>
        <?php
        $data = mysqli_query($conn, "SELECT * FROM items");
        while ($row = mysqli_fetch_assoc($data)) {
            echo "<tr>
                <td>{$row['id_barang']}</td>
                <td>{$row['nama_barang']}</td>
                <td>{$row['jumlah']}</td>
                <td>{$row['satuan']}</td>
                <td>" . number_format($row['harga_satuan']) . "</td>
            </tr>";
        }
        ?>
    </table>

</body>
</html>