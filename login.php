<?php
require_once __DIR__.'/includes/auth.php';
require_once __DIR__.'/config/db.php';

$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND status='aktif' LIMIT 1");
    $stmt->execute([$u]);
    $user = $stmt->fetch();
    if ($user && password_verify($p, $user['password'])) {
        $_SESSION['user_id']=$user['id'];
        $_SESSION['role']=$user['role'];
        $_SESSION['nama']=$user['nama_lengkap'];
        header('Location: '.base_url($user['role'].'/dashboard.php')); exit;
    } else { $err='Username atau password salah.'; }
}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Login - Apotek Sehat</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet"></head>
<body><div class="login-wrap"><div class="login-card">
  <div class="text-center mb-4">
    <i class="bi bi-capsule-pill text-success" style="font-size:3rem"></i>
    <h3 class="mt-2">Apotek Sehat</h3><p class="text-muted">Silakan login untuk melanjutkan</p>
  </div>
  <?php if($err):?><div class="alert alert-danger py-2"><?= e($err) ?></div><?php endif;?>
  <form method="post">
    <div class="mb-3"><label class="form-label">Username</label>
      <input name="username" class="form-control" required autofocus></div>
    <div class="mb-3"><label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required></div>
    <button class="btn btn-success w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
  </form>
  <hr><div class="text-center">
    <small>Belum punya akun? <a href="<?= base_url('signup.php') ?>">Daftar sebagai pelanggan</a></small>
  </div>
  <div class="mt-3 p-2 bg-light rounded small">
    <b>Demo akun (password: <code>password123</code>):</b><br>
    admin / apoteker / pelanggan
  </div>
</div></div></body></html>
