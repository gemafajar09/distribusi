@extends('layout.index')
@section('page-title','')
@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel fixed_height_100">
                    <div class="x_title">
                        <h2>Approval</h2>
                        
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-sm-5">
                                <i class="fa fa-clipboard fa-5x"></i>
                            </div>
                            <div class="col-sm-7">
                                <a href="{{ route('aproval_purchase_order') }}" class="btn btn-success btn-lg">Purchase</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="x_panel fixed_height_100">
                    <div class="x_title">
                        <h2>Approval</h2>
                        
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-sm-5">
                                <i class="fa fa-clipboard fa-5x"></i>
                            </div>
                            <div class="col-sm-7">
                                <a href="{{ route('approvesales') }}" class="btn btn-success btn-lg btn-block">Sales</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="x_panel fixed_height_100">
                    <div class="x_title">
                        <h2>Approval</h2>
                        
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-sm-5">
                                <i class="fa fa-clipboard fa-5x"></i>
                            </div>
                            <div class="col-sm-7">
                                <a href="{{ route('aprovalopname') }}" class="btn btn-success btn-lg btn-block">Opname</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="x_panel">
            <div class="x_title">
                <h2>REKAP TRANSAKSI</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li class="mr-2"><select name="" id="waktu" class="form-control">
                            <option value="">Pilih</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </li>
                    <li>
                        <button class="btn btn-danger" onclick="print_report()">Export PDF</button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card text-white rounded">
                            <div class="card-body" style="background-color:#f7e363;">
                                <div class="row">
                                <div class="col-sm-3"><i class="fa fa-shopping-cart fa-5x" style="margin-right:20px;"></i></div>
                                <div class="col-sm-9">
                                <h3 style="display: inline;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">Total Pembelian</h3>
                                <h3 id="total_pembelian" class="text-secondary" style="font-size:20px ;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;"></h3>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-white rounded">
                            <div class="card-body" style="background-color:#f7e363;">
                                <div class="row">
                                <div class="col-sm-3"><i class="fa fa-truck fa-5x" style="margin-right:20px;"></i></div>
                                <div class="col-sm-9">
                                <h3 style="display: inline;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">Total Penjualan</h3>
                                <h3 id="total_penjualan" class="text-secondary" style=" font-size:20px ;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;"></h3>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="card text-white rounded">
                            <div class="card-body" style="background-color:#f7e363;">
                                <div class="row">
                                <div class="col-sm-3"><i class="fa fa-home fa-5x" style="margin-right:20px;"></i></div>
                                <div class="col-sm-9">
                                <h3 style="display: inline;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">Stok In Price</h3>
                                <h3 id="stok_price" class="text-secondary" style=" font-size:20px ;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;"></h3>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="card text-white rounded">
                            <div class="card-body" style="background-color:#f7e363;">
                                <div class="row">
                                <div class="col-sm-3"><i class="fa fa-dollar fa-5x" style="margin-right:20px;"></i></div>
                                <div class="col-sm-9">
                                <h3 style="display: inline;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;">Profit Cabang</h3>
                                <h3 id="profit_cabang" class="text-secondary" style=" font-size:20px ;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;"></h3>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>


<script>
    $(document).ready(function() {
        id_cabang = {{session()->get('cabang')}}
        var now = new Date();
        bulan = now.getMonth() + 1;
        load_data(id_cabang,bulan);

        $('#waktu').on('change',function(){
            var waktu = $('#waktu').val();
            console.log(waktu)
            load_data(id_cabang,waktu)
        })

    });

    function load_data(id_cabang,bulan){
        axios.get('{{url('/api/totalpembelian/')}}/'+id_cabang+'/'+bulan)
            .then(function(res) {
                isi = res.data;
                $('#total_pembelian').html(isi.data)
            });

        axios.get('{{url('/api/totalpenjualan/')}}/' +id_cabang+'/'+bulan)
            .then(function(res) {
                isi = res.data;
                $('#total_penjualan').html(isi.data)
            });

        axios.get('{{url('/api/stokprice/')}}/' + id_cabang)
            .then(function(res) {
                isi = res.data;
                $('#stok_price').html(isi.data)
            });

            axios.get('{{url('/api/profit/')}}/' + id_cabang+'/'+bulan)
            .then(function(res) {
                isi = res.data;
                $('#profit_cabang').html(isi.data)
            });
    }
    
    function print_report(){
        id_cabang = {{session()->get('cabang')}}
        waktu = $('#waktu').val()
        if(waktu == ""){
                return false;
        }
        window.open(`{{url('/rekap/rekap_transaksi/')}}/`+id_cabang+'/'+waktu);
        
    }
</script>
<!-- /page content -->
@endsection