<?php
$page_title='Data Obat';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
if (isset($_GET['delete'])){ $conn->prepare("DELETE FROM obat WHERE id=?")->execute([(int)$_GET['delete']]); flash('success','Obat dihapus.'); header('Location: obat.php'); exit; }
if (isset($_GET['set_status'])){
  $conn->prepare("UPDATE obat SET status=? WHERE id=?")->execute([$_GET['status'],(int)$_GET['set_status']]);
  flash('success','Status diperbarui.'); header('Location: obat.php'); exit;
}
$q = trim($_GET['q']??'');
$sql="SELECT o.*, s.nama_supplier FROM obat o LEFT JOIN supplier s ON s.id=o.supplier_id";
$p=[]; if ($q){ $sql.=" WHERE o.nama_obat LIKE ? OR o.kode_obat LIKE ?"; $p=["%$q%","%$q%"]; }
$sql.=" ORDER BY o.id DESC";
$st=$conn->prepare($sql); $st->execute($p); $rows=$st->fetchAll();
?>
<div class="d-flex justify-content-between mb-3"><h2>Data Obat</h2>
<a href="obat_form.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Obat</a></div>
<form class="card p-3 mb-3"><div class="row g-2"><div class="col-md-10"><input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Cari nama / kode obat..."></div>
<div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button></div></div></form>
<div class="card p-3"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>Supplier</th><th>Harga</th><th>Stok</th><th>Kadaluarsa</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r): $exp=strtotime($r['tgl_kadaluarsa'])<strtotime('+30 days'); ?>
<tr><td><?= e($r['kode_obat']) ?></td>
<td><a href="../apoteker/obat_detail.php?id=<?= $r['id'] ?>"><?= e($r['nama_obat']) ?></a></td>
<td><?= e($r['kategori']) ?></td><td><?= e($r['nama_supplier']) ?></td>
<td>Rp <?= number_format($r['harga_jual'],0,',','.') ?></td>
<td><?= $r['stok'] ?></td>
<td class="<?= $exp?'text-danger fw-bold':'' ?>"><?= e($r['tgl_kadaluarsa']) ?></td>
<td><span class="badge bg-<?= $r['status']=='tersedia'?'success':($r['status']=='habis'?'secondary':'danger') ?>"><?= $r['status'] ?></span></td>
<td>
<div class="btn-group btn-group-sm">
  <a class="btn btn-warning" href="obat_form.php?id=<?= $r['id'] ?>"><i class="bi bi-pencil"></i></a>
  <a class="btn btn-danger" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a>
  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">Status</button>
  <ul class="dropdown-menu">
    <?php foreach(['tersedia','habis','ditarik'] as $s):?>
    <li><a class="dropdown-item" href="?set_status=<?= $r['id'] ?>&status=<?= $s ?>"><?= ucfirst($s) ?></a></li>
    <?php endforeach;?>
  </ul>
</div>
</td></tr>
<?php endforeach; if(!$rows):?><tr><td colspan="9" class="text-center text-muted">Tidak ada data.</td></tr><?php endif;?>
</tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
