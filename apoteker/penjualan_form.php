<?php
$page_title='Transaksi Penjualan';
require_once __DIR__.'/../includes/header.php';
require_role('apoteker');
$obats=$conn->query("SELECT id,kode_obat,nama_obat,harga_jual,stok FROM obat WHERE status='tersedia' AND stok>0 ORDER BY nama_obat")->fetchAll();
$pelanggans=$conn->query("SELECT id,nama_lengkap FROM users WHERE role='pelanggan' ORDER BY nama_lengkap")->fetchAll();

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $pel=$_POST['pelanggan_id']?:null;
  $items=$_POST['item']??[];
  $items=array_values(array_filter($items, fn($x)=>!empty($x['obat_id']) && (int)$x['qty']>0));
  if (!$items){ flash('error','Minimal 1 item.'); header('Location: penjualan_form.php'); exit; }
  $conn->beginTransaction();
  try {
    $no = 'NJ'.date('YmdHis');
    $total=0;
    $conn->prepare("INSERT INTO penjualan(no_nota,tgl_penjualan,apoteker_id,pelanggan_id,total) VALUES(?,NOW(),?,?,0)")
         ->execute([$no,$_SESSION['user_id'],$pel]);
    $pid=$conn->lastInsertId();
    foreach($items as $it){
      $oid=(int)$it['obat_id']; $qty=(int)$it['qty'];
      $o=$conn->prepare("SELECT harga_jual,stok FROM obat WHERE id=? FOR UPDATE"); $o->execute([$oid]); $o=$o->fetch();
      if (!$o || $o['stok']<$qty) throw new Exception("Stok obat tidak cukup.");
      $sub=$o['harga_jual']*$qty; $total+=$sub;
      $conn->prepare("INSERT INTO penjualan_detail(penjualan_id,obat_id,jumlah,harga,subtotal) VALUES(?,?,?,?,?)")
           ->execute([$pid,$oid,$qty,$o['harga_jual'],$sub]);
      $conn->prepare("UPDATE obat SET stok=stok-?, status=IF(stok-?<=0,'habis',status) WHERE id=?")->execute([$qty,$qty,$oid]);
    }
    $conn->prepare("UPDATE penjualan SET total=? WHERE id=?")->execute([$total,$pid]);
    $conn->commit();
    flash('success','Transaksi berhasil. No Nota: '.$no);
    header('Location: penjualan_detail.php?id='.$pid); exit;
  } catch (Exception $ex){ $conn->rollBack(); flash('error',$ex->getMessage()); header('Location: penjualan_form.php'); exit; }
}
?>
<h2>Transaksi Penjualan Baru</h2>
<form method="post" class="card p-4">
<div class="row g-3 mb-3">
<div class="col-md-6"><label class="form-label">Pelanggan (opsional)</label>
<select name="pelanggan_id" class="form-select"><option value="">-- Umum --</option>
<?php foreach($pelanggans as $p):?><option value="<?= $p['id'] ?>"><?= e($p['nama_lengkap']) ?></option><?php endforeach;?>
</select></div>
</div>
<table class="table" id="items">
<thead><tr><th width="50%">Obat</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
<tbody></tbody></table>
<button type="button" class="btn btn-outline-primary mb-3" onclick="addRow()"><i class="bi bi-plus"></i> Tambah Item</button>
<div class="text-end"><h4>Total: Rp <span id="grand">0</span></h4></div>
<button class="btn btn-success"><i class="bi bi-save"></i> Simpan Transaksi</button>
<a href="penjualan.php" class="btn btn-secondary">Batal</a>
</form>
<script>
const obats = <?= json_encode($obats) ?>;
let idx=0;
function addRow(){
  const tb=document.querySelector('#items tbody');
  const opts=obats.map(o=>`<option value="${o.id}" data-h="${o.harga_jual}" data-s="${o.stok}">${o.nama_obat} (stok ${o.stok})</option>`).join('');
  const tr=document.createElement('tr');
  tr.innerHTML=`<td><select name="item[${idx}][obat_id]" class="form-select" onchange="rowUpd(this)" required>
    <option value="">-- pilih --</option>${opts}</select></td>
    <td class="harga">0</td>
    <td><input type="number" min="1" name="item[${idx}][qty]" value="1" class="form-control qty" onchange="rowUpd(this)"></td>
    <td class="sub">0</td>
    <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove();calc()"><i class="bi bi-x"></i></button></td>`;
  tb.appendChild(tr); idx++;
}
function rowUpd(el){
  const tr=el.closest('tr'); const sel=tr.querySelector('select'); const opt=sel.selectedOptions[0];
  const h=opt?+opt.dataset.h:0; const q=+tr.querySelector('.qty').value||0;
  tr.querySelector('.harga').textContent='Rp '+h.toLocaleString('id-ID');
  tr.querySelector('.sub').textContent='Rp '+(h*q).toLocaleString('id-ID');
  calc();
}
function calc(){
  let t=0; document.querySelectorAll('#items tbody tr').forEach(tr=>{
    const opt=tr.querySelector('select').selectedOptions[0]; const h=opt?+opt.dataset.h:0;
    t+=h*(+tr.querySelector('.qty').value||0);
  });
  document.getElementById('grand').textContent=t.toLocaleString('id-ID');
}
addRow();
</script>
<?php require __DIR__.'/../includes/footer.php'; ?>
