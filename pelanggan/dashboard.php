<?php
$page_title='Beranda';
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');
$top=$conn->query("SELECT o.nama_obat, SUM(d.jumlah) qty FROM penjualan_detail d JOIN obat o ON o.id=d.obat_id GROUP BY o.id ORDER BY qty DESC LIMIT 5")->fetchAll();
$jml=$conn->query("SELECT COUNT(*) FROM obat WHERE status='tersedia'")->fetchColumn();
?>
<h2 class="mb-4">Selamat Datang, <?= e($_SESSION['nama']) ?></h2>
<div class="row g-3 mb-4">
<div class="col-md-6"><div class="card p-4 bg-success text-white">
<h5>Jumlah Obat Tersedia</h5><h1><?= $jml ?></h1>
<a href="obat.php" class="btn btn-light btn-sm">Lihat Katalog Obat <i class="bi bi-arrow-right"></i></a>
</div></div>
<div class="col-md-6"><div class="card p-3"><h5>Obat Terlaris</h5>
<ol class="mb-0"><?php foreach($top as $t):?><li><?= e($t['nama_obat']) ?> <small class="text-muted">(<?= $t['qty'] ?> terjual)</small></li><?php endforeach;
if(!$top):?><li class="text-muted">Belum ada penjualan.</li><?php endif;?></ol></div></div>
</div>
<?php require __DIR__.'/../includes/footer.php'; ?>
