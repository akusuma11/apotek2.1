<?php
$page_title='Detail Penjualan';
require_once __DIR__.'/../includes/header.php';
require_login();
$id=(int)($_GET['id']??0);
$s=$conn->prepare("SELECT p.*, u.nama_lengkap apoteker, up.nama_lengkap pelanggan
  FROM penjualan p LEFT JOIN users u ON u.id=p.apoteker_id LEFT JOIN users up ON up.id=p.pelanggan_id WHERE p.id=?");
$s->execute([$id]); $p=$s->fetch();
if (!$p) die('Penjualan tidak ditemukan.');
$d=$conn->prepare("SELECT d.*, o.nama_obat, o.kode_obat FROM penjualan_detail d JOIN obat o ON o.id=d.obat_id WHERE d.penjualan_id=?");
$d->execute([$id]); $items=$d->fetchAll();
?>
<a href="javascript:history.back()" class="btn btn-link"><i class="bi bi-arrow-left"></i> Kembali</a>
<div class="card p-4">
<div class="d-flex justify-content-between">
<div>
<h2>Nota <?= e($p['no_nota']) ?></h2>
<p class="mb-1"><b>Tanggal:</b> <?= e($p['tgl_penjualan']) ?></p>
<p class="mb-1"><b>Apoteker:</b> <?= e($p['apoteker']) ?></p>
<p><b>Pelanggan:</b> <?= e($p['pelanggan'] ?: 'Umum') ?></p>
</div>
<div class="text-end">
<h3 class="text-success">Rp <?= number_format($p['total'],0,',','.') ?></h3>
<button class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
</div></div>
<hr>
<table class="table">
<thead><tr><th>Kode</th><th>Nama Obat</th><th>Harga</th><th>Qty</th><th class="text-end">Subtotal</th></tr></thead>
<tbody>
<?php foreach($items as $it):?>
<tr><td><?= e($it['kode_obat']) ?></td><td><?= e($it['nama_obat']) ?></td>
<td>Rp <?= number_format($it['harga'],0,',','.') ?></td>
<td><?= $it['jumlah'] ?></td>
<td class="text-end">Rp <?= number_format($it['subtotal'],0,',','.') ?></td></tr>
<?php endforeach;?>
<tr><th colspan="4" class="text-end">TOTAL</th><th class="text-end">Rp <?= number_format($p['total'],0,',','.') ?></th></tr>
</tbody></table>
</div>
<?php require __DIR__.'/../includes/footer.php'; ?>
