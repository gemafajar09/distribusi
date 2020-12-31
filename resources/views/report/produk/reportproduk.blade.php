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
        <div class="row">
            <div class="col-sm-12">
        <div class="text-center">
            <h4>Report Purchase</h4>
            <h5>SUMBER CAHAYA REZEKI</h5>
            <h6>Jl. BY PASS TALUAK BUKITTINGGI, INDONESIA</h6>
            <H6>PHONE : 085375715757 FAX : (0752) 8810863</H6>
            <hr style="border:2px solid black;">
        </div>
        </div>
        </div>

        
    <table border="2" id="tabel"  style="border: 2px solid black; width:100%;">
    <thead>
    <tr>
                   <th>PRODUK ID</th>
                   <th>PRODUK BRAND</th>
                   <th>NAMA PRODUK</th>
                   <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr>
                   <td>{{$d['produk_id']}}</td>
                   <td>{{$d['produk_brand']}}</td>
                   <td>{{$d['produk_brand']}}</td>
                   <td>{{$d['quantity']}}</td>
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
            window.print();   
    });
    </script>
</body>
</html>