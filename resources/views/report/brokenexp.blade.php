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
            <h4>Report Broken ExPired</h4>
            <h5>{{$data_cabang->nama_cabang}}</h5>
            <h6>{{$data_cabang->alamat}}</h6>
            <H6>PHONE : {{$data_cabang->telepon}} EMAIL : {{$data_cabang->email}}</H6>
            <hr style="border:2px solid black;">
        </div>
        </div>
        </div>
    <table border="2"  style="border: 2px solid black; width:100%;">
    <thead style="text-align: center;">
        <tr>
                        <th>STOCK ID</th>
                        <th>PRODUCT TYPE</th>
                        <th>PRODUCT BRAND</th>
                        <th>PRODUCT NAME</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataconversi as $d)
        <tr>
            <td>{{$d['stok_id']}}</td>
            <td>{{$d['nama_type']}}</td>
            <td>{{$d['produk_brand']}}</td>
            <td>{{$d['produk_nama']}}</td>
            <td>{{$d['jumlah_broken']}}</td>
            <td>{{"Rp " . number_format($d['total'],2,',','.')}}</td>
        </tr>
        
        @endforeach
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