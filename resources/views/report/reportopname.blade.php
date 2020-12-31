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
            <h4>Report Stock Opname</h4>
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
            <td rowspan="2">Nama Produk</td>
            <td>System Stok</td>
            <td>Fisik Stok</td>
            <td>Unbalance</td>
            <td rowspan="2">Date</td>
            <td rowspan="2">Status</td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td>Quantity</td>
            <td>Quantity</td>
        </tr>
    </thead>
    <tbody>
        @foreach($dataisi as $d)
        <tr>
            <td>{{$d['produk_nama']}}</td>
            <td>{{$d['jumlah']}}</td>
            <td>{{$d['jumlah_fisik']}}</td>
            <td>{{$d['selisih']}}</td>
            <td>{{$d['update_opname']}}
            </td>
            <td>@if($d['balance']=='0')
                {{"Not Balance"}}
                @elseif($d['balance']=='1')
                {{"Balance"}}
                @elseif($d['balance']=='2')
                {{"Waiting Adjust"}}
                @elseif($d['balance']=='3')
                {{"Adjust Reject"}}
                @else
                {{"Not Cek"}}
                @endif</td>
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