<?php
$page_title='Detail Obat';
require_once __DIR__.'/../includes/header.php';
require_login();
$id=(int)($_GET['id']??0);
$s=$conn->prepare("SELECT o.*, s.nama_supplier FROM obat o LEFT JOIN supplier s ON s.id=o.supplier_id WHERE o.id=?");
$s->execute([$id]); $o=$s->fetch();
if (!$o) die('Obat tidak ditemukan.');
$hist=$conn->prepare("SELECT d.*, p.no_nota, p.tgl_penjualan FROM penjualan_detail d JOIN penjualan p ON p.id=d.penjualan_id WHERE d.obat_id=? ORDER BY p.tgl_penjualan DESC LIMIT 20");
$hist->execute([$id]); $hrows=$hist->fetchAll();
?>
<a href="javascript:history.back()" class="btn btn-link"><i class="bi bi-arrow-left"></i> Kembali</a>
<div class="card p-4 mb-3">
<div class="row">
<div class="col-md-8">
<h2><?= e($o['nama_obat']) ?></h2>
<p class="text-muted">Kode: <?= e($o['kode_obat']) ?> • Kategori: <?= e($o['kategori']) ?></p>
<p><?= nl2br(e($o['deskripsi'])) ?></p>
</div>
<div class="col-md-4">
<table class="table table-sm">
<tr><th>Harga Jual</th><td>Rp <?= number_format($o['harga_jual'],0,',','.') ?></td></tr>
<tr><th>Stok</th><td><?= $o['stok'] ?> <?= e($o['satuan']) ?></td></tr>
<tr><th>Kadaluarsa</th><td><?= e($o['tgl_kadaluarsa']) ?></td></tr>
<tr><th>Supplier</th><td><?= e($o['nama_supplier']) ?></td></tr>
<tr><th>Status</th><td><span class="badge bg-success"><?= e($o['status']) ?></span></td></tr>
</table>
</div></div></div>
<div class="card p-3"><h5>Histori Penjualan (20 terakhir)</h5>
<table class="table"><thead><tr><th>No Nota</th><th>Tanggal</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>
<?php foreach($hrows as $h):?>
<tr><td><a href="penjualan_detail.php?id=<?= $h['penjualan_id'] ?>"><?= e($h['no_nota']) ?></a></td>
<td><?= e($h['tgl_penjualan']) ?></td><td><?= $h['jumlah'] ?></td>
<td>Rp <?= number_format($h['subtotal'],0,',','.') ?></td></tr>
<?php endforeach; if(!$hrows):?><tr><td colspan="4" class="text-center text-muted">Belum ada penjualan.</td></tr><?php endif;?>
</tbody></table></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
