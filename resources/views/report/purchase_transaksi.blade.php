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
        <div class="text-center">
            <h3>Prototipe Bukti Purchase Order</h3>
            <h4>Mediatama Web Indonesia</h4>
        </div>
        <br>
<table class="table" style="border: 2px solid black;">
    <thead>
        <tr>
            <td>Invoice ID</td>
            <td>Invoice Date</td>
            <td>Produk Nama</td>
            <td>Quantity</td>
            <td>Diskon</td>
            <td>Total Price</td>
        </tr>
    </thead>
    <tbody>
        @foreach($datatmp as $d)
        <tr>
            <td>{{$d['invoice_id']}}</td>
            <td>{{$d['invoice_date']}}</td>
            <td>{{$d['produk_nama']}}</td>
            <td>{{$d['stok_quantity']}}</td>
            <td>{{$d['diskon']}}</td>
            <td>{{$d['total_price']}}</td>
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