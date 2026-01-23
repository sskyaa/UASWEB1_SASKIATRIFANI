<?php
include 'koneksi.php';

/* ======================
   PARAMETER AKSI
====================== */
$aksi = $_GET['aksi'] ?? 'list';
$id   = $_GET['id'] ?? null;

/* ======================
   PROSES TAMBAH DATA
====================== */
if ($aksi == 'tambah' && isset($_POST['simpan'])) {
    mysqli_query($conn, "
        INSERT INTO barang (kode_barang, nama_barang, kategori, harga, stok, satuan)
        VALUES (
            '$_POST[kode]',
            '$_POST[nama]',
            '$_POST[kategori]',
            '$_POST[harga]',
            '$_POST[stok]',
            '$_POST[satuan]'
        )
    ");

    echo "<script>
        alert('Data berhasil ditambahkan');
        location='dashboard.php?page=listproducts';
    </script>";
}

/* ======================
   PROSES UPDATE
====================== */
if ($aksi == 'edit' && isset($_POST['update'])) {
    mysqli_query($conn, "
        UPDATE barang SET
            kode_barang = '$_POST[kode]',
            nama_barang = '$_POST[nama]',
            kategori    = '$_POST[kategori]',
            harga       = '$_POST[harga]',
            stok        = '$_POST[stok]',
            satuan      = '$_POST[satuan]'
        WHERE id_barang = '$id'
    ");

    echo "<script>
        alert('Data berhasil diperbarui');
        location='dashboard.php?page=listproducts';
    </script>";
}

/* ======================
   PROSES HAPUS
====================== */
if ($aksi == 'hapus' && $id) {
    mysqli_query($conn, "DELETE FROM barang WHERE id_barang='$id'");
    echo "<script>
        alert('Data berhasil dihapus');
        location='dashboard.php?page=listproducts';
    </script>";
}
?>

<style>
.card {
    background: white;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.btn {
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    color: white;
    font-size: 14px;
}
.btn-tambah { background: #27ae60; }
.btn-edit   { background: #2980b9; }
.btn-hapus  { background: #c0392b; }

table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
th {
    background: #f8f8f8;
}

/* ===== FORM STYLE ASLI ===== */
.form-container {
    display: flex;
    justify-content: center;
    padding-top: 40px;
}
.form-wrapper {
    width: 100%;
    max-width: 1000px;
    background: #f9fafb;
    border-radius: 14px;
    padding: 30px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 25px rgba(0,0,0,.05);
}
.form-title {
    font-size: 22px;
    font-weight: 600;
    color: #1f2933;
    margin-bottom: 25px;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
.form-control {
    display: flex;
    flex-direction: column;
}
.form-control label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 6px;
}
.form-control input {
    padding: 11px 12px;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    background: white;
}
.form-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 25px;
    gap: 10px;
}
.btn-save { background: #374151; }
.btn-back { background: #9ca3af; }
</style>

<!-- ======================
     MODE LIST
====================== -->
<?php if ($aksi == 'list') : ?>
<?php $data = mysqli_query($conn, "SELECT * FROM barang"); ?>

<div class="card">
    <div class="card-header">
        <h3>List Produk</h3>
        <a href="dashboard.php?page=listproducts&aksi=tambah" class="btn btn-tambah">
            + Tambah Produk
        </a>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Aksi</th>
        </tr>

        <?php $no=1; while($row=mysqli_fetch_assoc($data)) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['kode_barang']; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td><?= $row['kategori']; ?></td>
            <td>Rp <?= number_format($row['harga'],0,',','.'); ?></td>
            <td><?= $row['stok']; ?></td>
            <td><?= $row['satuan']; ?></td>
            <td>
                <a href="dashboard.php?page=listproducts&aksi=edit&id=<?= $row['id_barang']; ?>" class="btn btn-edit">Edit</a>
                <a href="dashboard.php?page=listproducts&aksi=hapus&id=<?= $row['id_barang']; ?>" class="btn btn-hapus"
                   onclick="return confirm('Yakin hapus data?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php endif; ?>

<!-- ======================
     MODE TAMBAH
====================== -->
<?php if ($aksi == 'tambah') : ?>
<div class="form-container">
<div class="form-wrapper">
<div class="form-title">Tambah Data Produk</div>

<form method="post">
<div class="form-grid">
<div class="form-control"><label>Kode Barang</label><input type="text" name="kode" required></div>
<div class="form-control"><label>Nama Barang</label><input type="text" name="nama" required></div>
<div class="form-control"><label>Kategori</label><input type="text" name="kategori"></div>
<div class="form-control"><label>Harga</label><input type="number" name="harga" required></div>
<div class="form-control"><label>Stok</label><input type="number" name="stok" required></div>
<div class="form-control"><label>Satuan</label><input type="text" name="satuan"></div>
</div>

<div class="form-footer">
<a href="dashboard.php?page=listproducts" class="btn btn-back">Kembali</a>
<button type="submit" name="simpan" class="btn btn-save">Simpan</button>
</div>
</form>
</div>
</div>
<?php endif; ?>

<!-- ======================
     MODE EDIT
====================== -->
<?php if ($aksi == 'edit' && $id) : ?>
<?php
$q = mysqli_query($conn,"SELECT * FROM barang WHERE id_barang='$id'");
$data = mysqli_fetch_assoc($q);
?>
<div class="form-container">
<div class="form-wrapper">
<div class="form-title">Edit Data Produk</div>

<form method="post">
<div class="form-grid">
<div class="form-control"><label>Kode Barang</label><input type="text" name="kode" value="<?= $data['kode_barang']; ?>" required></div>
<div class="form-control"><label>Nama Barang</label><input type="text" name="nama" value="<?= $data['nama_barang']; ?>" required></div>
<div class="form-control"><label>Kategori</label><input type="text" name="kategori" value="<?= $data['kategori']; ?>"></div>
<div class="form-control"><label>Harga</label><input type="number" name="harga" value="<?= $data['harga']; ?>" required></div>
<div class="form-control"><label>Stok</label><input type="number" name="stok" value="<?= $data['stok']; ?>" required></div>
<div class="form-control"><label>Satuan</label><input type="text" name="satuan" value="<?= $data['satuan']; ?>"></div>
</div>

<div class="form-footer">
<a href="dashboard.php?page=listproducts" class="btn btn-back">Kembali</a>
<button type="submit" name="update" class="btn btn-save">Simpan</button>
</div>
</form>
</div>
</div>
<?php endif; ?>
