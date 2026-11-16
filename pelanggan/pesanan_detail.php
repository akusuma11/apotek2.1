<?php
$page_title='Detail Pesanan';
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');
$id=(int)($_GET['id']??0);
$st=$conn->prepare("SELECT * FROM pemesanan WHERE id=? AND pelanggan_id=?");
$st->execute([$id,$_SESSION['user_id']]); $p=$st->fetch();
if(!$p){ die('Pesanan tidak ditemukan.'); }
$d=$conn->prepare("SELECT pd.*, o.nama_obat, o.kode_obat FROM pemesanan_detail pd JOIN obat o ON o.id=pd.obat_id WHERE pemesanan_id=?");
$d->execute([$id]); $items=$d->fetchAll();
?>
<a href="pesanan.php" class="btn btn-link mb-2"><i class="bi bi-arrow-left"></i> Kembali</a>
<h2 class="mb-3">Detail Pesanan <small class="text-muted"><?= e($p['no_pesanan']) ?></small></h2>
<div class="row g-3">
<div class="col-md-8"><div class="card p-3">
<h5>Item Pesanan</h5>
<table class="table table-sm">
<thead><tr><th>Kode</th><th>Nama Obat</th><th>Qty</th><th>Harga</th><th class="text-end">Subtotal</th></tr></thead>
<tbody>
<?php foreach($items as $it): ?>
<tr><td><?= e($it['kode_obat']) ?></td><td><?= e($it['nama_obat']) ?></td>
  <td><?= $it['jumlah'] ?></td><td>Rp <?= number_format($it['harga'],0,',','.') ?></td>
  <td class="text-end">Rp <?= number_format($it['subtotal'],0,',','.') ?></td></tr>
<?php endforeach; ?>
<tr class="table-light"><th colspan="4" class="text-end">Total</th>
  <th class="text-end">Rp <?= number_format($p['total'],0,',','.') ?></th></tr>
</tbody></table>
</div></div>
<div class="col-md-4"><div class="card p-3">
<h5>Info Pengiriman</h5>
<p class="mb-1"><strong>Penerima:</strong> <?= e($p['nama_penerima']) ?></p>
<p class="mb-1"><strong>Telp:</strong> <?= e($p['no_telp']) ?></p>
<p class="mb-1"><strong>Alamat:</strong><br><?= nl2br(e($p['alamat_kirim'])) ?></p>
<?php if($p['catatan']): ?><p class="mb-1"><strong>Catatan:</strong> <?= e($p['catatan']) ?></p><?php endif; ?>
<hr>
<p class="mb-1"><strong>Metode Bayar:</strong> <span class="badge bg-secondary text-uppercase"><?= e($p['metode_bayar']) ?></span></p>
<p class="mb-1"><strong>Status Bayar:</strong> <?= e($p['status_bayar']) ?></p>
<p class="mb-1"><strong>Status Pesanan:</strong> <?= e($p['status_pesanan']) ?></p>
<p class="mb-1"><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($p['tgl_pesanan'])) ?></p>
<?php if($p['status_bayar']!=='dibayar' && $p['status_pesanan']==='diproses'): ?>
<a href="pembayaran.php?id=<?= $p['id'] ?>" class="btn btn-success w-100 mt-2"><i class="bi bi-credit-card"></i> Bayar Sekarang</a>
<?php endif; ?>
</div></div>
</div>
<?php require __DIR__.'/../includes/footer.php'; ?>
