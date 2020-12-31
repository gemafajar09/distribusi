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
            <div class="col-sm-8">
        <div class="text-left">
            <h5>{{$data_cabang->nama_cabang}}</h5>
            <h6>{{$data_cabang->alamat}}</h6>
            <H6>PHONE : {{$data_cabang->telepon}} EMAIL : {{$data_cabang->email}}</H6>
            <br>
            <p>Suplier : {{$datatmp[0]['nama_suplier']}}</p>
        </div>
        </div>
        
        <div class="col-sm-4">
        <div class="text-center">   
            <h5><b>PURCHASE ORDER<b></h5>
            <hr style="border-top:5px solid black; width:300px;">
            <h6>Invoice ID : {{$datatmp[0]['invoice_id']}}</h6>
            <div class="row mb-2">
                <div class="col-sm-5 offset-1">
                    <table border="2" style="width: 140px;">
                        <tr>
                            <td>Invoice Date</td>
                        </tr>
                        <tr>
                            <td>{{$datatmp[0]['invoice_date']}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6" >
                    <table border="2"style="width: 140px;">
                        <tr>
                            <td>Invoice Type</td>
                        </tr>
                        <tr>
                            <td>@if($datatmp[0]['transaksi_tipe'] == 0 )
                                    {{"Cash"}}
                                @else
                                    {{"Credit"}}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 offset-1">
                    <table border="2" style="width: 140px;">
                        <tr>
                            <td>P.I.C</td>
                        </tr>
                        <tr>
                            <td>ADMIN</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table border="2" style="width: 140px;">
                        <tr>
                            <td>TERM</td>
                        </tr>
                        <tr>
                            <td>{{$datatmp[0]['term_until']}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
        </div>
        </div>
        </div>
        <br>
    <table border="2"  style="border: 2px solid black; text-align:center; width:100%;">
    <thead>
        <tr>
            <td>Item Description</td>
            <td>Quantity</td>
            <td>Total</td>
            <td>Diskon</td>
            <td>Amount</td>
        </tr>
    </thead>
    <tbody>
        @foreach($datatmp as $d)
        <tr>
            <td>{{$d['produk_nama']}}</td>
            <td>{{$d['stok_quantity']}}</td>
            <td>{{"Rp " . number_format($d['total'],2,',','.')}}</td>
            <td>{{"Rp " . number_format($d['diskon'],2,',','.')}}</td>
            <td>{{"Rp " . number_format($d['total_price'],2,',','.')}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
    <br>
    <div class="row">
        <div class="col-sm-7">
            <p>Note :</p>
            <hr style="border-top: 5px solid black;">
            <div class="row mb-5">
                <div class="col-sm-8">
                    <p class="ml-4">Disiapkan Oleh</p>
                </div>
                <div class="col-sm-4">
                    <p class="ml-3">Disetujui Oleh</p>
                </div>
            </div>
            <div class="row">
            <div class="col-sm-8">
                    <p>............................</p>
                    <p>Tanggal : {{date('d-m-Y')}}</p>
                </div>
                <div class="col-sm-4">
                <p>............................</p>
                    <p>Tanggal : </p>
                </div>
            </div>
        </div>
        <div class="col-sm-5 mt-5">
            <table  border="2" style="width: 100%;">
                <tr>
                    <td style="border-bottom: none;">Total Purchase :</td>
                    <td>{{"Rp " . number_format($calculate[0],2,',','.')}}</td>
                </tr>
                <tr>
                    <td>Final Discount :</td>
                    <td>{{"Rp " . number_format($calculate[1],2,',','.')}}</td>
                </tr>
                <tr>
                    <td>Total After Discount :</td>
                    <td>{{"Rp " . number_format($calculate[0]-$calculate[1],2,',','.')}}</td>
                </tr>
                <tr>
                    <td>Down Payment :</td>
                    <td>{{"Rp " . number_format($calculate[2],2,',','.')}}</td>
                </tr>
                <tr>
                    <td>Total Debt Balance :</td>
                    <td>{{"Rp " . number_format($calculate[3],2,',','.')}}</td>
                </tr>
            </table>
        </div>
    </div>
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