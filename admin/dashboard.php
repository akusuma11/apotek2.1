<?php
$page_title='Dashboard Admin';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
$obat = $conn->query("SELECT COUNT(*) FROM obat")->fetchColumn();
$apo  = $conn->query("SELECT COUNT(*) FROM users WHERE role='apoteker'")->fetchColumn();
$pel  = $conn->query("SELECT COUNT(*) FROM users WHERE role='pelanggan'")->fetchColumn();
$sup  = $conn->query("SELECT COUNT(*) FROM supplier")->fetchColumn();
$exp  = $conn->query("SELECT COUNT(*) FROM obat WHERE tgl_kadaluarsa <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)")->fetchColumn();
$jual = $conn->query("SELECT COALESCE(SUM(total),0) FROM penjualan WHERE MONTH(tgl_penjualan)=MONTH(CURDATE())")->fetchColumn();
?>
<h2 class="mb-4">Dashboard Administrator</h2>
<div class="row g-3">
  <?php foreach([['Obat',$obat,'capsule','success'],['Apoteker',$apo,'person-badge','info'],['Pelanggan',$pel,'people','info'],['Supplier',$sup,'truck','success'],['Akan Kadaluarsa (30 hari)',$exp,'exclamation-triangle','warning'],['Penjualan Bulan Ini','Rp '.number_format($jual,0,',','.'),'graph-up','danger']] as $c):?>
  <div class="col-md-4"><div class="card stat-card <?= $c[3]==='warning'?'warning':($c[3]==='danger'?'danger':'') ?> p-3">
    <div class="d-flex justify-content-between align-items-center">
      <div><div class="text-muted small"><?= $c[0] ?></div><h3 class="mb-0"><?= $c[1] ?></h3></div>
      <i class="bi bi-<?= $c[2] ?> text-<?= $c[3] ?>" style="font-size:2.5rem"></i>
    </div></div></div>
  <?php endforeach;?>
</div>
<?php require __DIR__.'/../includes/footer.php'; ?>
