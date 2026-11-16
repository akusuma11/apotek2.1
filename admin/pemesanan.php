<?php
$page_title='Pesanan Pelanggan';
require_once __DIR__.'/../includes/header.php';
require_role('admin');

// update status by admin
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='update_status') {
    $id=(int)$_POST['id'];
    $sb=$_POST['status_bayar'] ?? '';
    $sp=$_POST['status_pesanan'] ?? '';
    if(in_array($sb,['menunggu','dibayar','gagal'],true) && in_array($sp,['diproses','selesai','dibatalkan'],true)){
        $conn->prepare("UPDATE pemesanan SET status_bayar=?, status_pesanan=? WHERE id=?")->execute([$sb,$sp,$id]);
        flash('success','Status pesanan diperbarui.');
    }
    header('Location: pemesanan.php'); exit;
}

$rows=$conn->query("SELECT p.*, u.nama_lengkap, u.username FROM pemesanan p LEFT JOIN users u ON u.id=p.pelanggan_id ORDER BY p.id DESC")->fetchAll();
?>
<h2 class="mb-3"><i class="bi bi-bag-check"></i> Pesanan Pelanggan</h2>
<div class="card"><table class="table align-middle mb-0">
<thead class="table-light"><tr><th>No</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Metode</th><th>Bayar</th><th>Pesanan</th><th>Aksi</th></tr></thead>
<tbody>
<?php foreach($rows as $r): ?>
<tr>
  <td><strong><?= e($r['no_pesanan']) ?></strong></td>
  <td><?= e($r['nama_lengkap'] ?? '-') ?><br><small class="text-muted"><?= e($r['username'] ?? '') ?></small></td>
  <td><?= date('d/m/Y H:i', strtotime($r['tgl_pesanan'])) ?></td>
  <td>Rp <?= number_format($r['total'],0,',','.') ?></td>
  <td><span class="badge bg-secondary text-uppercase"><?= e($r['metode_bayar']) ?></span></td>
  <td><span class="badge bg-<?= ['menunggu'=>'warning text-dark','dibayar'=>'success','gagal'=>'danger'][$r['status_bayar']]??'secondary' ?>"><?= e($r['status_bayar']) ?></span></td>
  <td><span class="badge bg-<?= ['diproses'=>'info','selesai'=>'success','dibatalkan'=>'danger'][$r['status_pesanan']]??'secondary' ?>"><?= e($r['status_pesanan']) ?></span></td>
  <td>
    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#m<?= $r['id'] ?>"><i class="bi bi-pencil"></i> Ubah</button>
    <div class="modal fade text-start" id="m<?= $r['id'] ?>"><div class="modal-dialog"><div class="modal-content">
      <form method="post">
        <div class="modal-header"><h5 class="modal-title">Update Pesanan <?= e($r['no_pesanan']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <input type="hidden" name="action" value="update_status">
          <input type="hidden" name="id" value="<?= $r['id'] ?>">
          <p><strong>Penerima:</strong> <?= e($r['nama_penerima']) ?> (<?= e($r['no_telp']) ?>)<br>
            <strong>Alamat:</strong> <?= e($r['alamat_kirim']) ?></p>
          <div class="mb-2"><label class="form-label">Status Bayar</label>
            <select name="status_bayar" class="form-select">
              <?php foreach(['menunggu','dibayar','gagal'] as $o): ?>
              <option <?= $r['status_bayar']==$o?'selected':'' ?>><?= $o ?></option>
              <?php endforeach; ?>
            </select></div>
          <div class="mb-2"><label class="form-label">Status Pesanan</label>
            <select name="status_pesanan" class="form-select">
              <?php foreach(['diproses','selesai','dibatalkan'] as $o): ?>
              <option <?= $r['status_pesanan']==$o?'selected':'' ?>><?= $o ?></option>
              <?php endforeach; ?>
            </select></div>
        </div>
        <div class="modal-footer"><button class="btn btn-success">Simpan</button></div>
      </form>
    </div></div></div>
  </td>
</tr>
<?php endforeach; if(!$rows): ?><tr><td colspan="8" class="text-center text-muted py-4">Belum ada pesanan.</td></tr><?php endif; ?>
</tbody></table></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
