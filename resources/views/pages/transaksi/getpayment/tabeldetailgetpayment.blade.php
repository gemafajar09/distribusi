<?php
$total = 0;
foreach($detail as $i => $a):
$total += $a->payment;
?>
<tr>
    <td>{{$i+1}}</td>
    <td>{{$a->invoice_id}}</td>
    <td>{{$a->tgl_payment}}</td>
    <td>Rp.{{number_format($a->payment)}}</td>
    <td>{{$a->status}}</td>
</tr>
<?php endforeach ?>
<tr>
    <td colspan="3">Total Pembayaran</td>
    <td colspan="2">Rp.{{$total}}</td>
</tr>