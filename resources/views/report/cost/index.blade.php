@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Cost Report')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
    <div class="col-sm-9 border p-3 mr-3">
        <div class="card-box table-responsive" id="isi">

        </div>
    </div>
    <div class="col border p-3 bg-white rounded">
        <h6>View : </h6>
        <select name="ket_waktu" id="ket_waktu" class="form-control">
            <option value="">Silahkan Pilih</option>
            <option value="0">All Report</option>
            <option value="1">Today</option>
            <!-- <option value="2">Weekly</option> -->
            <option value="3">Monthly</option>
            <option value="4">Yearly</option>
            <option value="5">Range</option>
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
        <div>
            <label for="">Filter By :</label>
            <select name="" id="pilihanfilter" class="form-control">
                <option value="alltransaction">-FILTER BY-</option>
                <option value="idrequester">ID REQUESTER</option>
                <option value="namarequester">NAME REQUESTER</option>
                <option value="costnama">COST NAME</option>
            </select><br>
            <input type="text" class="form-control" id="inputpilihan">
        </div>
        <br>
        <div class="text-center">
            <button class="btn btn-warning btn-sm btn-block" onclick="pilihanfilter()">View Report</button>
            <button class="btn btn-success btn-sm btn-block" onclick="location.reload()">Refresh Report</button>
            <button class="btn btn-danger btn-sm btn-block" onclick="print_report()">Generate Report</button>
        </div>
    </div>
</div>
<script>
    // $(document).ready(function () {
    //     $('#isi').load('{{ route('table_cost')}}')
    // });

        $("#waktu_awal" ).prop( "readonly", true );
        $("#waktu_akhir" ).prop( "readonly", true );
        $('#ket_waktu').change(function(){
            $("waktu_awal").val('0');
            $("waktu_akhir").val('0');
            $("#waktu_awal" ).prop( "readonly", true );
            $("#waktu_akhir" ).prop( "readonly", true );
            var nilai = $('#ket_waktu').val();
            $('#wadah').html('');

            if(nilai ==3){
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
            }else if(nilai ==4){
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
            }else if(nilai ==5){
                $("#waktu_awal" ).prop( "readonly", false);
                $("#waktu_akhir" ).prop( "readonly", false);
            }
            $("#waktu_awal").val("")
            $("#waktu_akhir").val("")
        })

    function pilihanfilter()
    {
        id_cabang = {{session()->get('cabang')}}
        var filterdata = $('#pilihanfilter').val()
        if(filterdata == null)
        {
            filterdata = 0;
        }

        var caridata = $('#inputpilihan').val()
        if(caridata == "")
        {
            caridata = 0;
        }

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

        $('#isi').load("{{ route('table_cost') }}/" + id_cabang + "/" + filterdata + "/" + caridata + "/" + ket_waktu + "/"+  filtertahun +"/" + filterbulan + "/" +filter_tahun + "/" +waktuawal + "/" +waktuakhir)
    }



    function print_report()
    {
        id_cabang = {{session()->get('cabang')}}
        var filterdata = $('#pilihanfilter').val()
        if(filterdata == null)
        {
            filterdata = 0;
        }

        var caridata = $('#inputpilihan').val()
        if(caridata == "")
        {
            caridata = 0;
        }

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

        window.open("{{ url('/cost_report/generate_cost') }}/" + id_cabang + "/" + filterdata + "/" + caridata + "/" + ket_waktu + "/"+  filtertahun +"/" + filterbulan + "/" +filter_tahun + "/" +waktuawal + "/" +waktuakhir);
    }
</script>
@endsection