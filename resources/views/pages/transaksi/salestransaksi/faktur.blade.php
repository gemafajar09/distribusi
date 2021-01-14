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
            <table width="100%" style="font-size:14px">
                <tr>
                    <td style="width: 60%;">
                        <b style="font-size:12px">{{$data_cabang->nama_cabang}}</b>
                    </td>

                    <td colspan="3" rowspan="4" style="width:40%; font-size:14px">
                        <table align="right" style="border-collapse: collapse; border: 1px solid black;" id="tab">
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black; width:150px; text-align:left">No. Faktur :</th>
                                <th style="border: 1px solid black; width:150px; text-align:right">{{$inv}}</th>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black; width:150px; text-align:left">Tgl. Faktur :</th>
                                <th style="border: 1px solid black; width:150px; text-align:right">{{tanggal_indonesia($sales['invoice_date'])}}</th>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black; width:150px; text-align:left">Tipe Faktur :</th>
                                <th style="border: 1px solid black; width:150px; text-align:right">{{$sales['transaksi_tipe']}}</th>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black; width:150px; text-align:left">Tgl. Jatuh Tempo :</th>
                                <th style="border: 1px solid black; width:150px; text-align:right">{{tanggal_indonesia($sales['term_until'])}}</th>
                            </tr>
                            <tr style="border: 1px solid black;">
                                <th style="border: 1px solid black; width:150px; text-align:left">Salesman :</th>
                                <th style="border: 1px solid black; width:150px; text-align:right">{{$salesnama}}</th>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        PHONE : {{$data_cabang->telepon}}
                    </td>
                    <td rowspan="3">

                        {{-- Perubahan Baru --}}

                    </td>
                </tr>

                <tr>
                    <td>
                        EMAIL : {{$data_cabang->email}}
                    </td>
                </tr>

                {{-- <tr>
                    <td>
                        SALES : {{$salesnama}}<br>
                        NAMA TOKO : {{$sales['nama_customer']}}, {{$sales['alamat']}}, {{$sales['kota']}},<br>Telpon :
                        {{$sales['telepon']}}
                    </td>
                </tr> --}}
            </table>

            <center>Faktur Penjualan</center>
            {{-- Perubahan Tertuju --}}
            <table style="font-size:14px; border-collapse:collapse; margin-bottom:5px; ">
                <tr>
                    <th style=" text-align:left; height:1px;">Kepada Yth</th>
                </tr>
                <tr>
                    <th style=" text-align:left; height:1px; border: 1px solid black;">Nama Pelanggan : </th>
                    <th style=" text-align:right; height:1px; border: 1px solid black;">{{$sales['nama_customer']}}</th>
                </tr>
                <tr>
                    <th style=" text-align:left; height:1px; border: 1px solid black;">Alamat Pelanggan : </th>
                    <th style=" text-align:right; height:1px; border: 1px solid black;">{{$sales['alamat']}}, {{$sales['kota']}}</th>
                </tr>
                <tr>
                    <th style=" text-align:left; height:1px; border: 1px solid black;">No. Telp : </th>
                    <th style=" text-align:right; height:1px; border: 1px solid black;">{{$sales['telepon']}}</th>
                </tr>
            </table>

            <table style="font-size:14px; width:100%; border-collapse:collapse; border:1px solid black;">
                <tr style="border:1px solid grey">
                    <th style="width:30%; text-align:left; height:1px; border: 1px solid black;">NAMA BARANG </th>
                    <th style="width:22%; text-align:left; height:1px; border: 1px solid black;">JUMLAH BARANG </th>
                    <th style="width:12%; text-align:left; height:1px; border: 1px solid black;">HARGA </th>
                    <th style="width:12%; text-align:left; height:1px; border: 1px solid black;">TOTAL </th>
                    <th style="width:12%; text-align:left; height:1px; border: 1px solid black;">DISKON </th>
                    <th style="width:12%; text-align:right; height:1px; border: 1px solid black;">NILAI </th>
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
                    <td style="border:1px; height:1px; border: 1px solid black; text-align:right">
                        Rp.{{number_format($d['amount'])}}</td>
                </tr>
                @endforeach
                <tr style="border:0 solid white">
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>TOTAL PEMBELIAN&nbsp;&nbsp;</b>
                    </td>
                    <td style="text-align: right;">
                        Rp.{{number_format($tot)}}
                    </td>
                </tr>

                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>TOTAL DISKON&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format($sales['diskon'])}}
                    </td>
                </tr>

                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>TOTAL SETELAH DISKON&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format($tot - $sales['diskon'])}}
                    </td>
                </tr>

                {{-- <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>DOWN PAYMENT&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format($sales['dp'])}}
                    </td>
                </tr> --}}

                <tr>
                    <td colspan="3"></td>
                    <td colspan="2" style="text-align: left;">
                        <b>TOTAL SALDO HUTANG&nbsp;&nbsp;</b>
                    </td>
                    <td style="height:1px; text-align: right;">
                        Rp.{{number_format(($tot - $sales['diskon']) - $sales['dp'] )}}
                    </td>
                </tr>
            </table>

            <table width="100%" style="font-size:12px">
                <tr>
                    <td style="width:10%"><i style="font-size:12px; height:1px">Note :&nbsp;&nbsp;</i></td>
                    <td style="width:90%"><i style="font-size:12px; height:1px"> Ok</i></td>
                </tr>
            </table>
            <hr style="border-top:2px solid black;">
            <table width="100%" style="font-size:12px; text-align:center">
                <tr>
                    <td colspan="2" style="font-size:12px; height:1px">HORMAT KAMI</td>
                    <td style="font-size:12px; height:1px">GUDANG</td>
                    <td style="font-size:12px; height:1px">SUPIR</td>
                    <td style="font-size:12px; height:1px">PELANGGAN</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td><br>_ _ _ _ _ _ _ _ _ _ _ <br>
                    Admin Sales
                    </td>
                    <td><br>_ _ _ _ _ _ _ _ _ _ _ <br>
                    Pimpinan
                    </td>
                    <td>_ _ _ _ _ _ _ _ _ _ _</td>
                    <td>_ _ _ _ _ _ _ _ _ _ _</td>
                    <td>_ _ _ _ _ _ _ _ _ _ _</td>
                </tr>
            </table>
        </body>
