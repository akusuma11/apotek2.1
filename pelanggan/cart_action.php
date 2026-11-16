<?php
require_once __DIR__.'/../includes/header.php';
require_role('pelanggan');

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

if ($action === 'add' && $id) {
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    $st = $conn->prepare("SELECT id, nama_obat, harga_jual, stok FROM obat WHERE id=? AND status='tersedia'");
    $st->execute([$id]); $o = $st->fetch();
    if (!$o) { flash('error','Obat tidak tersedia.'); header('Location: obat.php'); exit; }
    $cur = $_SESSION['cart'][$id]['qty'] ?? 0;
    $new = min($o['stok'], $cur + $qty);
    if ($new <= 0) { flash('error','Stok habis.'); header('Location: obat.php'); exit; }
    $_SESSION['cart'][$id] = [
        'obat_id' => (int)$o['id'],
        'nama_obat' => $o['nama_obat'],
        'harga' => (float)$o['harga_jual'],
        'qty' => $new,
        'stok' => (int)$o['stok'],
    ];
    flash('success','Obat ditambahkan ke keranjang.');
    header('Location: keranjang.php'); exit;
}
if ($action === 'update' && $id) {
    $qty = max(0, (int)($_POST['qty'] ?? 1));
    if (isset($_SESSION['cart'][$id])) {
        if ($qty === 0) unset($_SESSION['cart'][$id]);
        else {
            $stok = $_SESSION['cart'][$id]['stok'];
            $_SESSION['cart'][$id]['qty'] = min($qty, $stok);
        }
    }
    header('Location: keranjang.php'); exit;
}
if ($action === 'remove' && $id) {
    unset($_SESSION['cart'][$id]);
    flash('success','Item dihapus dari keranjang.');
    header('Location: keranjang.php'); exit;
}
if ($action === 'clear') {
    $_SESSION['cart'] = [];
    header('Location: keranjang.php'); exit;
}
header('Location: keranjang.php');
