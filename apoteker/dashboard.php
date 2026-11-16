<?php
$page_title='Dashboard Apoteker';
require_once __DIR__.'/../includes/header.php';
require_role('apoteker');
$obat=$conn->query("SELECT COUNT(*) FROM obat WHERE status='tersedia'")->fetchColumn();
$jualHari=$conn->query("SELECT COALESCE(SUM(total),0) FROM penjualan WHERE DATE(tgl_penjualan)=CURDATE()")->fetchColumn();
$jmlTxn=$conn->query("SELECT COUNT(*) FROM penjualan WHERE DATE(tgl_penjualan)=CURDATE()")->fetchColumn();
$kad=$conn->query("SELECT COUNT(*) FROM obat WHERE tgl_kadaluarsa<=DATE_ADD(CURDATE(),INTERVAL 30 DAY)")->fetchColumn();
$obatTerjual=$conn->query("SELECT o.nama_obat, SUM(d.jumlah) qty FROM penjualan_detail d JOIN obat o ON o.id=d.obat_id GROUP BY o.id ORDER BY qty DESC LIMIT 5")->fetchAll();
?>
<h2 class="mb-4">Dashboard Apoteker</h2>
<div class="row g-3 mb-4">
<div class="col-md-3"><div class="card stat-card p-3"><div class="text-muted small">Obat Tersedia</div><h3><?= $obat ?></h3></div></div>
<div class="col-md-3"><div class="card stat-card info p-3"><div class="text-muted small">Transaksi Hari Ini</div><h3><?= $jmlTxn ?></h3></div></div>
<div class="col-md-3"><div class="card stat-card p-3"><div class="text-muted small">Penjualan Hari Ini</div><h3>Rp <?= number_format($jualHari,0,',','.') ?></h3></div></div>
<div class="col-md-3"><div class="card stat-card warning p-3"><div class="text-muted small">Akan Kadaluarsa</div><h3><?= $kad ?></h3></div></div>
</div>
<div class="card p-3"><h5>Top 5 Obat Terjual</h5>
<table class="table"><thead><tr><th>Nama Obat</th><th class="text-end">Qty Terjual</th></tr></thead><tbody>
<?php foreach($obatTerjual as $o):?><tr><td><?= e($o['nama_obat']) ?></td><td class="text-end"><?= $o['qty'] ?></td></tr><?php endforeach;
if(!$obatTerjual):?><tr><td colspan="2" class="text-center text-muted">Belum ada penjualan.</td></tr><?php endif;?>
</tbody></table></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
