@extends('layout.index')

@section('page-title','Return Sales Trasaction')
@section('content')

<div class="row">
    <div class="col-md-12" id="entrySales">
        <div class="card">
            <form action="" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-lg-6 col-xl-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Sales Return ID</label>
                                        <input type="text" style="border-radius:3px; font-size:12px" id="invoiceid"
                                            name="invoiceid" value="{{$inv}}" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Return Date</label>
                                        <input type="date" style="border-radius:3px" name="invoiceDate" id="invoiceDate"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Compensation</label><br>
                                        <select name="compensation" id="compensation" class="form-control">
                                            <option value="">-SELECT-</option>
                                            <option value="CASH">CASH</option>
                                            <option value="TERM UNTIL">TERM UNTIL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12" id="termutil" style="display: none;">
                                    <div class="form-group">
                                        <label for="">Term Until</label>
                                        <input type="date" value="{{date('Y-m-d')}}" style="border-radius:3px"
                                            id="term_util" name="term_util"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-6 col-xl-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Sales Invoice ID</label>
                                        <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="idsalesinv" id="idsalesinv">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" onclick="serch()" type="button"><i class="glyphicon glyphicon-search"></i></button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Salesman ID</label>
                                        <input type="text" class="form-control" style="border-radius:3px" readonly id="salesid">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Invoice Type</label>
                                        <input type="text" readonly style="border-radius:3px" class="form-control"
                                            name="invtype" id="invtype">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Customer ID</label>
                                        <input type="text" class="form-control" style="border-radius:3px"  readonly id="customerid">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Customer Nama</label>
                                        <input type="text" readonly style="border-radius:3px" class="form-control"
                                            name="namacustomer" id="namacustomer">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="">Pay Status</label>
                                    <input name="" id="paystatus" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-6 col-xl-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button style="margin-top:28px" type="button"
                                            class="btn btn-success btn-sm" onclick="stokcari('{{Session()->get('cabang')}}')">Stok To Return</button>
                                        <input type="hidden" id="stok_id">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Product ID</label>
                                        <input type="text" style="border-radius:3px" id="produkid" name="produkid"
                                            readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Product Type</label>
                                        <input type="text" style="border-radius:3px" id="produktype" name="produktype" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Product Brand</label>
                                        <input type="text" style="border-radius:3px" id="produkbrand" name="produkbrand" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Product Name</label>
                                        <input type="text" style="border-radius:3px" id="produknama" name="produknama" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-6 col-xl-5">
                            <div class="card">
                                <div class="card-body">
                                    <b>Quantity</b>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div id="isi1"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div style="font-size: 10px;" class="input-group-text">Selling Price</div>
                                                </div>
                                                <input type="text" class="form-control" readonly id="prices">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div style="font-size: 10px;" class="input-group-text">Discount</div>
                                                </div>
                                                <input type="text" class="form-control" value="0" onkeyup="diskont(this)" id="diskon">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div style="font-size: 10px;" class="input-group-text">Final Selling Price</div>
                                                </div>
                                                <input type="text" class="form-control" readonly id="finalselling">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div style="font-size: 10px;" class="input-group-text">Note Return</div>
                                                </div>
                                                <select name="notereturn" class="form-control" id="note">
                                                    <option value="">-SELECT-</option>
                                                    <option value="BROKEN PRODUCT">BROKEN PRODUCT</option>
                                                    <option value="EXPIRED PRODUCT">EXPIRED PRODUCT</option>
                                                    <option value="MISMATCH PRODUCT">BMISMATCHPRODUCT</option>
                                                    <option value="UNSOLD PRODUCT">BROUNSOLDODUCT</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card my-5">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6" style="text-align: center;">
                                            <button type="button" style="width:140px;" onclick="add()" class="btn btn-outline-success">Add</button>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <button type="button" style="width:140px;" class="btn btn-outline-danger">Remove All</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body" id="isibody"></div>
                    <div>
                        <div class="row" style="margin-left: 10px; margin-right:10px; margin-bottom:10px;">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <table border="0" style="width: 100%;">
                                            <tr>
                                                <td>Total Return</td>
                                                <td style="width: 10px;">:</td>
                                                <td colspan="3"><input type="text" id="totalsales" class="form-control">
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="hidden" id="metode">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body" style="background-color: grey;">
                                        <h5 style="color: white;" id="terbilang"></h5>
                                    </div>
                                </div>
                                <br>
                                <!--<div id="printt" style="display:none">-->
                                <!--    <input type="checkbox" id="print">&nbsp;<i>Print Invoice</i>-->
                                <!--</div>-->
                                <br>
                                <button type="button" onclick="register()" class="btn btn-outline-success">Register
                                    Transaction </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="caridata" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color:grey">
        <h4 class="modal-title" style="color:white" >List Sales Transaction</h4>
      </div>
      <div class="modal-body">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="email">View By:</label>
                                <Select class="select2" name="view" id="view" style="width:100%">
                                    <option value="">-SELECT-</option>
                                    <option value="ALL">ALL TRANSACTION</option>
                                    <option value="CANVASING">CANVAS</option>
                                    <option value="TAKING ORDER">TAKING ORDER</option>
                                    <option value="CUSTOMER">CUSTOMER</option>
                                </Select>
                            </div>
                            <div class="checkbox">
                                <label><input style="margin-left:50px" onclick="alls()" type="radio" name="radio"> All Invoice</label>
                            </div>
                            <div class="checkbox">
                                <label><input style="margin-left:50px" type="radio" onclick="range()" name="radio"> Range Invoice</label>
                            </div>
                            <input type="hidden" id="tipe">
                        </form>
                    </div>
                    <div class="col-md-12">
                        <form class="form-inline">
                            <div class="form-group">
                                <label for="email">Search By:</label>
                                <Select class="select2" name="serch" id="serch" style="width:100%">
                                    <option value="">-SELECT-</option>
                                    <option value="ALL">ALL REPORT</option>
                                    <option value="INVOICE ID">INVOICE ID</option>
                                    <option value="INVOICE TYPE">INVOICE TYPE</option>
                                    <option value="SALESMAN">SALESMAN</option>
                                    <option value="CUSTOMER">CUSTOMER</option>
                                </Select>
                            </div>
                            <div class="checkbox">
                                <label>&nbsp;</label>
                                <input style="margin-left:50px" class="form-control" onkeyup="cari1(this)" type="text" name="filter">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12 py-3" id="datadicari"></div>
                </div>
            </div>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="modal" tabindex="-1" id="tmpmodal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stok Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" id="datastoktampil">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function(){
        
        $('#isibody').load("{{ route('tmpdata')}}")
        
    })

    // $('#compensation').change(function(){
    //     var isi = $('#compensation').val()
    //     if(isi == 'TERM UNTIL')
    //     {
    //         $('#printt').hide()
    //         document.getElementById('print').checked = false
    //     }else if(isi == 'CASH'){
    //         $('#printt').show()
    //         document.getElementById('print').checked = true
    //     }
    // })

    function stokcari(cabang)
    {
        axios.get("{{url('/api/tampilstok')}}/"+cabang).then(function(res){
            var data = res.data
            $('#tmpmodal').modal()
            $('#datastoktampil').html(data)
        })
    }

    function diskont(nilai)
    {
        var disc = nilai.value
        var amn = convertToAngka($('#prices').val())
        var hasil = amn - disc
        $('#finalselling').val(convertToRupiah(hasil))
    }

    function register()
    {
        var invoiceid = $('#invoiceid').val()   
        var invoicedate = $('#invoiceDate').val()   
        var compensation = $('#compensation').val()   
        var term_util = $('#term_util').val()   
        var idsalesinv = $('#idsalesinv').val()  
        var diskon = $('#diskon').val() 
        var totalsales = convertToAngka($('#totalsales').val())   
        var id_user = "{{Session()->get('id')}}"
        axios.post("{{url('/api/rekaptransaksir')}}",{
            'invoiceid':invoiceid,
            'invoicedate':invoicedate,
            'compensation':compensation,
            'term_util':term_util,
            'idsalesinv':idsalesinv,
            'totalsales':totalsales,
            'diskon':diskon,
            'id_user':id_user
        }).then(function(res){
            var data = res.data
            if(data.status == 200){
                // var check = document.getElementById('print').checked;
                // if(check == true)
                // {
                //     // window.open("{{url('/sales_transaksi/fakturs')}}/"+data.invoice_id+"/"+salestype, '_blank');
                //     window.location.reload()
                // }else{
                //     window.location.reload()
                // }
                window.location.reload()
            }
            
        })
    }

    function add()
    {
        
        var id_user = "{{Session()->get('id')}}"
        var invoiceid = $('#invoiceid').val()
        var stockId = $('#stok_id').val()
        var note = $('#note').val()
        var prices = convertToAngka($('#finalselling').val())
        var count = $('#totals').val()
        // cek diskon kosong atau tidak
        var discount = $('#diskon').val()
       // cek amount
       var amou = $('#amount').val()
            if (amou == '') {
                var amount = 0
            } else {
                var amount = amou
            }
            // hitung jumlah
            if ($('#jumlah1').val() == '0') {
                var jumlah1 = '0'
            } else {
                var jumlah1 = $('#jumlah1').val()
            }
            if ($('#jumlah2').val() == '0') {
                var jumlah2 = '0'
            } else {
                var jumlah2 = $('#jumlah2').val()
            }
            if ($('#jumlah3').val() == '0') {
                var jumlah3 = '0'
            }else{
                var jumlah3 = $('#jumlah3').val()
            }
            // cari nilai unit

            if(count == 1){
                var uni1 = parseInt($('#pecah1').val())
                var hargasatuan = prices / uni1
                var total = parseInt(jumlah1 * uni1)
            }else if(count == 2){
                var uni1 = parseInt($('#pecah1').val())
                var uni2 = parseInt($('#pecah2').val())
                var hargasatuan = prices / uni2
                var total = parseInt(jumlah1 * uni1) + parseInt(jumlah2 * uni2)
            }else if(count == 3){
                var uni1 = parseInt($('#pecah1').val())
                var uni2 = parseInt($('#pecah2').val())
                var uni3 = parseInt($('#pecah3').val())
                var hargasatuan = prices / uni3
                var total = parseInt(jumlah1 * uni1) + parseInt(jumlah2 * uni2) + parseInt(jumlah3 * uni3)
            }
        axios.post("{{url('/api/addkeranjangr')}}",{
            'invoiceid':invoiceid,
            'id_stok':stockId,
            'prices':prices,
            'disc':discount,
            'quantity':total,
            'note':note,
            'id_user':id_user,
            'hargasatuan':hargasatuan,
        }).then(function(res){
            data = res.data
            if(data.status == 200){
                toastr.info('Tersimpan')
                $('#isibody').load("{{ route('tmpdata')}}")
                kosong()
            }
        }).catch(function(err){
            toastr.warning('Periksa Kembali')
        })
    }

    function kosong()
    {
        $("#stockId").val('').trigger('change')
        $('#produktype').val('')
        $('#produkbrand').val('')
        $('#produknama').val('')
        $('#prices').val('')
        $('#finalselling').val('')
        $('#isi1').html('')
        $('#isi2').html('')
    }

    $('#compensation').change(function(){
        var cek = $(this).val()
        if(cek == 'TERM UNTIL')
        {
            $('#termutil').show()
        }else{
            $('#termutil').hide()
        }
    })

   function pilihstok(stokid){
        var customer = $('#customerid').val()
        var cabang = "{{session()->get('cabang')}}"
        axios.post("{{url('/api/getProduk')}}",{
            'id_stok': stokid,
            'cabang': cabang
        }).then(function(res){
            var data = res.data.data
            stok(data.stok_id,cabang)
            $('#stok_id').val(data.stok_id)
            $('#produkid').val(data.produk_id)
            $('#produktype').val(data.nama_type_produk)
            $('#produkbrand').val(data.produk_brand)
            $('#produknama').val(data.produk_nama)
            $('#prices').val(convertToRupiah(data.capital_price))
            $('#finalselling').val(convertToRupiah(data.capital_price))
            $('#tmpmodal').modal('hide')
        }).catch(function(err){
            console.log(err)
        })
    }

    function stok(id, cabang) {
            axios.post("{{url('/api/cekstok')}}", {
                'stok_id': id,
                'cabang': cabang
            }).then(function (res) {
                var data = res.data
                $('#isi1').html(data)
            }).catch(function (err) {
                console.log(err)
            })
        }

    function alls()
    {
        $('#tipe').val('all')
    }

    function range()
    {
        $('#tipe').val('range')
    }

    function serch()
    {
        $('#caridata').modal()
    }

    function cari1(cari)
    {
        var nama = cari.value
        var serch = $('#serch').val()
        var view = $('#view').val()
        var tipe = $('#tipe').val()

        axios.get("{{url('/sales_transaksi/showreturdetail')}}/"+nama+"/"+serch+"/"+view+"/"+tipe).then(function(res){
            var data = res.data
            $('#datadicari').html(data)
        })
    }
</script>
@endsection