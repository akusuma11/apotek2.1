<?php
$page_title='Report Penjualan';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
$from=$_GET['from']??date('Y-m-01');
$to=$_GET['to']??date('Y-m-d');
$st=$conn->prepare("SELECT p.*, u.nama_lengkap apoteker, (SELECT COUNT(*) FROM penjualan_detail d WHERE d.penjualan_id=p.id) AS jml
  FROM penjualan p LEFT JOIN users u ON u.id=p.apoteker_id
  WHERE DATE(p.tgl_penjualan) BETWEEN ? AND ? ORDER BY p.tgl_penjualan DESC");
$st->execute([$from,$to]); $rows=$st->fetchAll();
$total=array_sum(array_column($rows,'total'));
?>
<h2>Report Penjualan Obat</h2>
<form class="card p-3 mb-3"><div class="row g-2 align-items-end">
<div class="col-md-3"><label class="form-label">Dari</label><input type="date" name="from" value="<?= e($from) ?>" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Sampai</label><input type="date" name="to" value="<?= e($to) ?>" class="form-control"></div>
<div class="col-md-2"><button class="btn btn-primary w-100">Filter</button></div></div></form>
<div class="alert alert-success"><b>Total Penjualan:</b> Rp <?= number_format($total,0,',','.') ?> dari <?= count($rows) ?> transaksi.</div>
<div class="card p-3"><div class="table-responsive"><table class="table">
<thead><tr><th>No Nota</th><th>Tanggal</th><th>Apoteker</th><th>Item</th><th>Total</th></tr></thead><tbody>
<?php foreach($rows as $r):?>
<tr><td><a href="../apoteker/penjualan_detail.php?id=<?= $r['id'] ?>"><?= e($r['no_nota']) ?></a></td>
<td><?= e($r['tgl_penjualan']) ?></td><td><?= e($r['apoteker']) ?></td>
<td><?= $r['jml'] ?></td><td>Rp <?= number_format($r['total'],0,',','.') ?></td></tr>
<?php endforeach; if(!$rows):?><tr><td colspan="5" class="text-center text-muted">Tidak ada data.</td></tr><?php endif;?>
</tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
