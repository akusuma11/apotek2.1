<?php
require_once __DIR__.'/includes/auth.php';
require_once __DIR__.'/config/db.php';
$err=$ok='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $u=trim($_POST['username']); $p=$_POST['password'];
    $n=trim($_POST['nama']); $em=trim($_POST['email']);
    $tl=trim($_POST['no_telp']); $al=trim($_POST['alamat']);
    if (strlen($u)<3 || strlen($p)<6) $err='Username min 3 & password min 6 karakter.';
    else {
      $c=$conn->prepare("SELECT id FROM users WHERE username=?"); $c->execute([$u]);
      if ($c->fetch()) $err='Username sudah dipakai.';
      else {
        $hash=password_hash($p, PASSWORD_DEFAULT);
        $conn->prepare("INSERT INTO users(username,password,nama_lengkap,email,no_telp,alamat,role) VALUES(?,?,?,?,?,?,'pelanggan')")
             ->execute([$u,$hash,$n,$em,$tl,$al]);
        $ok='Pendaftaran berhasil. Silakan login.';
      }
    }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Daftar Pelanggan</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet"></head>
<body><div class="login-wrap"><div class="login-card" style="max-width:500px">
  <h3 class="text-center mb-3">Daftar Pelanggan Baru</h3>
  <?php if($err):?><div class="alert alert-danger"><?= e($err) ?></div><?php endif;?>
  <?php if($ok):?><div class="alert alert-success"><?= e($ok) ?></div><?php endif;?>
  <form method="post">
    <div class="row g-2">
      <div class="col-6"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
      <div class="col-6"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
      <div class="col-12"><label class="form-label">Nama Lengkap</label><input name="nama" class="form-control" required></div>
      <div class="col-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
      <div class="col-6"><label class="form-label">No. Telp</label><input name="no_telp" class="form-control"></div>
      <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2"></textarea></div>
    </div>
    <button class="btn btn-success w-100 mt-3">Daftar</button>
  </form>
  <div class="text-center mt-3"><small><a href="<?= base_url('login.php') ?>">Kembali ke login</a></small></div>
</div></div></body></html>
