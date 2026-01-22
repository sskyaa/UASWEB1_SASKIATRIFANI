<?php
include 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<h3>ID tidak ditemukan</h3>";
    exit;
}

// Ambil data barang
$q = mysqli_query($conn, "SELECT * FROM barang WHERE id_barang='$id'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    echo "<h3>Data tidak ditemukan</h3>";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
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
?>

<style>
/* === CONTAINER (UNTUK TENGAH) === */
.form-container {
    display: flex;
    justify-content: center;
    padding-top: 40px;
}

/* === WRAPPER === */
.form-wrapper {
    width: 100%;
    max-width: 1000px;
    background: #f9fafb;
    border-radius: 14px;
    padding: 30px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 25px rgba(0,0,0,.05);
}

/* === HEADER === */
.form-title {
    font-size: 22px;
    font-weight: 600;
    color: #1f2933;
    margin-bottom: 25px;
}

/* === GRID === */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

/* === INPUT === */
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

.form-control input:focus {
    outline: none;
    border-color: #374151;
    box-shadow: 0 0 0 3px rgba(56,189,248,.25);
}

/* === ACTION === */
.form-footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 25px;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    text-decoration: none;
    border: none;
    cursor: pointer;
}

.btn-save {
    background: linear-gradient(135deg, #376174, #374151);
    color: white;
}

.btn-save:hover {
    opacity: .9;
}

.btn-back {
    background: #d1ddf7;
    color:#374151;
}
.btn-back:hover {
    color: #a0bbf5;
}
</style>

<div class="form-container">
    <div class="form-wrapper">

        <div class="form-title">Edit Data Produk</div>

        <form method="post">
            <div class="form-grid">

                <div class="form-control">
                    <label>Kode Barang</label>
                    <input type="text" name="kode" value="<?= $data['kode_barang']; ?>" required>
                </div>

                <div class="form-control">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" value="<?= $data['nama_barang']; ?>" required>
                </div>

                <div class="form-control">
                    <label>Kategori</label>
                    <input type="text" name="kategori" value="<?= $data['kategori']; ?>">
                </div>

                <div class="form-control">
                    <label>Harga</label>
                    <input type="number" name="harga" value="<?= $data['harga']; ?>" required>
                </div>

                <div class="form-control">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?= $data['stok']; ?>" required>
                </div>

                <div class="form-control">
                    <label>Satuan</label>
                    <input type="text" name="satuan" value="<?= $data['satuan']; ?>">
                </div>

            </div>

            <div class="form-footer">
                <a href="dashboard.php?page=listproducts" class="btn btn-back">Kembali</a>
                <button type="submit" name="update" class="btn btn-save">Simpan</button>
            </div>
        </form>

    </div>
</div>
