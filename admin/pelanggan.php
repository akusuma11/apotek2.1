<?php
$page_title='Pelanggan';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
if (isset($_GET['delete'])){ $conn->prepare("DELETE FROM users WHERE id=? AND role='pelanggan'")->execute([(int)$_GET['delete']]); flash('success','Pelanggan dihapus.'); header('Location: pelanggan.php'); exit; }
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $f=$_POST; $h=password_hash($f['password']?:'password123',PASSWORD_DEFAULT);
  $conn->prepare("INSERT INTO users(username,password,nama_lengkap,email,no_telp,alamat,role) VALUES(?,?,?,?,?,?,'pelanggan')")
       ->execute([$f['username'],$h,$f['nama_lengkap'],$f['email'],$f['no_telp'],$f['alamat']]);
  flash('success','Pelanggan ditambahkan.'); header('Location: pelanggan.php'); exit;
}
$rows=$conn->query("SELECT * FROM users WHERE role='pelanggan' ORDER BY id DESC")->fetchAll();
?>
<h2>Data Pelanggan</h2>
<div class="row g-3">
<div class="col-md-4"><div class="card p-3"><h5>Daftarkan Pelanggan</h5>
<form method="post">
<div class="mb-2"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
<div class="mb-2"><label class="form-label">Password</label><input name="password" class="form-control" placeholder="default: password123"></div>
<div class="mb-2"><label class="form-label">Nama</label><input name="nama_lengkap" class="form-control" required></div>
<div class="mb-2"><label class="form-label">Email</label><input name="email" class="form-control"></div>
<div class="mb-2"><label class="form-label">Telp</label><input name="no_telp" class="form-control"></div>
<div class="mb-2"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control"></textarea></div>
<button class="btn btn-success w-100">Simpan</button></form></div></div>
<div class="col-md-8"><div class="card p-3"><div class="table-responsive"><table class="table align-middle">
<thead><tr><th>Username</th><th>Nama</th><th>Email</th><th>Telp</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r):?>
<tr><td><?= e($r['username']) ?></td><td><?= e($r['nama_lengkap']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['no_telp']) ?></td>
<td><a class="btn btn-sm btn-danger" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a></td></tr>
<?php endforeach;?>
</tbody></table></div></div></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
