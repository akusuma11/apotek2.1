<?php
$page_title='Form Apoteker';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
$id=(int)($_GET['id']??0); $data=['username'=>'','nama_lengkap'=>'','email'=>'','no_telp'=>'','alamat'=>'','status'=>'aktif'];
if ($id){ $s=$conn->prepare("SELECT * FROM users WHERE id=? AND role='apoteker'"); $s->execute([$id]); $data=$s->fetch()?:$data; }
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $u=trim($_POST['username']); $n=trim($_POST['nama_lengkap']); $em=trim($_POST['email']);
  $tl=trim($_POST['no_telp']); $al=trim($_POST['alamat']); $st=$_POST['status']; $p=$_POST['password']??'';
  if ($id){
    if ($p!==''){
      $h=password_hash($p,PASSWORD_DEFAULT);
      $conn->prepare("UPDATE users SET username=?,nama_lengkap=?,email=?,no_telp=?,alamat=?,status=?,password=? WHERE id=?")
           ->execute([$u,$n,$em,$tl,$al,$st,$h,$id]);
    } else {
      $conn->prepare("UPDATE users SET username=?,nama_lengkap=?,email=?,no_telp=?,alamat=?,status=? WHERE id=?")
           ->execute([$u,$n,$em,$tl,$al,$st,$id]);
    }
    flash('success','Apoteker diperbarui.');
  } else {
    $h=password_hash($p?:'password123',PASSWORD_DEFAULT);
    $conn->prepare("INSERT INTO users(username,password,nama_lengkap,email,no_telp,alamat,role,status) VALUES(?,?,?,?,?,?,'apoteker',?)")
         ->execute([$u,$h,$n,$em,$tl,$al,$st]);
    flash('success','Apoteker ditambahkan.');
  }
  header('Location: apoteker.php'); exit;
}
?>
<h2><?= $id?'Edit':'Tambah' ?> Apoteker</h2>
<div class="card p-4"><form method="post"><div class="row g-3">
<div class="col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" value="<?= e($data['username']) ?>" required></div>
<div class="col-md-6"><label class="form-label">Password <?= $id?'<small class="text-muted">(kosongkan jika tidak diubah)</small>':'' ?></label><input type="password" name="password" class="form-control" <?= $id?'':'required' ?>></div>
<div class="col-md-6"><label class="form-label">Nama Lengkap</label><input name="nama_lengkap" class="form-control" value="<?= e($data['nama_lengkap']) ?>" required></div>
<div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($data['email']) ?>"></div>
<div class="col-md-6"><label class="form-label">No. Telp</label><input name="no_telp" class="form-control" value="<?= e($data['no_telp']) ?>"></div>
<div class="col-md-6"><label class="form-label">Status</label>
  <select name="status" class="form-select"><option value="aktif" <?= $data['status']=='aktif'?'selected':'' ?>>Aktif</option>
  <option value="nonaktif" <?= $data['status']=='nonaktif'?'selected':'' ?>>Nonaktif</option></select></div>
<div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2"><?= e($data['alamat']) ?></textarea></div>
</div>
<div class="mt-3"><button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
<a href="apoteker.php" class="btn btn-secondary">Batal</a></div></form></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
