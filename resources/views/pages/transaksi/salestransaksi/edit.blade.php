@extends('layout.index')

@section('page-title','Edit Sales Trasaction')
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
                                        <label for="">Sales Type</label>
                                        <select name="salesType" id="salesType" class="select2" style="width:100%">
                                            <option value="">SALES TYPE</option>
                                            <option value="CUSTOMER">CUSTOMER</option>
                                            <option value="TAKING ORDER">TAKING ORDER</option>
                                            <option value="CANVASING">CANVASING</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Invoice ID</label>
                                        <input type="text" style="border-radius:3px; font-size:12px" id="invoiceid"
                                            name="invoiceid" value="" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Invoice Date</label>
                                        <input type="date" style="border-radius:3px" name="invoiceDate" id="invoiceDate"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Transaction Type</label><br>
                                        <input type="radio" id="radiocash" style="border-radius:3px" name="transType"
                                            placeholder="transType" value="Cash"> <b>Cash</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" id="radiocredit" style="border-radius:3px" name="transType"
                                            value="Credit" placeholder="transType"> <b>Credit</b>
                                    </div>
                                </div>
                                <div class="col-md-12" id="termutil" style="display: none;">
                                    <div class="form-group">
                                        <label for="">Term Until</label>
                                        <input type="date" value="{{date('Y-m-d')}}" style="border-radius:3px"
                                            id="term_util" name="term_util" placeholder="invoiceDate"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-6 col-xl-2">
                            <div class="row">
                                <div class="col-md-12" id="warehouse" style="display: none;">
                                    <div class="form-group">
                                        <label for="">Warehouse ID</label>
                                        <select name="warehouse" id="warehouse" class="select2" style="width:100%">
                                            <option value="">Warehouse ID</option>
                                            @foreach($warehouse as $i => $gud):
                                            <option value="{{$gud->id_gudang}}">{{$gud->nama_gudang}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12" id="tampilsales">
                                    <div class="form-group">
                                        <label for="">Salesman ID</label>
                                        <select name="salesmanId" id="salesmanId" class="select2" style="width:100%">
                                            <option value="">Sales ID</option>
                                            @foreach($salesid as $i => $sales):
                                            <option value="{{$sales->id_sales}}">{{$sales->id_sales}} |
                                                {{$sales->nama_sales}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12" id="tampilsales1">
                                    <div class="form-group">
                                        <label for="">Salesman Nama</label>
                                        <input type="text" readonly style="border-radius:3px" class="form-control"
                                            name="namaSales" id="namasales">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Customer ID</label>
                                        <select name="customerid" id="customerid" class="select2" style="width:100%">
                                            <option value="">Customer ID</option>
                                            @foreach($customerid as $i => $customer):
                                            <option value="{{$customer->id_customer}}">{{$customer->id_customer}} |
                                                {{$customer->nama_customer}}</option>
                                            @endforeach
                                        </select>
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
                                    <label for="">Note</label>
                                    <textarea name="" id="note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-lg-6 col-xl-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">

                                        <button style="margin-top:28px" type="button"
                                            class="btn btn-success btn-sm" onclick="stokcari('{{Session()->get('cabang')}}')">Cari stok</button>
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
                                        <input type="text" style="border-radius:3px" id="produktype" name="produktype"
                                            readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Product Brand</label>
                                        <input type="text" style="border-radius:3px" id="produkbrand" name="produkbrand"
                                            readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Product Name</label>
                                        <input type="text" style="border-radius:3px" id="produknama" name="produknama"
                                            readonly class="form-control">
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

                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Price</div>
                                                </div>
                                                <input type="text" class="form-control" id="prices">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Discount</div>
                                                </div>
                                                {{-- <input type="text" class="form-control" onkeyup="diskon(this)"
                                                    id="discount"> --}}
                                                {{-- <div class="input-group-prepend">
                                                    <div class="input-group-text">%</div>
                                                </div> --}}
                                                <div class="input-group-prepend">
                                                    <input type="text" class="form-control" id="amount">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card my-5">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6" style="text-align: center;">
                                            <button type="button" style="width:140px;" onclick="add()"
                                                class="btn btn-outline-success">Add</button>
                                        </div>
                                        <div class="col-md-6" style="text-align: center;">
                                            <button type="button" style="width:140px;"
                                                class="btn btn-outline-danger">Remove All</button>
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
                                                <td>Total Sales</td>
                                                <td style="width: 10px;">:</td>
                                                <td colspan="3"><input type="text" id="totalsales" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Final Discount</td>
                                                <td style="width: 10px;">:</td>
                                                {{-- <td style="width: 60px;"><input onkeyup="disco(this)" id="discon"
                                                        type="text" class="form-control"></td>
                                                <td style="width: 10px;">%</td> --}}
                                                <td><input type="text" onkeyup="potong(this)" id="potongan"
                                                        class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>Total After Discount</td>
                                                <td style="width: 10px;">:</td>
                                                <td colspan="3"><input type="text" id="afterdiscount"
                                                        class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>Down Payment</td>
                                                <td style="width: 10px;">:</td>
                                                <td colspan="3"><input type="text" onkeyup="dp(this)" id="downpayment"
                                                        class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>Total Credit Balance</td>
                                                <td style="width: 10px;">:</td>
                                                <td colspan="3"><input type="text" id="creditbalance"
                                                        class="form-control"></td>
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
                                <div id="printt" >
                                    <input checked type="checkbox" id="print">&nbsp;<i>Print Invoice</i>
                                </div>
                                <br>
                                <button type="button" onclick="registerulang()" class="btn btn-outline-success">Register
                                    Transaction </button>
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
    function stokcari(cabang)
    {
        axios.get("{{url('/api/tampilstok')}}/"+cabang).then(function(res){
            var data = res.data
            console.log(data)
            $('#tmpmodal').modal()
            $('#datastoktampil').html(data)
        })
    }

        function dp(nilai) {
            var after = convertToAngka($('#afterdiscount').val())
            var hasil = after - nilai.value
            $('#creditbalance').val(convertToRupiah(hasil))
        }

    //     $('#salesType').change(function () {
    //         var body = $(this).val()
    //         var user = "{{Session()->get('id')}}"
    //         if (body == 'CUSTOMER') {
    //             axios.post("{{url('/api/invoice')}}", {
    //                 'type': body,
    //                 'user': user
    //             }).then(function (res) {
    //                 var data = res.data
    //                 $('#invoiceid').val(data.inv)
    //                 $('#tampilsales').hide()
    //                 $('#tampilsales1').hide()
    //                 $('#warehouse').hide()
    //             })
    //         } else if (body == 'TAKING ORDER') {
    //             axios.post("{{url('/api/invoice')}}", {
    //                 'type': body,
    //                 'user': user
    //             }).then(function (res) {
    //                 var data = res.data
    //                 $('#invoiceid').val(data.inv)
    //                 $('#tampilsales').show()
    //                 $('#tampilsales1').show()
    //             })
    //         } else if (body == 'CANVASING') {
    //             axios.post("{{url('/api/invoice')}}", {
    //                 'type': body,
    //                 'user': user
    //             }).then(function (res) {
    //                 var data = res.data
    //                 $('#invoiceid').val(data.inv)
    //                 $('#tampilsales').show()
    //                 $('#tampilsales1').show()
    //                 $('#warehouse').show()
    //             })
    //         }
    //     })

        function registerulang() {
            var id_cabang = '{{session()->get('cabang')}}'
            var salestype = $('#salesType').val()
            var invoiceid = $('#invoiceid').val()
            var invoicedate = $('#invoiceDate').val()
            var radiocash = $('#metode').val()
            var term_util = $('#term_util').val()
            var warehouse = $('#warehouse').val()
            var salesmanid = $('#salesmanId').val()
            var customerid = $('#customerid').val()
            var note = $('#note').val()
            var discon = convertToAngka($('#potongan').val())
            var dp = convertToAngka($('#downpayment').val())
            var totalsales = convertToAngka($('#totalsales').val())
            var id_user = "{{Session()->get('id')}}"
            axios.post("{{url('/api/registransaksi')}}", {
                'salestype': salestype,
                'invoiceid': invoiceid,
                'invoicedate': invoicedate,
                'radiocash': radiocash,
                'term_util': term_util,
                'salesmanid': salesmanid,
                'customerid': customerid,
                'warehouse': warehouse,
                'note': note,
                'totalsales': totalsales,
                'potongan': discon,
                'dp': dp,
                'id_user': id_user
            }).then(function (res) {
                var data = res.data
                if (data.status == 200) {
                    var check = document.getElementById('print').checked;
                    if (check == true) {
                        window.open("{{url('/sales_transaksi/fakturs')}}/" + data.invoice_id + "/" + salestype+ "/"+id_cabang,
                            '_blank');
                        window.location = '/sales_transaksi/report';
                    } else {
                        window.location = '/sales_transaksi/report';
                    }
                }

            })
        }

    //     // function disco(nilai) {
    //     //     var persen = nilai.value
    //     //     var tot = convertToAngka($('#totalsales').val())
    //     //     var hasil = tot - ((tot * persen) / 100)
    //     //     $('#potongan').val(convertToRupiah((tot * persen) / 100))
    //     //     $('#downpayment').val(convertToRupiah((tot * persen) / 100))
    //     //     $('#creditbalance').val(convertToRupiah(hasil))
    //     //     $('#afterdiscount').val(convertToRupiah(tot - (tot * persen) / 100))

    //     // }

        function potong(potongan) {
            var nilai = potongan.value
            var tot = convertToAngka($('#totalsales').val())
            $('#afterdiscount').val(convertToRupiah(tot - nilai))
        }

    //     // function diskon1(nilai) {
    //     //     var persen = nilai.value
    //     // }

    //     // function diskon(disc) {
    //     //     var diskon = disc.value
    //     //     var harga = convertToAngka($('#prices').val())
    //     //     var totalsemua = (harga * diskon) / 100
    //     //     $('#amount').val(totalsemua)
    //     // }

    //     function show() {
    //         $('#entry').modal()
    //     }

        function hargakhusus(stokid, customer, harga) {
            axios.post("{{url('/api/hargakusus')}}", {
                'id_stok': stokid,
                'customer': customer
            }).then(function (res) {
                var data = res.data
                if (data.status == 404) {
                    $('#prices').val(convertToRupiah(harga))
                } else {
                    $('#prices').val(convertToRupiah(data.data.spesial_nominal))
                }
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

        function kosong() {
            $("#stockId").val('').trigger('change')
            $('#produkid').val(data.produk_id)
            $('#produktype').val('')
            $('#produkbrand').val('')
            $('#produknama').val('')
            $('#prices').val('')
            $('#discount').val('')
            $('#amount').val('')
            $('#total').val('')
            $('#isi1').html('')
            $('#isi2').html('')
        }

        $('#radiocredit').click(function () {
            var radio = document.getElementById('radiocredit').checked
            if (radio == true) {
                $('#termutil').show()
                $('#metode').val('Credit')
                $('#printt').show()
                document.getElementById('print').checked = false
            } else {
                $('#termutil').hide()
            }
        })

        $(document).ready(function () {
            // Munculkan Data
            document.getElementsByName('invoiceid')[0].value = "<?php echo $transaksi->invoice_id ?>";
            document.getElementsByName('invoiceDate')[0].value = "<?php echo $transaksi->invoice_date ?>";
            document.getElementsByName('salesType')[0].value = "<?php echo $transaksi->sales_type ?>";
            document.getElementsByName('customerid')[0].value = "<?php echo $transaksi->customer_id ?>";
            document.getElementsByName('namacustomer')[0].value = "<?php echo $transaksi->nama_customer ?>";

            // DataTable Keranjang
            $('#isibody').load("{{ route('datatableskeranjang')}}")

            // Sales Type
            var body = "<?php echo $transaksi->sales_type ?>"
            console.log(body);
            var user = "{{Session()->get('id')}}"
            if (body == 'CUSTOMER') {
                    $('#tampilsales').hide()
                    $('#tampilsales1').hide()
                    $('#warehouse').hide()
                    document.getElementsByName('customerid')[0].value = "<?php echo $transaksi->customer_id ?>";
                    document.getElementsByName('namacustomer')[0].value = "<?php echo $transaksi->nama_customer ?>";
            } else if (body == 'TAKING ORDER') {
                    $('#tampilsales').show()
                    $('#tampilsales1').show()
                    document.getElementsByName('salesmanId')[0].value = "<?php echo $transaksi->sales_id ?>";
                    document.getElementsByName('namaSales')[0].value = "<?php echo $transaksi->nama_sales ?>";
            } else if (body == 'CANVASING') {
                    document.getElementsByName('warehouse')[0].value = "<?php echo $transaksi->id_warehouse ?>";
                    document.getElementsByName('salesmanId')[0].value = "<?php echo $transaksi->sales_id ?>";
                    document.getElementsByName('namaSales')[0].value = "<?php echo $transaksi->nama_sales ?>";
                    $('#tampilsales').show()
                    $('#tampilsales1').show()
                    $('#warehouse').show()
            }
            // Radio
            var radiocash = "<?php echo $transaksi->transaksi_tipe ?>";
            var radiocredit = "<?php echo $transaksi->transaksi_tipe ?>";
            if(radiocash == 'Cash'){
                $('#radiocash').prop('checked', true)
            }else if(radiocredit == 'Credit'){
                $('#radiocredit').prop('checkced', true)
            }
        });

        $('#radiocash').click(function () {
            var radio = document.getElementById('radiocash').checked
            if (radio == true) {
                $('#termutil').hide()
                $('#metode').val('Cash')
                $('#printt').show()
                document.getElementById('print').checked = true
            } else {
                $('#termutil').show()
            }
        })

        function add() {
            var id_user = "{{Session()->get('id')}}"
            var invoiceid = $('#invoiceid').val()
            var stockId = $('#stok_id').val()
            var produkid = $('#produkid').val()
            var prices = convertToAngka($('#prices').val())
            var count = $('#totals').val()
            // cek diskon kosong atau tidak

            var amount = $('#amount').val()

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
                if (amount == '') {
                var amou = 0
                    } else {
                        var jumlah3 = parseInt($('#jumlah1').val())
                        var amou = amount*jumlah3
                    }
                var uni1 = parseInt($('#pecah1').val())
                var hargasatuan = prices / uni1
                var total = parseInt(jumlah1 * uni1)
            }else if(count == 2){
                if (amount == '') {
                var amou = 0
                    } else {
                        var jumlah3 = parseInt($('#jumlah2').val())
                        var amou = amount*jumlah3
                    }
                var uni1 = parseInt($('#pecah1').val())
                var uni2 = parseInt($('#pecah2').val())
                var hargasatuan = prices / uni2
                var total = parseInt(jumlah1 * uni1) + parseInt(jumlah2 * uni2)
            }else if(count == 3){
                if (amount == '') {
                var amou = 0
                    } else {
                        var jumlah3 = parseInt($('#jumlah3').val())
                        var amou = amount*jumlah3
                    }
                var uni1 = parseInt($('#pecah1').val())
                var uni2 = parseInt($('#pecah2').val())
                var uni3 = parseInt($('#pecah3').val())
                var hargasatuan = prices / uni3
                var total = parseInt(jumlah1 * uni1) + parseInt(jumlah2 * uni2) + parseInt(jumlah3 * uni3)
            }
            axios.post("{{url('/api/tambahkeranjangedit')}}", {
                'invoiceid': invoiceid,
                'stockId': stockId,
                'produkid': produkid,
                'prices': prices,
                'disc': amou,
                'amount': amou,
                'quantity': total,
                'id_user': id_user,
                'satuan': hargasatuan,
            }).then(function (res) {
                data = res.data
                if (data.status == 200) {
                    toastr.info('Success')
                    $('#isibody').load("{{ route('datatableskeranjang')}}")
                    kosong()
                }
            }).catch(function (err) {
                toastr.warning('Periksa Kembali')
            })
        }

    //     $('#salesmanId').change(function () {
    //         var salesid = $(this).val()

    //         axios.post("{{url('/api/getsalestrans')}}", {
    //             'sales_id': salesid
    //         }).then(function (res) {
    //             var data = res.data.data
    //             console.log(data.nama_sales)
    //             $('#namasales').val(data.nama_sales)
    //         }).catch(function (err) {
    //             toastr.warning('Oops Ada Kesalahan')
    //         })
    //     })

    //     $('#customerid').change(function () {
    //         var customerid = $(this).val()

    //         axios.post("{{url('/api/getCustomer')}}", {
    //             'customer_id': customerid
    //         }).then(function (res) {
    //             var data = res.data.data
    //             $('#namacustomer').val(data.nama_customer)
    //         }).catch(function (err) {
    //             toastr.warning('Oops Ada Kesalahan')
    //         })
    //     })

        function pilihstok(stokid) {
            var customer = $('#customerid').val()
            var cabang = "{{session()->get('cabang')}}"
            axios.post("{{url('/api/getProduk')}}", {
                'id_stok': stokid,
                'cabang': cabang
            }).then(function (res) {
                var data = res.data.data
                stok(data.stok_id, cabang)
                $('#stok_id').val(data.stok_id)
                $('#produkid').val(data.produk_id)
                $('#produktype').val(data.nama_type_produk)
                $('#produkbrand').val(data.produk_brand)
                $('#produknama').val(data.produk_nama)
                // cek harga kusus
                hargakhusus(stokid, customer, data.produk_harga)
                console.log(hargakhusus);
                $('#tmpmodal').modal('hide')
            }).catch(function (err) {
                toastr.warning('Oops Ada Kesalahan')
            })
        }
    </script>
    @endsection
