<?php
$page_title='Data Apoteker';
require_once __DIR__.'/../includes/header.php';
require_role('admin');

if (isset($_GET['delete'])) {
  $conn->prepare("DELETE FROM users WHERE id=? AND role='apoteker'")->execute([(int)$_GET['delete']]);
  flash('success','Apoteker dihapus.'); header('Location: apoteker.php'); exit;
}
$rows = $conn->query("SELECT * FROM users WHERE role='apoteker' ORDER BY id DESC")->fetchAll();
?>
<div class="d-flex justify-content-between mb-3">
  <h2>Data Apoteker</h2>
  <a href="apoteker_form.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Tambah Apoteker</a>
</div>
<div class="card p-3"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>#</th><th>Username</th><th>Nama</th><th>Email</th><th>Telp</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $i=>$r):?>
<tr><td><?= $i+1 ?></td><td><?= e($r['username']) ?></td><td><?= e($r['nama_lengkap']) ?></td>
<td><?= e($r['email']) ?></td><td><?= e($r['no_telp']) ?></td>
<td><span class="badge bg-<?= $r['status']=='aktif'?'success':'secondary' ?>"><?= $r['status'] ?></span></td>
<td>
  <a class="btn btn-sm btn-warning" href="apoteker_form.php?id=<?= $r['id'] ?>"><i class="bi bi-pencil"></i></a>
  <a class="btn btn-sm btn-danger" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Hapus apoteker ini?')"><i class="bi bi-trash"></i></a>
</td></tr>
<?php endforeach; if(!$rows):?><tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr><?php endif;?>
</tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
