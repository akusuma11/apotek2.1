<?php
$page_title='Penjualan';
require_once __DIR__.'/../includes/header.php';
require_role('apoteker');
$rows=$conn->query("SELECT p.*, u.nama_lengkap pel,
  (SELECT COUNT(*) FROM penjualan_detail d WHERE d.penjualan_id=p.id) jml
  FROM penjualan p LEFT JOIN users u ON u.id=p.pelanggan_id
  ORDER BY p.tgl_penjualan DESC")->fetchAll();
?>
<div class="d-flex justify-content-between mb-3"><h2>Histori Penjualan</h2>
<a href="penjualan_form.php" class="btn btn-success"><i class="bi bi-plus-lg"></i> Transaksi Baru</a></div>
<div class="card p-3"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>No Nota</th><th>Tanggal</th><th>Pelanggan</th><th>Item</th><th>Total</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r):?>
<tr><td><a href="penjualan_detail.php?id=<?= $r['id'] ?>"><?= e($r['no_nota']) ?></a></td>
<td><?= e($r['tgl_penjualan']) ?></td><td><?= e($r['pel'] ?: '-') ?></td>
<td><?= $r['jml'] ?></td><td>Rp <?= number_format($r['total'],0,',','.') ?></td>
<td><a class="btn btn-sm btn-info" href="penjualan_detail.php?id=<?= $r['id'] ?>"><i class="bi bi-eye"></i></a></td></tr>
<?php endforeach; if(!$rows):?><tr><td colspan="6" class="text-center text-muted">Belum ada penjualan.</td></tr><?php endif;?>
</tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
