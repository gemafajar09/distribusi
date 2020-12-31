<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{asset('/assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="mt-2 mb-2">
<button id="close" class="btn btn-danger btn-sm">Close</button> <button id="print" class="btn btn-dark btn-sm">Print</button>
        </div>
        <div class="row">
            <div class="col-sm-12">
        <div class="text-center">
            <h4>Rekap Transaksi Cabang</h4>
            <h5>{{$data_cabang->nama_cabang}}</h5>
            <h6>{{$data_cabang->alamat}}</h6>
            <H6>PHONE : {{$data_cabang->telepon}} EMAIL : {{$data_cabang->email}}</H6>
            <hr style="border:2px solid black;">
        </div>
        </div>
        </div>
        <h5>Waktu : {{'Bulan '.$bulan.' Tahun '.$tahun}} </h5>
    <table border="2"  style="border: 2px solid black; width:100%;" class="table-striped">
    <thead style="text-align: center;">
        <tr class="bg-warning">
            <td>No</td>
            <td>Keterangan</td>
            <td>Jumlah</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="text-center">1</td>
            <td>Total Pembelian Barang</td>
            <td>{{$total_pembelian}}</td>
        </tr>
        <tr>
            <td class="text-center">2</td>
            <td>Stok In Price</td>
            <td>{{$stok_price}}</td>
        </tr>
        <tr>
            <td class="text-center">3</td>
            <td>Total Penjualan</td>
            <td>{{$total_penjualan}}</td>
        </tr>
        <tr>
            <td class="text-center">4</td>
            <td>Total Keuntungan</td>
            <td>{{$profit}}</td>
        </tr>
    </tbody>
    </table>
    
</div>

    
<!-- script -->
<script src="{{asset('/assets/vendors/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('/assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<script>
        $( document ).ready(function() {
            $('#print').on('click',function(){
                $("#print").hide();
                $("#close").hide();
                window.print();
                $("#print").show();
                $("#close").show();
            });
            $('#close').on('click',function(){
        
                window.close();
                
            });
    });
    </script>
</body>
</html>