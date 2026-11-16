<?php
$page_title='Keranjang';
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');
$cart = $_SESSION['cart'] ?? [];
$total = 0; foreach($cart as $c) $total += $c['harga']*$c['qty'];
?>
<h2 class="mb-3"><i class="bi bi-cart3"></i> Keranjang Belanja</h2>
<?php if(!$cart): ?>
<div class="card p-5 text-center text-muted">
  <i class="bi bi-cart-x" style="font-size:3rem"></i>
  <p class="mt-3">Keranjang Anda masih kosong.</p>
  <a href="obat.php" class="btn btn-success">Lihat Katalog Obat</a>
</div>
<?php else: ?>
<div class="card">
<table class="table align-middle mb-0">
<thead class="table-light"><tr><th>Obat</th><th>Harga</th><th width="180">Jumlah</th><th>Subtotal</th><th></th></tr></thead>
<tbody>
<?php foreach($cart as $id=>$c): ?>
<tr>
  <td><?= e($c['nama_obat']) ?><br><small class="text-muted">Stok tersedia: <?= $c['stok'] ?></small></td>
  <td>Rp <?= number_format($c['harga'],0,',','.') ?></td>
  <td>
    <form method="post" action="cart_action.php" class="d-flex gap-1">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= $id ?>">
      <input type="number" name="qty" value="<?= $c['qty'] ?>" min="0" max="<?= $c['stok'] ?>" class="form-control form-control-sm">
      <button class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-clockwise"></i></button>
    </form>
  </td>
  <td><strong>Rp <?= number_format($c['harga']*$c['qty'],0,',','.') ?></strong></td>
  <td><a href="cart_action.php?action=remove&id=<?= $id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus item?')"><i class="bi bi-trash"></i></a></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot class="table-light">
<tr><th colspan="3" class="text-end">Total</th><th colspan="2"><h5 class="text-success mb-0">Rp <?= number_format($total,0,',','.') ?></h5></th></tr>
</tfoot>
</table>
</div>
<div class="d-flex justify-content-between mt-3">
  <div>
    <a href="obat.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Lanjut Belanja</a>
    <a href="cart_action.php?action=clear" class="btn btn-outline-danger" onclick="return confirm('Kosongkan keranjang?')">Kosongkan</a>
  </div>
  <a href="checkout.php" class="btn btn-success btn-lg">Checkout <i class="bi bi-arrow-right"></i></a>
</div>
<?php endif; ?>
<?php require __DIR__.'/../includes/footer.php'; ?>
