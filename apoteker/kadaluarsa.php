<?php
$page_title='Hapus Obat Kadaluarsa';
require_once __DIR__.'/../includes/header.php';
require_role('apoteker');
if (isset($_GET['delete'])){
  $conn->prepare("DELETE FROM obat WHERE id=? AND tgl_kadaluarsa<=CURDATE()")->execute([(int)$_GET['delete']]);
  flash('success','Obat kadaluarsa dihapus.'); header('Location: kadaluarsa.php'); exit;
}
if (isset($_POST['hapus_semua'])){
  $n=$conn->exec("DELETE FROM obat WHERE tgl_kadaluarsa<=CURDATE()");
  flash('success',"$n obat kadaluarsa dihapus."); header('Location: kadaluarsa.php'); exit;
}
$rows=$conn->query("SELECT * FROM obat WHERE tgl_kadaluarsa<=DATE_ADD(CURDATE(),INTERVAL 30 DAY) ORDER BY tgl_kadaluarsa")->fetchAll();
?>
<div class="d-flex justify-content-between mb-3"><h2>Obat Kadaluarsa</h2>
<form method="post" onsubmit="return confirm('Hapus SEMUA obat yang sudah kadaluarsa?')">
<button name="hapus_semua" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus Semua Yang Kadaluarsa</button>
</form></div>
<div class="card p-3"><table class="table">
<thead><tr><th>Kode</th><th>Nama</th><th>Stok</th><th>Tgl Kadaluarsa</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r): $exp=strtotime($r['tgl_kadaluarsa'])<=time(); ?>
<tr class="<?= $exp?'table-danger':'table-warning' ?>">
<td><?= e($r['kode_obat']) ?></td><td><?= e($r['nama_obat']) ?></td>
<td><?= $r['stok'] ?></td><td><?= e($r['tgl_kadaluarsa']) ?></td>
<td><?= $exp?'KADALUARSA':'Akan kadaluarsa' ?></td>
<td><?php if($exp):?><a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Hapus?')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a><?php endif;?></td>
</tr>
<?php endforeach; if(!$rows):?><tr><td colspan="6" class="text-center text-muted">Tidak ada obat kadaluarsa.</td></tr><?php endif;?>
</tbody></table></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
