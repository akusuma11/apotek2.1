<?php
$page_title='Pembayaran';
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');
$id=(int)($_GET['id']??0);
$st=$conn->prepare("SELECT * FROM pemesanan WHERE id=? AND pelanggan_id=?");
$st->execute([$id,$_SESSION['user_id']]); $p=$st->fetch();
if(!$p){ die('Pesanan tidak ditemukan.'); }

// Tandai pembayaran QRIS sudah dibayar (simulasi)
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['confirm']??'')==='1' && $p['metode_bayar']==='qris') {
    $conn->prepare("UPDATE pemesanan SET status_bayar='dibayar' WHERE id=?")->execute([$id]);
    flash('success','Pembayaran QRIS dikonfirmasi. Pesanan akan segera diproses.');
    header('Location: pesanan_detail.php?id='.$id); exit;
}

$d=$conn->prepare("SELECT pd.*, o.nama_obat FROM pemesanan_detail pd JOIN obat o ON o.id=pd.obat_id WHERE pemesanan_id=?");
$d->execute([$id]); $items=$d->fetchAll();
$qrPayload = 'APOTEKSEHAT|'.$p['no_pesanan'].'|'.number_format($p['total'],0,'','');
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($qrPayload);
?>
<h2 class="mb-3"><i class="bi bi-credit-card-2-front"></i> Pembayaran Pesanan</h2>
<div class="alert alert-info">No. Pesanan: <strong><?= e($p['no_pesanan']) ?></strong> &middot; Total: <strong>Rp <?= number_format($p['total'],0,',','.') ?></strong></div>

<div class="row g-3">
  <div class="col-md-7">
  <?php if ($p['metode_bayar']==='qris'): ?>
    <div class="card p-4 text-center">
      <h4><i class="bi bi-qr-code"></i> Scan QRIS</h4>
      <p class="text-muted">Scan kode di bawah ini dari aplikasi e-wallet / m-banking Anda. Setelah pembayaran berhasil, klik tombol konfirmasi.</p>
      <img src="<?= e($qrUrl) ?>" alt="QRIS" class="mx-auto my-3" style="max-width:260px">
      <p class="text-muted small">Kode: <?= e($qrPayload) ?></p>
      <?php if ($p['status_bayar']!=='dibayar'): ?>
      <form method="post"><input type="hidden" name="confirm" value="1">
        <button class="btn btn-success btn-lg"><i class="bi bi-check2-circle"></i> Saya Sudah Bayar</button>
      </form>
      <?php else: ?>
        <div class="alert alert-success mt-3"><i class="bi bi-check-circle"></i> Pembayaran sudah dikonfirmasi.</div>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <div class="card p-4 text-center">
      <h4><i class="bi bi-cash-coin"></i> Bayar di Tempat (COD)</h4>
      <p>Silakan datang ke apotek untuk mengambil dan membayar pesanan Anda.</p>
      <p class="text-muted">Tunjukkan nomor pesanan <strong><?= e($p['no_pesanan']) ?></strong> kepada petugas.</p>
      <span class="badge bg-warning text-dark fs-6">Status: Menunggu pembayaran di apotek</span>
    </div>
  <?php endif; ?>
  </div>
  <div class="col-md-5">
    <div class="card p-3">
      <h5>Detail Pesanan</h5>
      <table class="table table-sm">
        <?php foreach($items as $it): ?>
        <tr><td><?= e($it['nama_obat']) ?> x<?= $it['jumlah'] ?></td>
          <td class="text-end">Rp <?= number_format($it['subtotal'],0,',','.') ?></td></tr>
        <?php endforeach; ?>
        <tr class="table-light"><th>Total</th><th class="text-end">Rp <?= number_format($p['total'],0,',','.') ?></th></tr>
      </table>
      <hr>
      <p class="mb-1"><strong>Penerima:</strong> <?= e($p['nama_penerima']) ?></p>
      <p class="mb-1"><strong>Telp:</strong> <?= e($p['no_telp']) ?></p>
      <p class="mb-1"><strong>Alamat:</strong> <?= e($p['alamat_kirim']) ?></p>
      <a href="pesanan.php" class="btn btn-outline-secondary w-100 mt-2">Lihat Semua Pesanan</a>
    </div>
  </div>
</div>
<?php require __DIR__.'/../includes/footer.php'; ?>
