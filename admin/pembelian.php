<?php
$page_title='Pembelian Obat';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
$rows=$conn->query("SELECT p.*, s.nama_supplier, (SELECT COUNT(*) FROM pembelian_detail d WHERE d.pembelian_id=p.id) AS jml_item
  FROM pembelian p LEFT JOIN supplier s ON s.id=p.supplier_id ORDER BY p.tgl_pembelian DESC")->fetchAll();
?>
<h2>Daftar Pembelian Obat</h2>
<div class="card p-3"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>No Faktur</th><th>Tanggal</th><th>Supplier</th><th>Item</th><th>Total</th></tr></thead><tbody>
<?php foreach($rows as $r):?>
<tr><td><?= e($r['no_faktur']) ?></td><td><?= e($r['tgl_pembelian']) ?></td>
<td><?= e($r['nama_supplier']) ?></td><td><?= $r['jml_item'] ?></td>
<td>Rp <?= number_format($r['total'],0,',','.') ?></td></tr>
<?php endforeach; if(!$rows):?><tr><td colspan="5" class="text-center text-muted">Belum ada pembelian.</td></tr><?php endif;?>
</tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
