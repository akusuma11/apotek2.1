<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/db.php';
require_login();
$role = $_SESSION['role'];
$nama = $_SESSION['nama'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= isset($page_title)? e($page_title).' | ' : '' ?>Apotek Sehat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-success sticky-top shadow-sm">
  <div class="container-fluid">
    <span class="navbar-brand mb-0 h1"><i class="bi bi-capsule-pill"></i> Apotek Sehat</span>
    <div class="d-flex align-items-center gap-3 text-white">
      <span class="badge bg-light text-success text-capitalize"><?= e($role) ?></span>
      <span><i class="bi bi-person-circle"></i> <?= e($nama) ?></span>
      <a class="btn btn-sm btn-outline-light" href="<?= base_url('logout.php') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
  </div>
</nav>
<div class="container-fluid">
  <div class="row">
    <aside class="col-md-2 sidebar bg-light min-vh-100 p-0">
      <ul class="nav flex-column p-2">
        <?php if ($role==='admin'): ?>
          <li><a class="nav-link" href="<?= base_url('admin/dashboard.php') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/apoteker.php') ?>"><i class="bi bi-person-badge"></i> Data Apoteker</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/obat.php') ?>"><i class="bi bi-capsule"></i> Data Obat</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/supplier.php') ?>"><i class="bi bi-truck"></i> Data Supplier</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/pelanggan.php') ?>"><i class="bi bi-people"></i> Data Pelanggan</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/pembelian.php') ?>"><i class="bi bi-cart-plus"></i> Pembelian Obat</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/pemesanan.php') ?>"><i class="bi bi-bag-check"></i> Pesanan Pelanggan</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/kadaluarsa.php') ?>"><i class="bi bi-exclamation-triangle"></i> Obat Kadaluarsa</a></li>
          <li><a class="nav-link" href="<?= base_url('admin/report.php') ?>"><i class="bi bi-graph-up"></i> Report Penjualan</a></li>
        <?php elseif ($role==='apoteker'): ?>
          <li><a class="nav-link" href="<?= base_url('apoteker/dashboard.php') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
          <li><a class="nav-link" href="<?= base_url('apoteker/obat.php') ?>"><i class="bi bi-capsule"></i> Data Obat</a></li>
          <li><a class="nav-link" href="<?= base_url('apoteker/penjualan.php') ?>"><i class="bi bi-receipt"></i> Penjualan</a></li>
          <li><a class="nav-link" href="<?= base_url('apoteker/kadaluarsa.php') ?>"><i class="bi bi-trash"></i> Hapus Obat Kadaluarsa</a></li>
        <?php else: ?>
          <li><a class="nav-link" href="<?= base_url('pelanggan/dashboard.php') ?>"><i class="bi bi-house"></i> Beranda</a></li>
          <li><a class="nav-link" href="<?= base_url('pelanggan/obat.php') ?>"><i class="bi bi-capsule"></i> Daftar Obat</a></li>
          <li><a class="nav-link" href="<?= base_url('pelanggan/keranjang.php') ?>"><i class="bi bi-cart3"></i> Keranjang
            <?php $cc=array_sum(array_column($_SESSION['cart']??[],'qty')); if($cc>0): ?><span class="badge bg-danger"><?= $cc ?></span><?php endif; ?>
          </a></li>
          <li><a class="nav-link" href="<?= base_url('pelanggan/pesanan.php') ?>"><i class="bi bi-bag-check"></i> Pesanan Saya</a></li>
        <?php endif; ?>
      </ul>
    </aside>
    <main class="col-md-10 py-4 px-4">
      <?php if($msg=flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
      <?php if($msg=flash('error')): ?><div class="alert alert-danger"><?= e($msg) ?></div><?php endif; ?>
