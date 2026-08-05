<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$query_trx = mysqli_query($conn, "SELECT MAX(id_transaksi) as max_id FROM transaction");
$data_trx = mysqli_fetch_assoc($query_trx);
$next_id_trx = ($data_trx['max_id'] ? $data_trx['max_id'] + 1 : 1);

$query_no = mysqli_query($conn, "SELECT MAX(id_transaksi) as max_no FROM transaction");
$data_no = mysqli_fetch_assoc($query_no);
$urutan = $data_no['max_no'] ? $data_no['max_no'] + 1 : 1;
$nomor_transaksi = "OUT/" . str_pad($urutan, 5, "0", STR_PAD_LEFT);

if (isset($_POST['tambah_keranjang'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah_beli = $_POST['jumlah'];

    $cek_stok = mysqli_query($conn, "SELECT * FROM items WHERE id_barang = '$id_barang'");
    $barang = mysqli_fetch_assoc($cek_stok);

    // Hitung total jumlah yang sudah ada di keranjang untuk barang ini (jika ada)
    $existing_qty_in_cart = 0;
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id_barang'] == $id_barang) {
            $existing_qty_in_cart += $item['jumlah'];
        }
    }

    $total_permintaan = $jumlah_beli + $existing_qty_in_cart;

    if ($barang['jumlah'] <= 0) {
        echo "<script>alert('Transaksi gagal! Stok barang sudah habis (<= 0).');</script>";
    } elseif ($total_permintaan > $barang['jumlah']) {
        echo "<script>alert('Jumlah total di keranjang melebihi stok yang tersedia (" . $barang['jumlah'] . " " . $barang['satuan'] . "). Stok di keranjang saat ini: " . $existing_qty_in_cart . "');</script>";
    } else {
        // Cek apakah item sudah ada di keranjang
        $found_index = -1;
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item['id_barang'] == $id_barang) {
                $found_index = $index;
                break;
            }
        }

        if ($found_index !== -1) {
            // Jika sudah ada, update jumlah dan totalnya
            $_SESSION['cart'][$found_index]['jumlah'] += $jumlah_beli;
            $_SESSION['cart'][$found_index]['total'] = $_SESSION['cart'][$found_index]['jumlah'] * $_SESSION['cart'][$found_index]['harga_satuan'];
        } else {
            // Jika belum ada, masukkan sebagai item baru
            $item_array = [
                'id_barang' => $barang['id_barang'],
                'nama_barang' => $barang['nama_barang'],
                'jumlah' => $jumlah_beli,
                'satuan' => $barang['satuan'],
                'harga_satuan' => $barang['harga_satuan'],
                'total' => $jumlah_beli * $barang['harga_satuan']
            ];
            $_SESSION['cart'][] = $item_array;
        }
    }
}

if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: transaksi.php");
}

if (isset($_POST['simpan_transaksi'])) {
    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Keranjang masih kosong!');</script>";
    } else {
        $tanggal = date('Y-m-d');
        
        mysqli_query($conn, "INSERT INTO transaction (id_transaksi, nomor_transaksi, tanggal_transaksi) VALUES ('$next_id_trx', '$nomor_transaksi', '$tanggal')");

        $q_it = mysqli_query($conn, "SELECT MAX(id) as max_id FROM items_transaction");
        $d_it = mysqli_fetch_assoc($q_it);
        $next_id_it = $d_it['max_id'] ? $d_it['max_id'] + 1 : 1;

        foreach ($_SESSION['cart'] as $cart) {
            $id_b = $cart['id_barang'];
            $jml = $cart['jumlah'];
            $sat = $cart['satuan'];
            $hrg = $cart['harga_satuan'];
            $tot = $cart['total'];

            mysqli_query($conn, "INSERT INTO items_transaction (id, jumlah, satuan, harga_satuan, total, id_barang, id_transaksi) 
                                 VALUES ('$next_id_it', '$jml', '$sat', '$hrg', '$tot', '$id_b', '$next_id_trx')");
            
            mysqli_query($conn, "UPDATE items SET jumlah = jumlah - $jml WHERE id_barang = '$id_b'");
            
            $next_id_it++;
        }

        unset($_SESSION['cart']);
        echo "<script>alert('Transaksi Berhasil Disimpan!'); window.location='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Pengeluaran Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Form Transaksi Pengeluaran Barang</h2>
    <a href="index.php">Lihat Report</a> | <a href="master_barang.php">Master Barang</a>
    <hr>

    <div class="box">
        <h3>Informasi Transaksi</h3>
        <p><strong>No. Transaksi:</strong> <?php echo $nomor_transaksi; ?></p>
        <p><strong>Tanggal:</strong> <?php echo date('Y-m-d'); ?></p>
    </div>

    <div class="box">
        <h3>Pilih Barang</h3>
        <form method="POST" action="">
            <label>Pilih Barang:</label>
            <select name="id_barang" required>
                <option value="">-- Pilih Barang --</option>
                <?php
                $items = mysqli_query($conn, "SELECT * FROM items WHERE jumlah > 0");
                while ($it = mysqli_fetch_assoc($items)) {
                    echo "<option value='{$it['id_barang']}'>{$it['nama_barang']} (Stok: {$it['jumlah']} {$it['satuan']})</option>";
                }
                ?>
            </select>
            
            <label>Jumlah Keluar:</label>
            <input type="number" step="any" name="jumlah" required>
            
            <button type="submit" name="tambah_keranjang">Tambah ke Keranjang</button>
        </form>
    </div>

    <h3>Keranjang Transaksi</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Harga Satuan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $grand_total = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $index => $row) {
                $grand_total += $row['total'];
                echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_barang']}</td>
                    <td>{$row['jumlah']}</td>
                    <td>{$row['satuan']}</td>
                    <td>" . number_format($row['harga_satuan']) . "</td>
                    <td>" . number_format($row['total']) . "</td>
                    <td><a href='transaksi.php?hapus={$index}' onclick='return confirm(\"Hapus item?\")'>Hapus</a></td>
                </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Keranjang kosong</td></tr>";
        }
        ?>
    </table>

    <br>
    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="POST" action="">
            <button type="submit" name="simpan_transaksi" class="btn-success">Simpan Transaksi Permanen</button>
        </form>
    <?php endif; ?>

</body>
</html>