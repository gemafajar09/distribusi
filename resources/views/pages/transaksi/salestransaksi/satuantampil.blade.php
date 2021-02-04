<?php
$no = 0;
?>
@foreach($isi as $a)
<?php
$no++;
$data = explode(" ",$a);
?>
<?php
if($total == 1)
{ ?>
    <div class="col-md-6">
    <div class="input-group">
        <input style="width: 20px;" readonly type="text" value="{{$data[0]}}" class="form-control" id="stok<?= $no ?>">
        <input readonly type="hidden" value="{{$data[2]}}" class="form-control" id="pecah<?= $no ?>">
        <div class="input-group-prepend">
            <div class="input-group-text" style="font-size: 10px;">{{$data[1]}}</div>
        </div>
    </div>
    <div class="input-group">
        <input type="text" value="0" class="form-control" id="jumlah<?= $no ?>">
        <div class="input-group-prepend">
            <div class="input-group-text"  style="font-size: 10px;">{{$data[1]}}</div>
        </div>
    </div>
</div>
<?php }elseif($total == 2){
?>
<div class="col-md-6">
    <div class="input-group">
        <input style="width: 20px;" readonly type="text" value="{{$data[0]}}" class="form-control" id="stok<?= $no ?>">
        <input readonly type="hidden" value="{{$data[2]}}" class="form-control" id="pecah<?= $no ?>">
        <div class="input-group-prepend">
            <div class="input-group-text" style="font-size: 10px;">{{$data[1]}}</div>
        </div>
    </div>
    <div class="input-group">
        <input type="text" value="0" class="form-control" id="jumlah<?= $no ?>">
        <div class="input-group-prepend">
            <div class="input-group-text"  style="font-size: 10px;">{{$data[1]}}</div>
        </div>
    </div>
</div>
<?php }elseif($total == 3){ ?>
    <div class="col-md-4">
    <div class="input-group">
        <input style="width: 20px;" readonly type="text" value="{{$data[0]}}" class="form-control" id="stok<?= $no ?>">
        <input readonly type="hidden" value="{{$data[2]}}" class="form-control" id="pecah<?= $no ?>">
        <div class="input-group-prepend">
            <div class="input-group-text" style="font-size: 10px;">{{$data[1]}}</div>
        </div>
    </div>
    <div class="input-group">
        <input type="text" value="0" class="form-control" id="jumlah<?= $no ?>">
        <div class="input-group-prepend">
            <div class="input-group-text"  style="font-size: 10px;">{{$data[1]}}</div>
        </div>
    </div>
</div>
<?php } ?>
@endforeach
<input type="hidden" id="totals" value="<?= $no?>">
