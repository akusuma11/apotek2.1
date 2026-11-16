<?php
$page_title='Supplier';
require_once __DIR__.'/../includes/header.php';
require_role('admin');
if (isset($_GET['delete'])){ $conn->prepare("DELETE FROM supplier WHERE id=?")->execute([(int)$_GET['delete']]); flash('success','Supplier dihapus.'); header('Location: supplier.php'); exit; }
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $id=(int)($_POST['id']??0); $f=$_POST;
  if ($id) $conn->prepare("UPDATE supplier SET nama_supplier=?,alamat=?,no_telp=?,email=? WHERE id=?")->execute([$f['nama_supplier'],$f['alamat'],$f['no_telp'],$f['email'],$id]);
  else $conn->prepare("INSERT INTO supplier(nama_supplier,alamat,no_telp,email) VALUES(?,?,?,?)")->execute([$f['nama_supplier'],$f['alamat'],$f['no_telp'],$f['email']]);
  flash('success','Supplier disimpan.'); header('Location: supplier.php'); exit;
}
$edit=null;
if (isset($_GET['edit'])){ $s=$conn->prepare("SELECT * FROM supplier WHERE id=?"); $s->execute([(int)$_GET['edit']]); $edit=$s->fetch(); }
$rows=$conn->query("SELECT * FROM supplier ORDER BY id DESC")->fetchAll();
?>
<h2>Data Supplier</h2>
<div class="row g-3">
<div class="col-md-5"><div class="card p-3"><h5><?= $edit?'Edit':'Tambah' ?> Supplier</h5>
<form method="post"><input type="hidden" name="id" value="<?= e($edit['id']??'') ?>">
<div class="mb-2"><label class="form-label">Nama</label><input name="nama_supplier" class="form-control" value="<?= e($edit['nama_supplier']??'') ?>" required></div>
<div class="mb-2"><label class="form-label">Telp</label><input name="no_telp" class="form-control" value="<?= e($edit['no_telp']??'') ?>"></div>
<div class="mb-2"><label class="form-label">Email</label><input name="email" class="form-control" value="<?= e($edit['email']??'') ?>"></div>
<div class="mb-2"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2"><?= e($edit['alamat']??'') ?></textarea></div>
<button class="btn btn-success">Simpan</button>
<?php if($edit):?><a href="supplier.php" class="btn btn-secondary">Batal</a><?php endif;?>
</form></div></div>
<div class="col-md-7"><div class="card p-3"><div class="table-responsive"><table class="table table-hover align-middle">
<thead><tr><th>Nama</th><th>Telp</th><th>Email</th><th>Aksi</th></tr></thead><tbody>
<?php foreach($rows as $r):?>
<tr><td><?= e($r['nama_supplier']) ?></td><td><?= e($r['no_telp']) ?></td><td><?= e($r['email']) ?></td>
<td><a class="btn btn-sm btn-warning" href="?edit=<?= $r['id'] ?>"><i class="bi bi-pencil"></i></a>
<a class="btn btn-sm btn-danger" href="?delete=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></a></td></tr>
<?php endforeach;?>
</tbody></table></div></div></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
