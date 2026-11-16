<?php
$page_title='Checkout';
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');

$cart = $_SESSION['cart'] ?? [];
if (!$cart) { flash('error','Keranjang kosong.'); header('Location: keranjang.php'); exit; }

// ambil profil pelanggan
$st = $conn->prepare("SELECT nama_lengkap, email, no_telp, alamat FROM users WHERE id=?");
$st->execute([$_SESSION['user_id']]); $u = $st->fetch();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $nama   = trim($_POST['nama_penerima'] ?? '');
    $telp   = trim($_POST['no_telp'] ?? '');
    $alamat = trim($_POST['alamat_kirim'] ?? '');
    $catatan= trim($_POST['catatan'] ?? '');
    $bayar  = $_POST['metode_bayar'] ?? '';
    $errors = [];
    if ($nama==='') $errors[]='Nama penerima wajib diisi.';
    if ($telp==='' || !preg_match('/^[0-9+\-\s]{8,20}$/',$telp)) $errors[]='No. telepon tidak valid.';
    if ($alamat==='') $errors[]='Alamat pengiriman wajib diisi.';
    if (!in_array($bayar,['qris','cod'],true)) $errors[]='Pilih metode pembayaran.';
    if ($errors){ flash('error', implode(' ', $errors)); }
    else {
        try {
            $conn->beginTransaction();
            // re-validate stok
            $total = 0; $items=[];
            foreach($cart as $c){
                $s=$conn->prepare("SELECT stok, harga_jual, nama_obat FROM obat WHERE id=? FOR UPDATE");
                $s->execute([$c['obat_id']]); $o=$s->fetch();
                if(!$o || $o['stok'] < $c['qty']) throw new Exception("Stok '{$c['nama_obat']}' tidak mencukupi.");
                $sub = $o['harga_jual']*$c['qty'];
                $total += $sub;
                $items[]=['id'=>$c['obat_id'],'qty'=>$c['qty'],'harga'=>$o['harga_jual'],'sub'=>$sub];
            }
            $no = 'PSN'.date('YmdHis').rand(10,99);
            $status_bayar = ($bayar==='cod') ? 'menunggu' : 'menunggu';
            $ins = $conn->prepare("INSERT INTO pemesanan (no_pesanan, pelanggan_id, tgl_pesanan, nama_penerima, no_telp, alamat_kirim, catatan, metode_bayar, status_bayar, status_pesanan, total) VALUES (?,?,NOW(),?,?,?,?,?,?, 'diproses', ?)");
            $ins->execute([$no,$_SESSION['user_id'],$nama,$telp,$alamat,$catatan,$bayar,$status_bayar,$total]);
            $pid = $conn->lastInsertId();
            $insd = $conn->prepare("INSERT INTO pemesanan_detail (pemesanan_id, obat_id, jumlah, harga, subtotal) VALUES (?,?,?,?,?)");
            $upd  = $conn->prepare("UPDATE obat SET stok=stok-? WHERE id=?");
            foreach($items as $it){
                $insd->execute([$pid,$it['id'],$it['qty'],$it['harga'],$it['sub']]);
                $upd->execute([$it['qty'],$it['id']]);
            }
            $conn->commit();
            $_SESSION['cart']=[];
            header('Location: pembayaran.php?id='.$pid); exit;
        } catch (Exception $e) {
            $conn->rollBack();
            flash('error','Gagal memproses pesanan: '.$e->getMessage());
        }
    }
}
$total = 0; foreach($cart as $c) $total += $c['harga']*$c['qty'];
?>
<h2 class="mb-3"><i class="bi bi-credit-card"></i> Checkout</h2>
<form method="post" class="row g-3">
  <div class="col-md-7">
    <div class="card p-3 mb-3">
      <h5>Data Pengiriman</h5>
      <div class="mb-2"><label class="form-label">Nama Penerima *</label>
        <input name="nama_penerima" class="form-control" required value="<?= e($_POST['nama_penerima'] ?? $u['nama_lengkap'] ?? '') ?>"></div>
      <div class="mb-2"><label class="form-label">No. Telepon *</label>
        <input name="no_telp" class="form-control" required value="<?= e($_POST['no_telp'] ?? $u['no_telp'] ?? '') ?>"></div>
      <div class="mb-2"><label class="form-label">Alamat Pengiriman *</label>
        <textarea name="alamat_kirim" rows="3" class="form-control" required><?= e($_POST['alamat_kirim'] ?? $u['alamat'] ?? '') ?></textarea></div>
      <div class="mb-2"><label class="form-label">Catatan (opsional)</label>
        <textarea name="catatan" rows="2" class="form-control"><?= e($_POST['catatan'] ?? '') ?></textarea></div>
    </div>
    <div class="card p-3">
      <h5>Metode Pembayaran</h5>
      <div class="form-check p-3 border rounded mb-2">
        <input class="form-check-input" type="radio" name="metode_bayar" id="bm1" value="qris" <?= (($_POST['metode_bayar']??'')==='qris'?'checked':'') ?>>
        <label class="form-check-label w-100" for="bm1">
          <strong><i class="bi bi-qr-code"></i> QRIS</strong> — Bayar dengan scan kode QRIS dari aplikasi e-wallet/m-banking Anda.
        </label>
      </div>
      <div class="form-check p-3 border rounded">
        <input class="form-check-input" type="radio" name="metode_bayar" id="bm2" value="cod" <?= (($_POST['metode_bayar']??'')==='cod'?'checked':'') ?>>
        <label class="form-check-label w-100" for="bm2">
          <strong><i class="bi bi-cash-coin"></i> Bayar di Tempat (COD)</strong> — Bayar tunai saat obat diserahkan di apotek.
        </label>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card p-3 sticky-top" style="top:80px">
      <h5>Ringkasan Pesanan</h5>
      <ul class="list-group list-group-flush mb-2">
      <?php foreach($cart as $c): ?>
        <li class="list-group-item d-flex justify-content-between px-0">
          <span><?= e($c['nama_obat']) ?> <small class="text-muted">x<?= $c['qty'] ?></small></span>
          <span>Rp <?= number_format($c['harga']*$c['qty'],0,',','.') ?></span>
        </li>
      <?php endforeach; ?>
      </ul>
      <div class="d-flex justify-content-between border-top pt-2">
        <strong>Total</strong>
        <h5 class="text-success mb-0">Rp <?= number_format($total,0,',','.') ?></h5>
      </div>
      <button class="btn btn-success btn-lg mt-3 w-100">Buat Pesanan <i class="bi bi-check2-circle"></i></button>
      <a href="keranjang.php" class="btn btn-link w-100">Kembali ke Keranjang</a>
    </div>
  </div>
</form>
<?php require __DIR__.'/../includes/footer.php'; ?>
