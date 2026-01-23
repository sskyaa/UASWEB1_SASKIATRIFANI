<?php
include 'koneksi.php';

/* ======================
   INISIALISASI KERANJANG
====================== */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ======================
   TAMBAH BARANG
====================== */
if (isset($_POST['tambah'])) {
    $id_barang = $_POST['id_barang'];
    $jumlah    = $_POST['jumlah'];

    $q = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id_barang'");
    $b = mysqli_fetch_assoc($q);

    $_SESSION['cart'][] = [
        'id'     => $b['id_barang'],
        'nama'   => $b['nama_barang'],
        'harga'  => $b['harga'],
        'jumlah' => $jumlah,
        'total'  => $b['harga'] * $jumlah
    ];
}

/* ======================
   HAPUS ITEM
====================== */
if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][$_GET['hapus']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

/* ======================
   SIMPAN TRANSAKSI
====================== */
if (isset($_POST['simpan_transaksi'])) {

    $user_id = $_SESSION['user_id'] ?? 1;
    $tanggal = date('Y-m-d');
    $created = date('Y-m-d H:i:s');

    $total_belanja = 0;
    foreach ($_SESSION['cart'] as $c) {
        $total_belanja += $c['total'];
    }

    $total_bayar = $_POST['total_bayar'];
    $kode_transaksi = 'TRX' . date('YmdHis');

    // INSERT TRANSAKSI
    mysqli_query($conn, "
        INSERT INTO transaksi
        (kode_transaksi, user_id, total_belanja, total_bayar, tanggal, created_at)
        VALUES
        ('$kode_transaksi','$user_id','$total_belanja','$total_bayar','$tanggal','$created')
    ");

    $transaksi_id = mysqli_insert_id($conn);

    // INSERT DETAIL + UPDATE STOK
    foreach ($_SESSION['cart'] as $c) {
        mysqli_query($conn, "
            INSERT INTO transaksi_detail
            (transaksi_id, barang_id, harga, jumlah, total)
            VALUES
            ('$transaksi_id','{$c['id']}','{$c['harga']}','{$c['jumlah']}','{$c['total']}')
        ");

        mysqli_query($conn, "
            UPDATE barang
            SET stok = stok - {$c['jumlah']}
            WHERE id_barang = '{$c['id']}'
        ");
    }

    unset($_SESSION['cart']);

    echo "<script>
        alert('Transaksi berhasil disimpan');
        location='dashboard.php?page=penjualan';
    </script>";
}
?>

<style>
.card{background:#fff;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.08)}
.row{display:flex;gap:10px;margin-bottom:15px}
input,select{padding:10px;border-radius:8px;border:1px solid #d1d5db}
.btn{padding:10px 16px;border:none;border-radius:8px;cursor:pointer}
.btn-add{background:#2563eb;color:#fff}
.btn-del{background:#dc2626;color:#fff}
table{width:100%;border-collapse:collapse;margin-top:15px}
th,td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:center}
th{background:#f9fafb}
.total{text-align:right;font-size:18px;font-weight:600;margin-top:10px}
</style>

<div class="card">
<h2>Transaksi Penjualan</h2>

<form method="post">
<div class="row">
<select name="id_barang" required>
<option value="">-- Pilih Barang --</option>
<?php
$q = mysqli_query($conn,"SELECT * FROM barang WHERE stok > 0");
while($b = mysqli_fetch_assoc($q)){
    echo "<option value='$b[id_barang]'>
        $b[nama_barang] - Rp ".number_format($b['harga'],0,',','.')."
    </option>";
}
?>
</select>

<input type="number" name="jumlah" min="1" placeholder="Jumlah" required>
<button name="tambah" class="btn btn-add">Tambah</button>
</div>
</form>

<table>
<tr>
<th>No</th>
<th>Nama</th>
<th>Harga</th>
<th>Jumlah</th>
<th>Total</th>
<th>Aksi</th>
</tr>

<?php
$grand = 0;
foreach ($_SESSION['cart'] as $i => $c):
$grand += $c['total'];
?>
<tr>
<td><?= $i+1 ?></td>
<td><?= $c['nama'] ?></td>
<td>Rp <?= number_format($c['harga'],0,',','.') ?></td>
<td><?= $c['jumlah'] ?></td>
<td>Rp <?= number_format($c['total'],0,',','.') ?></td>
<td>
<a href="?page=penjualan&hapus=<?= $i ?>" class="btn btn-del">Hapus</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<div class="total">
Total Belanja : Rp <?= number_format($grand,0,',','.') ?>
</div>

<form method="post" style="margin-top:15px;text-align:right">
<button name="simpan_transaksi" class="btn btn-add">Simpan Transaksi</button>
</form>

</div>
