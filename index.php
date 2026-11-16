<?php
require_once __DIR__.'/includes/auth.php';
if (!empty($_SESSION['user_id'])) {
    $r = $_SESSION['role'];
    header('Location: '.base_url("$r/dashboard.php")); exit;
}
$base = base_url('');
// Gallery images from Unsplash (free to hotlink)
$gallery = [
  ['https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=800','Apotek modern'],
  ['https://images.unsplash.com/photo-1576602976047-174e57a47881?w=800','Rak obat'],
  ['https://images.unsplash.com/photo-1585435557343-3b092031a831?w=800','Konsultasi apoteker'],
  ['https://images.unsplash.com/photo-1579165466741-7f35e4755660?w=800','Obat tablet'],
  ['https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=800','Pelanggan apotek'],
  ['https://images.unsplash.com/photo-1583912267550-d6c2ac3196c0?w=800','Resep dokter'],
  ['https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=800','Vitamin'],
  ['https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=800','Display obat'],
  ['https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800','Stok obat'],
  ['https://images.unsplash.com/photo-1550572017-edd951b55104?w=800','Transaksi'],
  ['https://images.unsplash.com/photo-1563213126-a4273aed2016?w=800','Sirup obat'],
  ['https://images.unsplash.com/photo-1559757175-5700dde675bc?w=800','Apoteker bekerja'],
];
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Apotek Sehat - Solusi Kesehatan Keluarga Anda</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{--brand:#0d9488;--brand2:#0891b2;--dark:#0f172a;}
  *{font-family:'Plus Jakarta Sans',sans-serif;}
  body{color:var(--dark);}
  .navbar-brand{font-weight:800;color:var(--brand)!important;font-size:1.4rem;}
  .nav-link{font-weight:600;color:var(--dark)!important;}
  .btn-brand{background:linear-gradient(135deg,var(--brand),var(--brand2));color:#fff;border:0;font-weight:600;padding:.6rem 1.4rem;border-radius:50px;}
  .btn-brand:hover{color:#fff;opacity:.92;}
  .hero{background:linear-gradient(135deg,#ecfeff 0%,#f0fdfa 100%);padding:80px 0;position:relative;overflow:hidden;}
  .hero h1{font-size:3.2rem;font-weight:800;line-height:1.1;}
  .hero .lead{font-size:1.15rem;color:#475569;}
  .hero-img{border-radius:24px;box-shadow:0 30px 80px -20px rgba(13,148,136,.4);}
  .badge-pill{background:#fff;border:1px solid #cbd5e1;color:var(--brand);font-weight:600;padding:.5rem 1rem;border-radius:50px;display:inline-block;margin-bottom:1rem;}
  .stat-card{background:#fff;border-radius:20px;padding:24px;border:1px solid #e2e8f0;text-align:center;}
  .stat-card .num{font-size:2.2rem;font-weight:800;color:var(--brand);}
  .section{padding:80px 0;}
  .section-title{font-size:2.2rem;font-weight:800;text-align:center;margin-bottom:.5rem;}
  .section-sub{text-align:center;color:#64748b;margin-bottom:50px;}
  .feature-card{background:#fff;border-radius:20px;padding:32px;border:1px solid #e2e8f0;height:100%;transition:.3s;}
  .feature-card:hover{transform:translateY(-6px);box-shadow:0 20px 50px -20px rgba(13,148,136,.3);border-color:var(--brand);}
  .feature-ico{width:60px;height:60px;border-radius:16px;background:linear-gradient(135deg,#ccfbf1,#a5f3fc);color:var(--brand);display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin-bottom:1rem;}
  .gallery{column-count:4;column-gap:16px;}
  @media(max-width:992px){.gallery{column-count:2;}}
  @media(max-width:576px){.gallery{column-count:1;}.hero h1{font-size:2.2rem;}}
  .gallery .item{break-inside:avoid;margin-bottom:16px;border-radius:16px;overflow:hidden;position:relative;cursor:pointer;}
  .gallery .item img{width:100%;display:block;transition:.5s;}
  .gallery .item:hover img{transform:scale(1.08);}
  .gallery .cap{position:absolute;bottom:0;left:0;right:0;padding:12px;background:linear-gradient(transparent,rgba(0,0,0,.7));color:#fff;font-weight:600;opacity:0;transition:.3s;}
  .gallery .item:hover .cap{opacity:1;}
  .cta-section{background:linear-gradient(135deg,var(--brand),var(--brand2));color:#fff;border-radius:32px;padding:60px;text-align:center;}
  .cta-section h2{font-weight:800;font-size:2.4rem;}
  .testi{background:#fff;border-radius:20px;padding:28px;border:1px solid #e2e8f0;height:100%;}
  .testi .stars{color:#fbbf24;}
  footer{background:var(--dark);color:#cbd5e1;padding:50px 0 20px;}
  footer h6{color:#fff;font-weight:700;}
  footer a{color:#cbd5e1;text-decoration:none;}
  footer a:hover{color:#fff;}
  .floating{animation:float 6s ease-in-out infinite;}
  @keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-15px);}}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-3">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="bi bi-prescription2"></i> Apotek Sehat</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
        <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimoni">Testimoni</a></li>
        <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
      </ul>
      <div class="d-flex gap-2">
        <a href="<?= base_url('login.php') ?>" class="btn btn-outline-dark rounded-pill">Login</a>
        <a href="<?= base_url('signup.php') ?>" class="btn btn-brand">Daftar Gratis</a>
      </div>
    </div>
  </div>
</nav>

<section class="hero" id="home">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <span class="badge-pill"><i class="bi bi-shield-check"></i> Apotek Terpercaya Sejak 2010</span>
        <h1>Kesehatan Keluarga Anda, <span style="color:var(--brand)">Prioritas Kami</span></h1>
        <p class="lead mt-3">Belanja obat resep, vitamin, dan alat kesehatan dengan mudah. Konsultasi langsung dengan apoteker berlisensi. Pengiriman cepat, pembayaran fleksibel (QRIS / COD).</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="<?= base_url('signup.php') ?>" class="btn btn-brand btn-lg"><i class="bi bi-bag-heart"></i> Mulai Belanja</a>
          <a href="<?= base_url('login.php') ?>" class="btn btn-outline-dark btn-lg rounded-pill"><i class="bi bi-person"></i> Sudah Punya Akun</a>
        </div>
        <div class="row g-3 mt-4">
          <div class="col-4"><div class="stat-card"><div class="num">10K+</div><small class="text-muted">Pelanggan Aktif</small></div></div>
          <div class="col-4"><div class="stat-card"><div class="num">500+</div><small class="text-muted">Jenis Obat</small></div></div>
          <div class="col-4"><div class="stat-card"><div class="num">24/7</div><small class="text-muted">Layanan</small></div></div>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <img src="https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=900" class="hero-img img-fluid floating" alt="Apotek">
      </div>
    </div>
  </div>
</section>

<section class="section" id="fitur" style="background:#f8fafc;">
  <div class="container">
    <h2 class="section-title">Mengapa Memilih Kami?</h2>
    <p class="section-sub">Pengalaman belanja obat yang aman, cepat, dan profesional</p>
    <div class="row g-4">
      <?php
      $features=[
        ['bi-capsule-pill','Obat Lengkap & Asli','500+ jenis obat resep & bebas, semua bersertifikasi BPOM dan terjamin keasliannya.'],
        ['bi-person-badge','Apoteker Berlisensi','Tim apoteker profesional siap melayani konsultasi dan rekomendasi obat untuk Anda.'],
        ['bi-truck','Pengiriman Cepat','Pesanan diproses cepat dan dikirim langsung ke alamat Anda di hari yang sama.'],
        ['bi-qr-code','Pembayaran Fleksibel','Bayar dengan QRIS atau Cash on Delivery (COD). Aman, mudah, dan cepat.'],
        ['bi-shield-lock','Data Aman','Privasi data Anda terjaga dengan sistem keamanan berstandar tinggi.'],
        ['bi-calendar-check','Cek Kadaluarsa','Sistem otomatis memastikan setiap obat masih dalam masa edar aman.'],
      ];
      foreach($features as $f): ?>
        <div class="col-md-6 col-lg-4">
          <div class="feature-card">
            <div class="feature-ico"><i class="bi <?= $f[0] ?>"></i></div>
            <h5 class="fw-bold"><?= $f[1] ?></h5>
            <p class="text-muted mb-0"><?= $f[2] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="galeri">
  <div class="container">
    <h2 class="section-title">Aktivitas di Apotek Kami</h2>
    <p class="section-sub">Dokumentasi pelayanan, penjualan, dan interaksi dengan pelanggan</p>
    <div class="gallery">
      <?php foreach($gallery as $g): ?>
        <div class="item">
          <img src="<?= $g[0] ?>" alt="<?= $g[1] ?>" loading="lazy">
          <div class="cap"><i class="bi bi-camera"></i> <?= $g[1] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" style="background:#f8fafc;">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1585435557343-3b092031a831?w=900" class="img-fluid rounded-4 shadow" alt="Layanan">
      </div>
      <div class="col-lg-6">
        <span class="badge-pill">Cara Kerja</span>
        <h2 class="fw-bold mb-4">Belanja Obat dalam 3 Langkah Mudah</h2>
        <?php
        $steps=[
          ['1','Daftar & Pilih Obat','Buat akun gratis, lalu jelajahi katalog obat lengkap kami.'],
          ['2','Isi Data & Bayar','Lengkapi alamat pengiriman, pilih QRIS atau COD untuk pembayaran.'],
          ['3','Terima Pesanan','Pesanan Anda akan diproses apoteker dan dikirim ke alamat Anda.'],
        ];
        foreach($steps as $s): ?>
          <div class="d-flex gap-3 mb-4">
            <div style="min-width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--brand),var(--brand2));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.2rem;"><?= $s[0] ?></div>
            <div><h5 class="fw-bold mb-1"><?= $s[1] ?></h5><p class="text-muted mb-0"><?= $s[2] ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="section" id="testimoni">
  <div class="container">
    <h2 class="section-title">Apa Kata Pelanggan Kami</h2>
    <p class="section-sub">Ribuan keluarga telah mempercayakan kebutuhan kesehatannya pada kami</p>
    <div class="row g-4">
      <?php
      $testis=[
        ['Siti Rahayu','Ibu Rumah Tangga','Pelayanannya cepat, obatnya lengkap. Apotekernya juga ramah memberi penjelasan dosis.'],
        ['Budi Santoso','Karyawan Swasta','Sangat membantu! Tidak perlu antri, tinggal pesan via aplikasi dan langsung diantar.'],
        ['dr. Maya','Dokter Praktik','Saya sering merekomendasikan apotek ini ke pasien. Stoknya selalu update.'],
      ];
      foreach($testis as $t): ?>
        <div class="col-md-4">
          <div class="testi">
            <div class="stars mb-2">★★★★★</div>
            <p class="mb-3">"<?= $t[2] ?>"</p>
            <div class="d-flex align-items-center gap-3">
              <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--brand),var(--brand2));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;"><?= strtoupper(substr($t[0],0,1)) ?></div>
              <div><div class="fw-bold"><?= $t[0] ?></div><small class="text-muted"><?= $t[1] ?></small></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="kontak">
  <div class="container">
    <div class="cta-section">
      <h2>Siap Untuk Memulai?</h2>
      <p class="lead mb-4 opacity-75">Daftar gratis sekarang dan nikmati kemudahan belanja obat di Apotek Sehat</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= base_url('signup.php') ?>" class="btn btn-light btn-lg rounded-pill px-4 fw-bold"><i class="bi bi-rocket-takeoff"></i> Daftar Sekarang</a>
        <a href="<?= base_url('login.php') ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">Masuk Akun</a>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <h5 class="fw-bold text-white"><i class="bi bi-prescription2"></i> Apotek Sehat</h5>
        <p class="mt-3">Mitra terpercaya untuk semua kebutuhan obat dan kesehatan keluarga Indonesia.</p>
      </div>
      <div class="col-md-2"><h6>Menu</h6><ul class="list-unstyled mt-3"><li><a href="#home">Beranda</a></li><li><a href="#fitur">Fitur</a></li><li><a href="#galeri">Galeri</a></li></ul></div>
      <div class="col-md-3"><h6>Akun</h6><ul class="list-unstyled mt-3"><li><a href="<?= base_url('login.php') ?>">Login</a></li><li><a href="<?= base_url('signup.php') ?>">Daftar</a></li></ul></div>
      <div class="col-md-3"><h6>Kontak</h6><ul class="list-unstyled mt-3"><li><i class="bi bi-geo-alt"></i> Jl. Sehat No.123, Jakarta</li><li><i class="bi bi-telephone"></i> (021) 1234-5678</li><li><i class="bi bi-envelope"></i> hello@apoteksehat.id</li></ul></div>
    </div>
    <hr class="border-secondary my-4">
    <div class="text-center"><small>&copy; <?= date('Y') ?> Apotek Sehat. All rights reserved.</small></div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
