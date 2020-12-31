{{--  --}}
<?php $nmsales = DB::table('tbl_sales')->where('id_sales',$sales['id_sales'])->first(); 
    if($nmsales != NULL)
    { 
        $salesnama = $nmsales->nama_sales;
    }
    else
    {
        $salesnama = '-';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
	<font face="Calibri">
		<head>
		</head>
		<body onLoad="javascript:print()">
        <!-- header faktur -->
			<table width="100%" style="font-size:9px">
				<tr>
					<td style="width: 20%;">
						<b style="font-size:12px">{{$data_cabang->nama_cabang}}</b>
					</td>
					<td style="width:56%">&nbsp;</td>

					<td colspan="3" style="width:9%; font-size:12px">
						<b>TRANSAKSI SALES<b>
                            <hr>
                        {{-- <hr style="border-top:2px solid black;"> --}}
					</td>
				</tr>

				<tr>
					<td>
						<b>{{$data_cabang->alamat}}</b>
					</td>
					<td>&nbsp;</td>
                    <td style="width:10%; font-size:12px">
						<b>INVOICE ID</b>
					</td>
					<td style="width:1%">
						:
					</td>
					<td style="width:15%">
						<b>{{$inv}}</b>
					</td>
				</tr>

				<tr>
					<td>
						PHONE : {{$data_cabang->telepon}}
					</td>
					<td>&nbsp;</td>

					<td colspan="3" rowspan="3">
						<table style="border-collapse: collapse; border: 1px solid black;" id="tab">
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black; width:80px">INVOICE DATE</th>
                                <th style="border: 1px solid black; width:80px">INVOICE TYPE</th>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <td style="border: 1px solid black; width:80px; text-align:center"><?= date('d-m-Y') ?></td>
                                <td style="border: 1px solid black; width:80px; text-align:center">{{$sales['transaksi_tipe']}}</td>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black; width:80px">P.I.C</th>
                                <th style="border: 1px solid black; width:80px">TERM</th>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <td style="border: 1px solid black; width:80px; text-align:center">Admin1</td>
                                <td style="border: 1px solid black; width:80px; text-align:center">{{$sales['invoice_date']}}</td>
                            </tr>
                        </table>
					</td>
				</tr>

                <tr>
					<td>
						EMAIL : {{$data_cabang->email}}
					</td>
					<td>&nbsp;</td>
				</tr>

                <tr>
					<td>
						Sales : <?= $salesnama ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
            
			<table style="font-size:9px; width:100%; border-collapse:collapse; border:1px solid black;">
				<tr style="border:1px solid grey">
					<th style="width:30%; text-align:left; height:1px; border: 1px solid black;">ITEM DESCRIPTION </th>
					<th style="width:22%; text-align:left; height:1px; border: 1px solid black;">QUANTITY </th>
					<th style="width:12%; text-align:left; height:1px; border: 1px solid black;">PRICE</th>
					<th style="width:12%; text-align:left; height:1px; border: 1px solid black;">TOTAL</th>
					<th style="width:12%; text-align:left; height:1px; border: 1px solid black;">DISKON</th>
					<th style="width:12%; text-align:right; height:1px; border: 1px solid black;">AMOUNT</th>
				</tr>
                <?php $tot = 0; ?>
                @foreach($init as $d)
                <?php $tot += $d['amount'] ?>
				<tr>
					<td style="border:1px; height:1px; border: 1px solid black;">{{$d['produk_nama']}}</td>
					<td style="border:1px; height:1px; border: 1px solid black;">{{$d['quantity']}}</td>
					<td style="border:1px; height:1px; border: 1px solid black;">Rp.{{$d['produk_harga']}}</td>
					<td style="border:1px; height:1px; border: 1px solid black;">Rp.{{$d['total']}}</td>
					<td style="border:1px; height:1px; border: 1px solid black;">Rp.{{$d['diskon']}}</td>
					<td style="border:1px; height:1px; border: 1px solid black; text-align:right">Rp.{{number_format($d['amount'])}}</td>
				</tr>
                @endforeach
                <tr style="border:0 solid white">
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>TOTAL PURCHASE&nbsp;&nbsp;</b>
                    </td>
                    <td style="text-align: right;">
                        Rp.{{number_format($tot)}}
                    </td>
                </tr>

                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>FINAL DISCOUNT&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format($sales['diskon'])}}
                    </td>
                </tr>

                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>TOTAL AFTER DISCOUNT&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format($tot - $sales['diskon'])}}
                    </td>
                </tr>

                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>DOWN PAYMENT&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format($sales['dp'])}}
                    </td>
                </tr>

                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>TOTAL DEBT BALANCE&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format(($tot - $sales['diskon']) - $sales['dp'] )}}
                    </td>
                </tr>
            </table>

			<table width="100%" style="font-size:9px">
				<tr>
					<td style="width:10%"><i style="font-size:9px; height:1px">Note :&nbsp;&nbsp;</i></td>
					<td style="width:90%"><i style="font-size:9px; height:1px"> Ok</i></td>
				</tr>
			</table>
            <hr style="border-top:2px solid black;">
			<table width="100%" style="font-size:9px; text-align:center">
				<tr>
					<td style="font-size:12px; height:1px">PENYEDIA</td>
					<td style="font-size:12px; height:1px">SOPIR</td>
					<td style="font-size:12px; height:1px">PENERIMA</td>
					<td style="font-size:12px; height:1px">GUDANG PENYEDIA</td>
				</tr>
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td>_ _ _ _ _ _ _ _ _ _ _</td>
					<td>_ _ _ _ _ _ _ _ _ _ _</td>
					<td>_ _ _ _ _ _ _ _ _ _ _</td>
					<td>_ _ _ _ _ _ _ _ _ _ _</td>
				</tr>
			</table>
		</body>