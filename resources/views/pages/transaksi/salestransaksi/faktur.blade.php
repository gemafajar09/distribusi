<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{asset('/assets/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
        <div class="text-left">
            <h5>SUMBER CAHAYA REZEKI</h5>
            <h6>Jl. BY PASS TALUAK</h6>
            <h6>BUKITTINGGI, INDONESIA</h6>
            <H6>PHONE : 085375715757 FAX : (0752) 8810863</H6>
            <br>
            <p>Sales : {{$sales['nama_sales']}}</p>
        </div>
        </div>
        
        <div class="col-sm-4">
        <div class="text-center">   
            <h5><b>TRANSAKSI SALES<b></h5>
            <hr style="border-top:5px solid black; width:300px;">
            <h6>Invoice ID : {{$inv}}</h6>
            <div class="row mb-2">
                <div class="col-sm-5 offset-1">
                    <table border="2" style="width: 140px;">
                        <tr>
                            <td>Invoice Date</td>
                        </tr>
                        <tr>
                            <td>{{date('Y-m-d')}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6" >
                    <table border="2"style="width: 140px;">
                        <tr>
                            <td>Invoice Type</td>
                        </tr>
                        <tr>
                            <td>Cash</td>
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
                            <td>ADMIN1</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table border="2" style="width: 140px;">
                        <tr>
                            <td>TERM</td>
                        </tr>
                        <tr>
                            <td>{{$sales['invoice_date']}}</td>
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
            <td>Price</td>
            <td>Total</td>
            <td>Diskon</td>
            <td>Amount</td>
        </tr>
    </thead>
    <tbody>
        <?php $tot = 0; ?>
        @foreach($init as $d)
        <?php $tot += $d['amount'] ?>
        <tr>
            <td>{{$d['produk_nama']}}</td>
            <td>{{$d['quantity']}}</td>
            <td>{{$d['produk_harga']}}</td>
            <td>{{$d['total']}}</td>
            <td>{{$d['diskon']}}</td>
            <td>{{number_format($d['amount'])}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
    <br>
    <div class="row">
        <div class="col-sm-7">
            <p>Note : {{$sales['note']}}</p>
            <hr style="border-top: 5px solid black;">
            <div class="row mb-5">
                <div class="col-sm-8">
                    <p class="ml-4">Prepared By</p>
                </div>
                <div class="col-sm-4">
                    <p class="ml-3">Approved By</p>
                </div>
            </div>
            <div class="row">
            <div class="col-sm-8">
                    <p>............................</p>
                    <p>Date : {{date('d-m-Y')}}</p>
                </div>
                <div class="col-sm-4">
                <p>............................</p>
                    <p>Date : </p>
                </div>
            </div>
        </div>
        <div class="col-sm-5 mt-5">
            <table  border="2" style="width: 100%;">
                <tr>
                    <td style="border-bottom: none;">Total Purchase :</td>
                    <td>{{number_format($tot)}}</td>
                </tr>
                <tr>
                    <td>Final Discount :</td>
                    <td>{{number_format($sales['diskon'])}}</td>
                </tr>
                <tr>
                    <td>Total After Discount :</td>
                    <td>{{number_format($tot - $sales['diskon'])}}</td>
                </tr>
                <tr>
                    <td>Down Payment :</td>
                    <td>{{number_format($sales['dp'])}}</td>
                </tr>
                <tr>
                    <td>Total Debt Balance :</td>
                    <td>{{number_format(($tot - $sales['diskon']) - $sales['dp'])}}</td>
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
            window.print()
    });
    </script>
</body>
</html>
