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
        INSERT INTO pelanggan (kode_pelanggan, nama_pelanggan, alamat, no_hp, email)
        VALUES (
            '$_POST[kode]',
            '$_POST[nama]',
            '$_POST[alamat]',
            '$_POST[no_hp]',
            '$_POST[email]'
        )
    ");

    echo "<script>
        alert('Data pelanggan berhasil ditambahkan');
        location='dashboard.php?page=customer';
    </script>";
}

/* ======================
   PROSES UPDATE
====================== */
if ($aksi == 'edit' && isset($_POST['update'])) {
    mysqli_query($conn, "
        UPDATE pelanggan SET
            kode_pelanggan = '$_POST[kode]',
            nama_pelanggan = '$_POST[nama]',
            alamat         = '$_POST[alamat]',
            no_hp          = '$_POST[no_hp]',
            email          = '$_POST[email]'
        WHERE id_pelanggan = '$id'
    ");

    echo "<script>
        alert('Data pelanggan berhasil diperbarui');
        location='dashboard.php?page=customer';
    </script>";
}

/* ======================
   PROSES HAPUS
====================== */
if ($aksi == 'hapus' && $id) {
    mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan='$id'");
    echo "<script>
        alert('Data pelanggan berhasil dihapus');
        location='dashboard.php?page=customer';
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

/* ===== FORM STYLE ===== */
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
<?php $data = mysqli_query($conn, "SELECT * FROM pelanggan"); ?>

<div class="card">
    <div class="card-header">
        <h3>List Pelanggan</h3>
        <a href="dashboard.php?page=customer&aksi=tambah" class="btn btn-tambah">
            + Tambah Pelanggan
        </a>
    </div>

    <table>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>

        <?php $no=1; while($row=mysqli_fetch_assoc($data)) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['kode_pelanggan']; ?></td>
            <td><?= $row['nama_pelanggan']; ?></td>
            <td><?= $row['alamat']; ?></td>
            <td><?= $row['no_hp']; ?></td>
            <td><?= $row['email']; ?></td>
            <td>
                <a href="dashboard.php?page=customer&aksi=edit&id=<?= $row['id_pelanggan']; ?>" class="btn btn-edit">Edit</a>
                <a href="dashboard.php?page=customer&aksi=hapus&id=<?= $row['id_pelanggan']; ?>" class="btn btn-hapus"
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
<div class="form-title">Tambah Data Pelanggan</div>

<form method="post">
<div class="form-grid">
<div class="form-control"><label>Kode Pelanggan</label><input type="text" name="kode"></div>
<div class="form-control"><label>Nama Pelanggan</label><input type="text" name="nama" required></div>
<div class="form-control"><label>Alamat</label><input type="text" name="alamat"></div>
<div class="form-control"><label>No HP</label><input type="text" name="no_hp"></div>
<div class="form-control"><label>Email</label><input type="email" name="email"></div>
</div>

<div class="form-footer">
<a href="dashboard.php?page=customer" class="btn btn-back">Kembali</a>
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
$q = mysqli_query($conn,"SELECT * FROM pelanggan WHERE id_pelanggan='$id'");
$data = mysqli_fetch_assoc($q);
?>

<div class="form-container">
<div class="form-wrapper">
<div class="form-title">Edit Data Pelanggan</div>

<form method="post">
<div class="form-grid">
<div class="form-control"><label>Kode Pelanggan</label>
<input type="text" name="kode" value="<?= $data['kode_pelanggan']; ?>"></div>

<div class="form-control"><label>Nama Pelanggan</label>
<input type="text" name="nama" value="<?= $data['nama_pelanggan']; ?>" required></div>

<div class="form-control"><label>Alamat</label>
<input type="text" name="alamat" value="<?= $data['alamat']; ?>"></div>

<div class="form-control"><label>No HP</label>
<input type="text" name="no_hp" value="<?= $data['no_hp']; ?>"></div>

<div class="form-control"><label>Email</label>
<input type="email" name="email" value="<?= $data['email']; ?>"></div>
</div>

<div class="form-footer">
<a href="dashboard.php?page=customer" class="btn btn-back">Kembali</a>
<button type="submit" name="update" class="btn btn-save">Simpan</button>
</div>
</form>
</div>
</div>
<?php endif; ?>
