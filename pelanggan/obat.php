<?php
$page_title='Daftar Obat';
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');
$q=trim($_GET['q']??'');
$sql="SELECT * FROM obat WHERE status='tersedia' AND stok>0";
$p=[]; if ($q){ $sql.=" AND (nama_obat LIKE ? OR kategori LIKE ?)"; $p=["%$q%","%$q%"]; }
$sql.=" ORDER BY nama_obat";
$st=$conn->prepare($sql); $st->execute($p); $rows=$st->fetchAll();
$cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'qty'));
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">Katalog Obat</h2>
  <a href="keranjang.php" class="btn btn-success position-relative">
    <i class="bi bi-cart3"></i> Keranjang
    <?php if($cartCount>0): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span><?php endif; ?>
  </a>
</div>
<form class="card p-3 mb-3"><div class="input-group"><input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Cari obat..."><button class="btn btn-primary"><i class="bi bi-search"></i> Cari</button></div></form>
<div class="row g-3">
<?php foreach($rows as $r):?>
<div class="col-md-4"><div class="card h-100 p-3">
<div class="d-flex justify-content-between"><span class="badge bg-success"><?= e($r['kategori']) ?></span>
<small class="text-muted"><?= e($r['kode_obat']) ?></small></div>
<h5 class="mt-2"><?= e($r['nama_obat']) ?></h5>
<p class="text-muted small"><?= e(mb_strimwidth($r['deskripsi']??'',0,90,'...')) ?></p>
<div class="d-flex justify-content-between align-items-center mb-2">
<h5 class="text-success mb-0">Rp <?= number_format($r['harga_jual'],0,',','.') ?></h5>
<small>Stok: <?= $r['stok'] ?></small></div>
<form method="post" action="cart_action.php" class="d-flex gap-2">
  <input type="hidden" name="action" value="add">
  <input type="hidden" name="id" value="<?= $r['id'] ?>">
  <input type="number" name="qty" value="1" min="1" max="<?= $r['stok'] ?>" class="form-control form-control-sm" style="max-width:80px">
  <button class="btn btn-sm btn-success flex-grow-1"><i class="bi bi-cart-plus"></i> Tambah</button>
</form>
</div></div>
<?php endforeach; if(!$rows):?><div class="col-12 text-center text-muted py-5">Obat tidak ditemukan.</div><?php endif;?>
</div>
<?php require __DIR__.'/../includes/footer.php'; ?>
