<?php
$page_title='Form Obat';
require_once __DIR__.'/../includes/header.php';
require_role(['admin','apoteker']);
$id=(int)($_GET['id']??0);
$data=['kode_obat'=>'','nama_obat'=>'','kategori'=>'','satuan'=>'','harga_beli'=>0,'harga_jual'=>0,'stok'=>0,'tgl_kadaluarsa'=>'','supplier_id'=>'','deskripsi'=>'','status'=>'tersedia'];
if ($id){ $s=$conn->prepare("SELECT * FROM obat WHERE id=?"); $s->execute([$id]); $data=$s->fetch()?:$data; }
$suppliers=$conn->query("SELECT id,nama_supplier FROM supplier ORDER BY nama_supplier")->fetchAll();
if ($_SERVER['REQUEST_METHOD']==='POST'){
  $f=$_POST;
  if ($id){
    $conn->prepare("UPDATE obat SET kode_obat=?,nama_obat=?,kategori=?,satuan=?,harga_beli=?,harga_jual=?,stok=?,tgl_kadaluarsa=?,supplier_id=?,deskripsi=?,status=? WHERE id=?")
      ->execute([$f['kode_obat'],$f['nama_obat'],$f['kategori'],$f['satuan'],$f['harga_beli'],$f['harga_jual'],$f['stok'],$f['tgl_kadaluarsa'],$f['supplier_id']?:null,$f['deskripsi'],$f['status'],$id]);
    flash('success','Obat diperbarui.');
  } else {
    $conn->prepare("INSERT INTO obat(kode_obat,nama_obat,kategori,satuan,harga_beli,harga_jual,stok,tgl_kadaluarsa,supplier_id,deskripsi,status) VALUES(?,?,?,?,?,?,?,?,?,?,?)")
      ->execute([$f['kode_obat'],$f['nama_obat'],$f['kategori'],$f['satuan'],$f['harga_beli'],$f['harga_jual'],$f['stok'],$f['tgl_kadaluarsa'],$f['supplier_id']?:null,$f['deskripsi'],$f['status']]);
    flash('success','Obat ditambahkan.');
  }
  $back = $_SESSION['role']==='admin'?'../admin/obat.php':'../apoteker/obat.php';
  header('Location: '.$back); exit;
}
?>
<h2><?= $id?'Edit':'Tambah' ?> Obat</h2>
<div class="card p-4"><form method="post"><div class="row g-3">
<div class="col-md-4"><label class="form-label">Kode</label><input name="kode_obat" class="form-control" value="<?= e($data['kode_obat']) ?>" required></div>
<div class="col-md-8"><label class="form-label">Nama Obat</label><input name="nama_obat" class="form-control" value="<?= e($data['nama_obat']) ?>" required></div>
<div class="col-md-4"><label class="form-label">Kategori</label><input name="kategori" class="form-control" value="<?= e($data['kategori']) ?>"></div>
<div class="col-md-4"><label class="form-label">Satuan</label><input name="satuan" class="form-control" value="<?= e($data['satuan']) ?>"></div>
<div class="col-md-4"><label class="form-label">Stok</label><input type="number" name="stok" class="form-control" value="<?= e($data['stok']) ?>" required></div>
<div class="col-md-4"><label class="form-label">Harga Beli</label><input type="number" name="harga_beli" class="form-control" value="<?= e($data['harga_beli']) ?>"></div>
<div class="col-md-4"><label class="form-label">Harga Jual</label><input type="number" name="harga_jual" class="form-control" value="<?= e($data['harga_jual']) ?>" required></div>
<div class="col-md-4"><label class="form-label">Tgl Kadaluarsa</label><input type="date" name="tgl_kadaluarsa" class="form-control" value="<?= e($data['tgl_kadaluarsa']) ?>"></div>
<div class="col-md-6"><label class="form-label">Supplier</label><select name="supplier_id" class="form-select"><option value="">-- pilih --</option>
  <?php foreach($suppliers as $s):?><option value="<?= $s['id'] ?>" <?= $data['supplier_id']==$s['id']?'selected':'' ?>><?= e($s['nama_supplier']) ?></option><?php endforeach;?>
</select></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select">
  <?php foreach(['tersedia','habis','ditarik'] as $s):?><option <?= $data['status']==$s?'selected':'' ?>><?= $s ?></option><?php endforeach;?>
</select></div>
<div class="col-12"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"><?= e($data['deskripsi']) ?></textarea></div>
</div>
<div class="mt-3"><button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
<a href="javascript:history.back()" class="btn btn-secondary">Batal</a></div></form></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
