@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Purchase Order Transaction')
<!-- Page Content -->
@section('content')

    <div class="x_content">
        <div class="row bg-white p-3 rounded mb-3" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-sm-3">
                            <label for="">Invoice ID</label>
                            <input type="text" class="form-control" id="invoice_id" readonly>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="">Invoice Date</label>
                            <input type="date" id="invoice_date" class="form-control">
                        </div>
                        
                        <div class="form-group col-sm-3">
                            <label for="">Transaction Type</label><br>
                            <div style="margin-top:10px;">
                            <input type="radio" name="transaksi_tipe" id="cash" value="0">
                            <b>Cash</b>
                            <input type="radio" name="transaksi_tipe" id="credit" value="1" style="margin-left:20px;">
                            <b>Credit</b>
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="">Term Until</label>
                            <input type="date" id="term_until" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-sm-3">
                    <label for="">Suplier</label>
                        <select name="id_suplier" id="id_suplier" class="selectpicker form-control" data-live-search="true" title="Pilih Suplier" autocomplete="off" data-size="5">
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="">Nama Produk</label>
                        <select name="produk_id" id="produk_id" class="selectpicker form-control" data-live-search="true" title="Pilih Produk" autocomplete="off" data-size="5">
                        </select>
                    </div>
                    <div class="form-group col-sm-3" style="display: none;">
                            <label for="">Unit Price</label>
                                        <div class="input-group">
                                        <div class="input-group-prepend">
                                                <div class="input-group-text">Rp</div>
                                            </div>
                                            <input  type="number" class="form-control" id="unit_satuan_price" value="0">
                                            
                                        </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="">Diskon</label>
                                        <div class="input-group">
                       
                                            <input  type="number" class="form-control" id="diskon" value="0">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                        </div>
                        <div class="form-group col-sm-3">
                        <label for="">Gudang</label>
                        <select name="id_gudang" id="id_gudang" class="form-control rounded" disabled="true">
                            @foreach ($gudang as $c)
                            <option value="{{$c->id_gudang}}">{{$c->nama_gudang}}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="form-row" id="wadah">
                    </div>
                    <div class="row">
                                        <div class="col-md-2" align="center">
                                            <button type="button" style="width:140px;" id="add" class="btn btn-outline-success btn-sm">Add</button>
                                        </div>
                                        <div class="col-md-2" align="center">
                                            <button type="button" style="width:140px;" class="btn btn-outline-danger btn-sm" onclick="bersih()">Remove All</button>
                                        </div>
                                    </div>
                </form>
            </div>
        </div>
    </div>
    
<div class="row">
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th >Type Produk</th>
                    <th >Brand</th>
                    <th >Nama Produk</th>
                    
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Diskon</th>
                    <th>Total Price</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
        <!-- <button onclick="register()" class="btn btn-danger btn-sm">Register Transaksi</button> -->
        
    </div>
</div>
</div>
<br>
<div class="row mb-2 bg-light p-4 justify-content-md-center rounded" style="box-shadow:1px 1px 5px;">
<div class="col-sm-5 ">
    <table style="text-align: center;">
        <tr>
            <td>Total Purchase : </td>
            <td><input type="number" class="form-control rounded" id="sub_tot_purchase" value="0" readonly></td>
        </tr>
        <tr>
            <td>Discount : %</td>
            <td><input type="number" class="form-control rounded" id="convert_discount" value="0"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="number" class="form-control rounded" id="final_discount" value="0" readonly></td>
        </tr>
        <tr>
            <td>Total After Discount : </td>
            <td><input type="number" class="form-control rounded" id="tot_after_discount" value="0" readonly></td>
        </tr>
        <tr>
            <td>Down Payment : </td>
            <td><input type="number" class="form-control rounded" id="down_payment" value="0"></td>
        </tr>
        <tr>
            <td>Total Debt Balance : </td>
            <td><input type="number" class="form-control rounded" id="tot_debt_balance" value="0" readonly></td>
        </tr>
    </table>
</div>
<div class="col-sm-3 align-self-center">
<a href="#" class="btn btn-danger btn-sm" id="register">Register Transaksi</a>
</div>

</div>

<!-- Modal -->


<script>
    $(document).ready(function(){
        id_cabang = {{session()->get('cabang')}}
      tables = $('#tabel').DataTable({
        processing : true,
        paginate : false,
        searching:false,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/purchasetmp/datatable/') }}/"+id_cabang, 
        },
        columns:[
          {
            data:'nama_type_produk',
            defaultContent:""
          },
          {
            data:'produk_brand',
            defaultContent:""
          },
          {
            data:'produk_nama',
            defaultContent:""
          },
          {
            data:'stok_quantity',
            defaultContent:""
          },
          {
            data:'total',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'diskon',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'total_price',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_transaksi_purchase_tmp + ")' class='btn btn-danger btn-sm'>Hapus</button> "+
            "</div>" ;
            }
          }
        ]
      });
            // get produk
            axios.get('{{url('/api/getproduk/')}}')
                .then(function (res) {
                // handle success
                isi = res.data
                $.each(isi.data, function (i, item) {
                    
                    $('#produk_id').append("<option value="+item.produk_id+">"+item.produk_nama+"</option>");
                    $('#produk_id_edit').append("<option value="+item.produk_id+">"+item.produk_nama+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });
            axios.get('{{url('/api/getsuplier/')}}/'+id_cabang)
                .then(function (res) {
                // handle success
                isi = res.data
                $.each(isi.data, function (i, item) {
                    
                    $('#id_suplier').append("<option value="+item.id_suplier+">"+item.nama_suplier+"</option>");
                    $('#id_suplier_edit').append("<option value="+item.id_suplier+">"+item.nama_suplier+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });

            $('#produk_id').on('change', function (e) {
                var optionSelected = $("option:selected", this); 
                let id = this.value;
                axios.get('{{url('/api/produk/')}}/'+id)
                    .then(function(res){
                        isi = res.data
                        harga_modal = isi.data.harga_modal
                        $('#unit_satuan_price').val(harga_modal)
                    });
                axios.get('{{url('/api/getunit/')}}/'+id)
                    .then(function(res){
                        isi = res.data
                        
                        panjang = isi.data.length
                        $('#wadah').html('');
                        if (panjang == 3){
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah').append(`<div class='form-group col-sm-4'><label>${isi.data[index].maximum_unit_name}</label> <input type='number' id='unit${index+1}' value='0' class='form-control'></div> `)   
                            }
                        }else if (panjang == 2){
                            $('#wadah').append(`<input type='hidden' id='unit${1}' value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah').append(`<div class='form-group col-sm-4'><label>${isi.data[index].maximum_unit_name}</label> <input type='number' id='unit${index+2}' value='0' class='form-control'></div> `)  
                            }
                        }else if (panjang == 1){
                            $('#wadah').append(`<input type='hidden' id='unit${1}' value='null'> `)
                            $('#wadah').append(`<input type='hidden' id='unit${2}' value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah').append(`<div class="form-group col-sm-4"><label>${isi.data[index].maximum_unit_name}</label> <input type='number' id='unit${index+3}' value='0' class='form-control'></div> `)  
                            }
                        }
                    })
                });

                $('#term_until').hide();
                session_cabang = {{session()->get('cabang')}};
                generateinv(session_cabang);
                axios.get('{{url('/api/calculatetmp/')}}/'+session_cabang)
                    .then(function(res){
                        isi = res.data
                        $('#sub_tot_purchase').val(isi.tot)
                        $('#tot_after_discount').val(isi.tot)
                        $('#tot_debt_balance').val(isi.tot)
                    });
    
    });

    // radio
    $('#cash').click(function(){
        var other = document.getElementById('cash').checked
        console.log(other)
        if (other == true) {
            $('#term_until').hide()  
        }
    });
    $('#credit').click(function(){
        var other = document.getElementById('credit').checked
        console.log(other)
        if (other == true) {
            $('#term_until').show()  
        }
    });

    $('#add').click(function(e){
        e.preventDefault();
        let produk_id = $('#produk_id').val();
        let invoice_id = $('#invoice_id').val();
        let id_gudang = $('#id_gudang').val();
        let invoice_date = $('#invoice_date').val();
        let transaksi_tipe = $('input[name=transaksi_tipe]:checked').val();
        if(transaksi_tipe == 0){
            document.getElementById('term_until').value = invoice_date;
        }
        let term_until = $('#term_until').val();
        let id_suplier = $('#id_suplier').val();
        let unit_satuan_price = $('#unit_satuan_price').val();
        let diskon = $('#diskon').val();
        let unit1 = $('#unit1').val();
        let unit2 = $('#unit2').val();
        let unit3 = $('#unit3').val();
        let id_cabang = {{session()->get('cabang')}}
        axios.get('{{url('/api/getunit/')}}/'+produk_id)
                    .then(function(res){
                        isi = res.data
                        panjang = isi.data.length
                        if(unit1 == 'null' && unit2 == 'null'){
                            harga = unit_satuan_price/ isi.data[0].default_value;
                            unit3 = (parseInt(unit3))* isi.data[0].default_value;
                        }else if(unit1 == 'null') {
                            harga = unit_satuan_price/ isi.data[0].default_value;
                            unit2 = (parseInt(unit2)) * isi.data[0].default_value;
                            harga = harga/ isi.data[1].default_value;
                            unit3 = (parseInt(unit2)+parseInt(unit3))* isi.data[1].default_value; 
                        }else{
                            harga = unit_satuan_price/ isi.data[0].default_value;
                            unit1 = parseInt(unit1) * isi.data[0].default_value;
                            harga = harga/ isi.data[1].default_value;
                            unit2 = (parseInt(unit1)+parseInt(unit2)) * isi.data[1].default_value;
                            harga = harga/ isi.data[2].default_value;
                            unit3 = (parseInt(unit2)+parseInt(unit3))* isi.data[2].default_value; 
                        }
                        total =  harga * unit3;
                        tmp_harga = (diskon/100) * total;
                        total_price = total - tmp_harga;

                        
                        axios.post('{{url('/api/purchasetmp/')}}',{
                            'produk_id':produk_id,
                            'invoice_id':invoice_id,
                            'invoice_date':invoice_date,
                            'transaksi_tipe':transaksi_tipe,
                            'term_until':term_until,
                            'id_suplier':id_suplier,
                            'unit_satuan_price':unit_satuan_price,
                            'diskon':tmp_harga,
                            'quantity':unit3,
                            'total_price':total_price,
                            'id_cabang':id_cabang,
                            'id_gudang':id_gudang
                        })
                        .then(function (res) {
                            console.log(res)
                            var data = res.data
                            if(data.status == 200)
                            {
                                $('#unit_satuan_price').val('')
                                $("#produk_id").val([]).selectpicker('refresh')
                                $('#diskon').val('0')
                                $('#convert_discount').val('0')
                                $('#final_discount').val('0')
                                $('#down_payment').val('0')
                                $('#wadah').html('')
                                tables.ajax.reload()
                                toastr.info(data.message)
                                axios.get('{{url('/api/calculatetmp/')}}/'+id_cabang)
                                .then(function(res){
                                    isi = res.data
                                    $('#sub_tot_purchase').val(isi.tot)
                                    $('#tot_after_discount').val(isi.tot)
                                    $('#tot_debt_balance').val(isi.tot)
                                });
                            }else{
                                toastr.info(data.message)
                            }
                        })
                    });

                    
        
    })

    $("#convert_discount").on('keyup',function(){
        let sub_tot = $('#sub_tot_purchase').val();
        let convert_discount = $('#convert_discount').val();
        convert = (convert_discount/100) * sub_tot
        $('#final_discount').val(convert);
        let after_discount = sub_tot - convert;
        $('#down_payment').val('0');
        $('#tot_after_discount').val(after_discount);
        $('#tot_debt_balance').val(after_discount);
    });
    $("#down_payment").on('keyup',function(){
        let after = $('#tot_after_discount').val();
        let down = $('#down_payment').val();
        let debt_balance = after - down;
        $('#tot_debt_balance').val(debt_balance);
    });
    $("#register").on('click', function(e) {
        e.preventDefault();
        id_cabang = {{session()->get('cabang')}}
        let tot = $('#sub_tot_purchase').val();
        let dis = $('#final_discount').val();
        let down = $('#down_payment').val();
        let deb = $('#tot_debt_balance').val();
        cek = window.open('{{url('/api/registerpurchase')}}/'+tot+'/'+dis+'/'+down+'/'+deb+'/'+id_cabang, "_blank");
        $(cek).on("unload", function(){
        tables.ajax.reload();
        session_cabang = {{session()->get('cabang')}}
        generateinv(session_cabang);
        });
        $('#sub_tot_purchase').val('');
        $('#final_discount').val('');
        $('#tot_after_discount').val('');
        $('#down_payment').val('');
        $('#tot_debt_balance').val('');
        $('#convert_discount').val('');
        bersih()
    });

    function deleted(id)
    {
        
        axios.delete('{{url('/api/purchasetmp/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
            axios.get('{{url('/api/calculatetmp/')}}/'+id_cabang)
                                .then(function(res){
                                    isi = res.data
                                    $('#sub_tot_purchase').val(isi.tot)
                                    $('#tot_after_discount').val(isi.tot)
                                    $('#tot_debt_balance').val(isi.tot)
                                });
        })
    }

    function bersih()
    {
        
        document.getElementById("invoice_date").value=null;
        $("#id_suplier").val([]).selectpicker('refresh');
        $("#produk_id").val([]).selectpicker('refresh');
        $('input[name=transaksi_tipe]').val([]);
        $('#term_until').val('');
        $('#unit_satuan_price').val('');
        $('#diskon').val('0');
        $('#wadah').html('');
        
    }

    function generateinv(id){
        axios.get('{{url('/api/purchaseinv/')}}/'+id)
        .then(function(res){
                isi = res.data
                invoice = isi.invoice
                $('#invoice_id').val(invoice)
        })
    }
</script>
@endsection
