<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operating Cost Report</title>
    <link href="{{asset('/assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-center">
                    <h4>Operating Cost Report</h4>
                    <h5>{{$data_cabang->nama_cabang}}</h5>
                    <h6>{{$data_cabang->alamat}}</h6>
                    <H6>PHONE : {{$data_cabang->telepon}}</H6>
                    <br>
                </div>
            </div>
        </div>
        <br>
        <table border="2" id="tabel" style="border: 2px solid black; width:100%;">
            <thead>
                <tr>
                    <th>COST ID</th>
                    <th>ID REQUESTER</th>
                    <th>NAME</th>
                    <th>OTHER</th>
                    <th>COST NAME</th>
                    <th>NOMINAL</th>
                    <th>COSTING DATE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td>{{$item->inv_cost}}</td>
                    <td>{{$item->id_sales}}</td>
                    <td>{{$item->nama_sales}}</td>
                    <td>{{$item->note}}</td>
                    <td>{{$item->cost_nama}}</td>
                    <td>{{number_format($item->nominal)}}</td>
                    <td>{{date('d-m-Y',strtotime($item->tanggal))}}</td>
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
