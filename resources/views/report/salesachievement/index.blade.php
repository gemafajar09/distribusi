@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Salesman Achievement Report')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col border p-3 mr-3 bg-white rounded">
        <h6>Salesman :</h6>
        <select name="" id="data_salesman" class="form-control">
            <option value="">-SALESMAN-</option>
            @foreach($data as $a)
            <option value="{{$a->id_sales}}">{{$a->nama_sales}}</option>

            @endforeach
        </select>
        <br>
        <div class="card-body bg-dark text-white text-center text-uppercase" id="terbilang">
            <span id="target_sales" class="text-white"></span>
        </div>
        <br>
        <h6>View : </h6>
        <select name="ket_waktu" id="ket_waktu" class="form-control">
            <option value="">Silahkan Pilih</option>
            <option value="0">All Report</option>
            <option value="1">Today</option>
            <option value="2">Monthly</option>
            <option value="3">Yearly</option>
            <option value="4">Range</option>
        </select>
        <br>
        <div id="wadah"></div>
        <br>
        <div class="range" id="range">
            <legend>Range</legend>
            <input type="date" class="form-control" id="waktu_awal"><br>
            <input type="date" class="form-control" id="waktu_akhir">
        </div>
        <br>
        <div class="text-center">
            <button class="btn btn-success btn-sm btn-block" onclick="refresh()">Refresh Report</button>
            <button class="btn btn-danger btn-sm btn-block" id="generate">Generate Report</button>
        </div>
        <div class="mb-2">
            <label for="">Filter By :</label>
            <select name="" id="select" class="form-control">
                <option value="alltransaction">-FILTER BY-</option>
                <option value="0">ALL TRANSACTION</option>
                <option value="1">TAKING ORDER TRANSACTION</option>
                <option value="2">CASH TRANSACTION</option>
                <option value="3">CREDIT TRANSACTION</option>
            </select>
        </div>
        <button class="btn btn-sm btn-outline-secondary" style="display: none" id="credit">CREDIT DETAILS</button>
        <br>
        <div class="btn-group-vertical d-flex justify-content-center" role="group" aria-label="Basic example">
            <button type="button" onclick="allstock()" class="btn btn-outline-secondary">View All Stock</button>
            <button type="button" onclick="tostock()" class="btn btn-outline-secondary">View TO Stock</button>
            <button type="button" onclick="canvasstock()" class="btn btn-outline-secondary">View Canvas Stock</button>
        </div>
    </div>
    <div class="col-sm-9 border p-3">
        <div class="card-box table-responsive" id="isitable">

        </div>
    </div>
</div>

<!-- Modal  Payment-->
<div class="modal fade" id="modalpayment" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div id="detailpayment"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Keluar</button>
            </div>
        </div>
    </div>
</div>
<script>
    var cabang = {{ session()->get('cabang')}};

    // $(document).ready(function () {
    //     $('#isitable').load('{{route('tablecredit')}}')
    // });

    $('#data_salesman').change(function(){
        var id_sales = $(this).val()
        console.log(id_sales);
        axios.get("{{url('/api/sales_achievement/ambiltarget')}}/"+id_sales)
        .then(function(res){
            var data = res.data
            document.getElementById("target_sales").innerHTML = data.target;
        })
    })

    $('#generate').click(function () {
        var filtertahun = $('#year').val()
        if(filtertahun == null)
        {
            filtertahun = 0;
        }

        var filterbulan = $('#month').val()
        if(filterbulan == null)
        {
            filterbulan = 0;
        }

        var filter_tahun = $('#year_filter').val()
        if(filter_tahun == null)
        {
            filter_tahun = 0;
        }

        var waktuawal = $('#waktu_awal').val()
        if(waktuawal == "")
        {
            waktuawal = 0;
        }

        var waktuakhir = $('#waktu_akhir').val()
        if(waktuakhir == "")
        {
            waktuakhir = 0;
        }
        var ket_waktu = $('#ket_waktu').val();
        var id_sales = $('#data_salesman').val()
        var select = $('#select').val()
        console.log(id_sales);
        axios.get("{{url('/api/sales_achievement/transaksisalesachievement')}}/"+id_sales + "/" +select+ "/" + ket_waktu + "/"+  filtertahun +"/" + filterbulan + "/" +filter_tahun + "/" +waktuawal + "/" +waktuakhir)
        .then(function(res){
            var data = res.data
            console.log(data)
            // document.getElementById("target_sales").innerHTML = data.target;
            $('#isitable').load('{{ route('table_achievement')}}/' + id_sales + "/" +select+ "/" + ket_waktu + "/"+  filtertahun +"/" + filterbulan + "/" +filter_tahun + "/" +waktuawal + "/" +waktuakhir)
        })
    });

    function allstock()
    {
    var id_cabang = '{{session()->get('cabang')}}'
    window.open(`{{url('/sales_achievement/report_all_stock')}}/` + id_cabang);
    }

    function tostock()
    {
    var id_cabang = '{{session()->get('cabang')}}'
    window.open(`{{url('/sales_achievement/report_to_stock')}}/` + id_cabang);
    }

    function canvasstock()
    {
    var id_cabang = '{{session()->get('cabang')}}'
    window.open(`{{url('/sales_achievement/report_canvas_stock')}}/` + id_cabang);
    }

    $("#waktu_awal" ).prop( "readonly", true );
        $("#waktu_akhir" ).prop( "readonly", true );
        $('#ket_waktu').change(function(){
            $("waktu_awal").val('0');
            $("waktu_akhir").val('0');
            $("#waktu_awal" ).prop( "readonly", true );
            $("#waktu_akhir" ).prop( "readonly", true );
            nilai = $('#ket_waktu').val();
            $('#wadah').html('');

            if(nilai ==2){
                $('#wadah').append(`<select name="year" id="year" class="form-control">
                <?php
                    $year = date('Y');
                    $min = $year - 10;
                    $max = $year;
                    for( $i=$max; $i>=$min; $i-- ) {
                    echo '<option value='.$i.'>'.$i.'</option>';
                    }
                ?>
                </select><br> <select class="form-control" name="month" id="month"><option>Pilih Bulan</option>
                <?php for( $m=1; $m<=12; ++$m ) {
                    $month_label = date('F', mktime(0, 0, 0, $m, 1));
                ?>
                    <option value="<?php echo $m; ?>"><?php echo $month_label; ?></option>
                <?php } ?>
                    </select>  `);
            }else if(nilai ==3){
                $('#wadah').append(`<select name="year_filter" id="year_filter" class="form-control"><option>Pilih Tahun</option>
                <?php
                    $year = date('Y');
                    $min = $year - 10;
                    $max = $year;
                    for( $i=$max; $i>=$min; $i-- ) {
                    echo '<option value='.$i.'>'.$i.'</option>';
                    }
                ?>
                </select>`);
            }else if(nilai ==4){
                $("#waktu_awal" ).prop( "readonly", false);
                $("#waktu_akhir" ).prop( "readonly", false);
            }
        })

    $('#select').change(function ()
    {
        var pilih = $(this).val()
        console.log(pilih);
        if(pilih == 3)
        {
            $('#credit').show()
        }
        else
        {
            $('#credit').hide()
        }
    });

    $('#credit').click(function ()
    {
        var filtertahun = $('#year').val()
        if(filtertahun == null)
        {
            filtertahun = 0;
        }

        var filterbulan = $('#month').val()
        if(filterbulan == null)
        {
            filterbulan = 0;
        }

        var filter_tahun = $('#year_filter').val()
        if(filter_tahun == null)
        {
            filter_tahun = 0;
        }

        var waktuawal = $('#waktu_awal').val()
        if(waktuawal == "")
        {
            waktuawal = 0;
        }

        var waktuakhir = $('#waktu_akhir').val()
        if(waktuakhir == "")
        {
            waktuakhir = 0;
        }
        var ket_waktu = $('#ket_waktu').val();
        var id_sales = $('#data_salesman').val();
        $('#modalpayment').modal('show')
        $('#detailpayment').load('{{route('tablecredit')}}/' + id_sales + "/" + ket_waktu + "/"+  filtertahun +"/" + filterbulan + "/" +filter_tahun + "/" +waktuawal + "/" +waktuakhir)
    });

    function refresh()
    {
        window.location.reload()
    }
</script>
@endsection
