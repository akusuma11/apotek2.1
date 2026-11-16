<?php
$page_title='Obat Akan Kadaluarsa';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
$days=(int)($_GET['days']??30);
$st=$conn->prepare("SELECT * FROM obat WHERE tgl_kadaluarsa <= DATE_ADD(CURDATE(), INTERVAL ? DAY) ORDER BY tgl_kadaluarsa ASC");
$st->execute([$days]); $rows=$st->fetchAll();
?>
<h2>Obat Akan/Sudah Kadaluarsa</h2>
<form class="card p-3 mb-3"><div class="row g-2 align-items-end">
<div class="col-md-3"><label class="form-label">Dalam (hari)</label><input type="number" name="days" value="<?= $days ?>" class="form-control"></div>
<div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div></div></form>
<div class="card p-3"><div class="table-responsive"><table class="table table-hover">
<thead><tr><th>Kode</th><th>Nama</th><th>Stok</th><th>Tgl Kadaluarsa</th><th>Status</th></tr></thead><tbody>
<?php foreach($rows as $r): $exp=strtotime($r['tgl_kadaluarsa'])<time(); ?>
<tr class="<?= $exp?'table-danger':'table-warning' ?>"><td><?= e($r['kode_obat']) ?></td>
<td><?= e($r['nama_obat']) ?></td><td><?= $r['stok'] ?></td><td><?= e($r['tgl_kadaluarsa']) ?></td>
<td><?= $exp?'KADALUARSA':'AKAN KADALUARSA' ?></td></tr>
<?php endforeach; if(!$rows):?><tr><td colspan="5" class="text-center text-muted">Tidak ada.</td></tr><?php endif;?>
</tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
