<?php
$page_title='Data Obat';
require_once __DIR__.'/../includes/header.php';
require_role('apoteker');
if (isset($_GET['delete'])){ $conn->prepare("DELETE FROM obat WHERE id=?")->execute([(int)$_GET['delete']]); flash('success','Obat dihapus.'); header('Location: obat.php'); exit; }
$q=trim($_GET['q']??'');
$sql="SELECT o.*, s.nama_supplier FROM obat o LEFT JOIN supplier s ON s.id=o.supplier_id";
$p=[]; if ($q){ $sql.=" WHERE o.nama_obat LIKE ? OR o.kode_obat LIKE ?"; $p=["%$q%","%$q%"]; }
$sql.=" ORDER BY o.nama_obat";
$st=$conn->prepare($sql); $st->execute($p); $rows=$st->fetchAll();
?>
<div class="d-flex justify-content-between mb-3"><h2>Data Obat</h2>
<a href="../admin/obat_form.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Obat</a></div>
<form class="card p-3 mb-3"><div class="input-group"><input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Cari obat..."><button class="btn btn-primary"><i class="bi bi-search"></i></button></div></form>
<div class="card p-3"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Stok</th><th>Harga</th><th>Kadaluarsa</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r):?>
<tr><td><?= e($r['kode_obat']) ?></td>
<td><a href="obat_detail.php?id=<?= $r['id'] ?>"><?= e($r['nama_obat']) ?></a></td>
<td><?= e($r['kategori']) ?></td><td><?= $r['stok'] ?></td>
<td>Rp <?= number_format($r['harga_jual'],0,',','.') ?></td>
<td><?= e($r['tgl_kadaluarsa']) ?></td>
<td>
<a class="btn btn-sm btn-info" href="obat_detail.php?id=<?= $r['id'] ?>"><i class="bi bi-eye"></i></a>
<a class="btn btn-sm btn-warning" href="../admin/obat_form.php?id=<?= $r['id'] ?>"><i class="bi bi-pencil"></i></a>
<a class="btn btn-sm btn-danger" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
</td></tr>
<?php endforeach;?>
</tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
