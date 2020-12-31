<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Achievment</title>
    <link href="{{asset('/assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-center">
                    <h4>LIST OF STOCK SALES TRANSACTION BY SALESMAN</h4>
                </div>
            </div>
        </div>
        <hr style="border: 20 solid:black">
        <div class="row">
            <div class="col-sm-4">
                <b>Report Type :</b>
            </div>
            <div class="col-sm-5 offset-3">
                <b>Salesman: ANDRE ALFINDRA(CANVAS)</b>
            </div>
        </div>
        <table border="2" id="tabel" style="border: 2px solid black; width:100%;">
            <thead>
                <tr>
                    <th>PRODUCT ID</th>
                    <th>PRODUCT DESCRIPTION</th>
                    <th>QUANTITY</th>
                </tr>
            </thead>
            <tbody>
                                @foreach ($datas as $d)
                <tr>
                    <td>{{$d['produk_id']}}</td>
                    <td>{{$d['produk_nama']}}</td>
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
