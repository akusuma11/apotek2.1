<?php
$page_title='Pesanan Saya';
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');

// Batalkan pesanan (hanya jika belum dibayar / belum diproses selesai)
if (($_GET['action']??'')==='cancel') {
    $id=(int)$_GET['id'];
    $st=$conn->prepare("SELECT * FROM pemesanan WHERE id=? AND pelanggan_id=?");
    $st->execute([$id,$_SESSION['user_id']]); $p=$st->fetch();
    if($p && $p['status_pesanan']==='diproses' && $p['status_bayar']!=='dibayar'){
        try{
            $conn->beginTransaction();
            $d=$conn->prepare("SELECT obat_id,jumlah FROM pemesanan_detail WHERE pemesanan_id=?");
            $d->execute([$id]);
            $upd=$conn->prepare("UPDATE obat SET stok=stok+? WHERE id=?");
            foreach($d->fetchAll() as $it){ $upd->execute([$it['jumlah'],$it['obat_id']]); }
            $conn->prepare("UPDATE pemesanan SET status_pesanan='dibatalkan' WHERE id=?")->execute([$id]);
            $conn->commit();
            flash('success','Pesanan dibatalkan, stok dikembalikan.');
        }catch(Exception $e){ $conn->rollBack(); flash('error','Gagal: '.$e->getMessage()); }
    } else { flash('error','Pesanan tidak dapat dibatalkan.'); }
    header('Location: pesanan.php'); exit;
}

$rows=$conn->prepare("SELECT * FROM pemesanan WHERE pelanggan_id=? ORDER BY id DESC");
$rows->execute([$_SESSION['user_id']]); $list=$rows->fetchAll();
?>
<h2 class="mb-3"><i class="bi bi-bag-check"></i> Pesanan Saya</h2>
<div class="card">
<table class="table align-middle mb-0">
<thead class="table-light"><tr><th>No. Pesanan</th><th>Tanggal</th><th>Total</th><th>Metode</th><th>Status Bayar</th><th>Status Pesanan</th><th></th></tr></thead>
<tbody>
<?php foreach($list as $r): ?>
<tr>
  <td><strong><?= e($r['no_pesanan']) ?></strong></td>
  <td><?= date('d/m/Y H:i', strtotime($r['tgl_pesanan'])) ?></td>
  <td>Rp <?= number_format($r['total'],0,',','.') ?></td>
  <td><span class="badge bg-secondary text-uppercase"><?= e($r['metode_bayar']) ?></span></td>
  <td><?php $sb=$r['status_bayar']; $cls=['menunggu'=>'warning text-dark','dibayar'=>'success','gagal'=>'danger'][$sb]??'secondary'; ?>
    <span class="badge bg-<?= $cls ?>"><?= e($sb) ?></span></td>
  <td><?php $sp=$r['status_pesanan']; $cls2=['diproses'=>'info','selesai'=>'success','dibatalkan'=>'danger'][$sp]??'secondary'; ?>
    <span class="badge bg-<?= $cls2 ?>"><?= e($sp) ?></span></td>
  <td class="text-end">
    <a href="pesanan_detail.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
    <?php if($r['status_bayar']!=='dibayar' && $r['status_pesanan']==='diproses'): ?>
      <a href="pembayaran.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-success"><i class="bi bi-credit-card"></i> Bayar</a>
      <a href="?action=cancel&id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Batalkan pesanan?')"><i class="bi bi-x-circle"></i></a>
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; if(!$list): ?>
<tr><td colspan="7" class="text-center text-muted py-4">Belum ada pesanan. <a href="obat.php">Belanja sekarang</a>.</td></tr>
<?php endif; ?>
</tbody></table></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
