<?php
include 'koneksi.php';

/* ======================
   MODE STRUK (PALING ATAS & BERHENTI TOTAL)
====================== */
if (isset($_GET['struk'])) {

    $id = $_GET['struk'];

    $trx = mysqli_query($conn,"SELECT * FROM transaksi WHERE id='$id'");
    $t = mysqli_fetch_assoc($trx);

    $detail = mysqli_query($conn,"
        SELECT d.*, b.nama_barang
        FROM transaksi_detail d
        JOIN barang b ON d.barang_id=b.id_barang
        WHERE d.transaksi_id='$id'
    ");
?>
<!DOCTYPE html>
<html>
<head>
<title>Struk Transaksi</title>
<style>
body{font-family:monospace;background:#f3f4f6}
.struk{
    width:320px;background:#fff;padding:15px;margin:30px auto;
    border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,.1)
}
.center{text-align:center}
hr{border:dashed 1px #999}
table{width:100%;font-size:12px}
.right{text-align:right}
button{
    width:100%;padding:10px;margin-top:10px;
    border:none;border-radius:6px;cursor:pointer
}
.print{background:#2563eb;color:#fff}
.back{background:#e5e7eb}
@media print{
    button{display:none}
    body{background:#fff}
}
</style>
</head>
<body>

<div class="struk">
<div class="center">
<b>MART KAMPUS</b><br>
===================
</div>

Kode : <?= $t['kode_transaksi'] ?><br>
Tgl  : <?= date('d-m-Y H:i',strtotime($t['created_at'])) ?>

<hr>

<table>
<?php while($d=mysqli_fetch_assoc($detail)): ?>
<tr>
<td colspan="2"><?= $d['nama_barang'] ?></td>
</tr>
<tr>
<td><?= $d['jumlah'] ?> x <?= number_format($d['harga'],0,',','.') ?></td>
<td class="right"><?= number_format($d['total'],0,',','.') ?></td>
</tr>
<?php endwhile; ?>
</table>

<hr>

<table>
<tr><td>Total</td><td class="right"><?= number_format($t['total_belanja'],0,',','.') ?></td></tr>
<tr><td>Bayar</td><td class="right"><?= number_format($t['total_bayar'],0,',','.') ?></td></tr>
<tr><td>Kembali</td><td class="right"><?= number_format($t['total_bayar']-$t['total_belanja'],0,',','.') ?></td></tr>
</table>

<hr>
<div class="center">TERIMA KASIH 💙</div>

<button class="print" onclick="window.print()">🖨 Print</button>
<button class="back" onclick="location.href='dashboard.php?page=penjualan&reset=1'">⬅ Kembali</button>
</div>

</body>
</html>
<?php
exit;
}

/* ======================
   RESET AMAN SETELAH STRUK
====================== */
if (isset($_GET['reset'])) {
    $_SESSION['cart'] = [];
}

/* ======================
   INIT KERANJANG
====================== */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ======================
   TAMBAH BARANG
====================== */
if (isset($_POST['tambah'])) {

    $id_barang = $_POST['id_barang'];
    $jumlah = (int)$_POST['jumlah'];

    $q = mysqli_query($conn,"SELECT * FROM barang WHERE id_barang='$id_barang'");
    $b = mysqli_fetch_assoc($q);

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id_barang) {
            $item['jumlah'] += $jumlah;
            $item['total'] = $item['jumlah'] * $item['harga'];
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id'=>$b['id_barang'],
            'nama'=>$b['nama_barang'],
            'harga'=>$b['harga'],
            'jumlah'=>$jumlah,
            'total'=>$b['harga']*$jumlah
        ];
    }

    header("Location: ".$_SERVER['REQUEST_URI']);
    exit;
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
if (isset($_POST['simpan'])) {

    if (empty($_SESSION['cart'])) {
        echo "<script>alert('Keranjang masih kosong');</script>";
    } else {

        $user_id = $_SESSION['user_id'] ?? 1;
        $tanggal = date('Y-m-d');
        $created = date('Y-m-d H:i:s');

        $total_belanja = 0;
        foreach ($_SESSION['cart'] as $c) {
            $total_belanja += $c['total'];
        }

        $total_bayar = (int)$_POST['total_bayar'];

        if ($total_bayar < $total_belanja) {
            echo "<script>alert('Pembayaran kurang');</script>";
        } else {

            $kode = 'TRX'.date('YmdHis');

            mysqli_query($conn,"
                INSERT INTO transaksi
                (kode_transaksi,user_id,total_belanja,total_bayar,tanggal,created_at)
                VALUES
                ('$kode','$user_id','$total_belanja','$total_bayar','$tanggal','$created')
            ");

            $trx_id = mysqli_insert_id($conn);

            foreach ($_SESSION['cart'] as $c) {
                mysqli_query($conn,"
                    INSERT INTO transaksi_detail
                    (transaksi_id,barang_id,harga,jumlah,total)
                    VALUES
                    ('$trx_id','{$c['id']}','{$c['harga']}','{$c['jumlah']}','{$c['total']}')
                ");

                mysqli_query($conn,"
                    UPDATE barang SET stok = stok - {$c['jumlah']}
                    WHERE id_barang='{$c['id']}'
                ");
            }

            $_SESSION['cart'] = [];

            echo "<script>
                alert('Transaksi berhasil');
                location='dashboard.php?page=penjualan&struk=$trx_id';
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Transaksi Penjualan</title>
<style>
body{background:#f3f4f6;font-family:Arial}
.card{
    background:#fff;padding:20px;border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,.08)
}
h2{margin-bottom:15px}
form{display:flex;gap:10px;flex-wrap:wrap}
select,input,button{
    padding:10px;border-radius:6px;border:1px solid #d1d5db
}
button{background:#2563eb;color:#fff;border:none;cursor:pointer}
table{
    width:100%;border-collapse:collapse;margin-top:20px
}
th{
    background:#1f2933;color:#fff;padding:10px
}
td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:center}
.total{font-size:18px;font-weight:bold;margin:15px 0}
a{color:#dc2626;text-decoration:none}
</style>
</head>
<body>

<div class="card">
<h2>Transaksi Penjualan</h2>

<form method="post">
<select name="id_barang" required>
<option value="">-- Pilih Barang --</option>
<?php
$q = mysqli_query($conn,"SELECT * FROM barang WHERE stok>0");
while($b=mysqli_fetch_assoc($q)){
echo "<option value='$b[id_barang]'>
$b[nama_barang] - Rp ".number_format($b['harga'],0,',','.')."
</option>";
}
?>
</select>

<input type="number" name="jumlah" min="1" placeholder="Jumlah" required>
<button name="tambah">Tambah</button>
</form>

<table>
<tr>
<th>No</th><th>Nama</th><th>Harga</th><th>Jumlah</th><th>Total</th><th>Aksi</th>
</tr>

<?php
$grand=0;
foreach($_SESSION['cart'] as $i=>$c):
$grand+=$c['total'];
?>
<tr>
<td><?= $i+1 ?></td>
<td><?= $c['nama'] ?></td>
<td>Rp <?= number_format($c['harga'],0,',','.') ?></td>
<td><?= $c['jumlah'] ?></td>
<td>Rp <?= number_format($c['total'],0,',','.') ?></td>
<td><a href="?page=penjualan&hapus=<?= $i ?>">Hapus</a></td>
</tr>
<?php endforeach; ?>
</table>

<div class="total">Total: Rp <?= number_format($grand,0,',','.') ?></div>

<form method="post">
<input type="number" name="total_bayar" placeholder="Total Bayar" required>
<button name="simpan">Simpan</button>
</form>
</div>

</body>
</html>
