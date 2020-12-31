@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Purchase Order Return')
<!-- Page Content -->
@section('content')

    <div class="x_content">
        <div class="row bg-white p-3 rounded mb-3" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="">PO Return ID</label>
                                    <input type="text" id="return_id" class="form-control" readonly>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Return Date</label>
                                    <input type="date" id="return_date" class="form-control" >
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-4">
                                     <label for="">Pilih Suplier</label>
                                    <select name="id_suplier" id="id_suplier" class="selectpicker form-control" data-live-search="true" title="Pilih Suplier" autocomplete="off" data-size="5">
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="">Stock To Return</label>
                                    <select name="stok_id" id="stok_id" class="selectpicker form-control" data-live-search="true" title="Pilih Produk" autocomplete="off" data-size="5">
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                <label for="">Return Tipe</label>
                                    <select name="note_return" id="note_return" class="form-control"autocomplete="off">
                                        <option value="1">Broken Product</option>
                                        <option value="2">Expired Product</option>
                                        <option value="3">Mismatch Order Product</option>
                                        <option value="4">Unsold Product</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        <div class="form-row" id="wadah">
                            
                            </div>
                            <div class="form-row" id="wadah1">

                            </div>
                            <div id="hiden">

                            </div>
                        </div>
                        
                            
                        
                    </div>
                    <div class="row">
                                        <div class="col-md-2" align="center">
                                            <button type="button" style="width:140px;" id="add" class="btn btn-outline-success">Add</button>
                                        </div>
                                        <div class="col-md-2" align="center">
                                            <button type="button" style="width:140px;" class="btn btn-outline-danger" onclick="bersih()">Remove All</button>
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
                    <th>Nama Suplier</th>
                    <th >Produk ID</th>
                    <th >Produk Nama</th>
                    <th>Return Date</th>
                    <th >Quantity</th>
                    <th>Price</th>
                    <th >Note Return</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
        <!-- <button onclick="register()" class="btn btn-danger btn-sm">Register Transaksi</button> -->
        <a href="#" class="btn btn-danger btn-sm" id="register">Register Transaksi</a>
    </div>
</div>
</div>

<!-- Modal -->


<script>
    $(document).ready(function(){
        id_cabang = {{session()->get('cabang')}}
      tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/returnpurchase/datatable/') }}/"+id_cabang, 
        },
        columns:[
          {
            data:'nama_suplier',
            defaultContent:""
          },
          {
            data:'produk_id',
            defaultContent:""
          },
          {
            data:'produk_nama',
            defaultContent:""
          },
          {
            data:'return_date',
            defaultContent:""
          },
          {
            data:'stok_quantity',
            defaultContent:""
          },
          {
            data:'price',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'note_return',
            defaultContent:""
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_transaksi_purchase_return + ")' class='btn btn-danger btn-sm'>Hapus</button> "+
            "</div>" ;
            }
          }
        ]
      });
            
            axios.get('{{url('/api/getsuplier/')}}/'+id_cabang)
                .then(function (res) {
                // handle success
                isi = res.data
                $.each(isi.data, function (i, item) {
                    
                    $('#id_suplier').append("<option value="+item.id_suplier+">"+item.nama_suplier+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });

            $('#id_suplier').on('change',function(e){
                var optionSelected = $("option:selected", this); 
                let id_suplier = this.value;
                $("#stok_id").html('');
                axios.get('{{url('/api/getsuplier/produk/')}}/'+id_suplier+'/'+id_cabang)
                .then(function(res){
                    isi = res.data
                    $.each(isi.data, function (i, item) {
                        $('#stok_id').append("<option value="+item.stok_id+">"+item.produk_nama+' '+'Rp. '+item.produk_harga+"</option>");
                    });
                    $('.selectpicker').selectpicker('refresh');
                    })
            });

            $('#stok_id').on('change', function (e) {
                var optionSelected = $("option:selected", this); 
                let id = this.value;
                // untuk nampilkan stok
                axios.get('{{url('/api/getsuplierproduk/')}}/'+id)
                    .then(function(res){
                        isi = res.data;
                        data = isi[0];
                        stok_id = data.stok_id;
                        satuan_price = data.satuan_price;
                        produk_id = data.produk_id;
                        $('#hiden').append(`<input type='hidden' value='${produk_id}' class='form-control' id='produk_id' readonly><input type='hidden' value='${satuan_price}' class='form-control' id='satuan_price' readonly>`) 
                        stok = data.stok.reverse();
                        panjang = data.stok.length;
                        $('#wadah').html('');
                        if (panjang == 3){
                            for (let index = 0; index < panjang; index++) {
                                data_tmp = stok[index].split(",")
                                $('#wadah').append(`<div class='form-group col-sm-4'><label>${data_tmp[1]}</label> <input type='number' value='${data_tmp[0]}' class='form-control' readonly></div> `)   
                            }
                        }else if (panjang == 2){
                            $('#wadah').append(`<input type='hidden'  value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                data_tmp = stok[index].split(",")
                               
                                $('#wadah').append(`<div class='form-group col-sm-6'><label>${data_tmp[1]}</label> <input type='number' value='${data_tmp[0]}' class='form-control' readonly></div> `)
                            }
                        }else if (panjang == 1){
                            $('#wadah').append(`<input type='hidden'  value='null'> `)
                            $('#wadah').append(`<input type='hidden'  value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                data_tmp = stok[index].split(",")
                                $('#wadah').append(`<div class="form-group col-sm-12"><label>${data_tmp[1]}</label> <input type='number' value='${data_tmp[0]}' class='form-control' readonly></div> `)  
                                }
                            }
                
                // untuk inputan wadah1
                axios.get('{{url('/api/getunit/')}}/'+produk_id)
                    .then(function(res){
                        isi = res.data
                        panjang = isi.data.length
                        $('#wadah1').html('');
                        if (panjang == 3){
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah1').append(`<div class='form-group col-sm-4'><label>${isi.data[index].maximum_unit_name}</label> <input type='number' id='unit${index+1}' value='0' class='form-control'></div> `)   
                            }
                        }else if (panjang == 2){
                            $('#wadah1').append(`<input type='hidden' id='unit${1}' value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah1').append(`<div class='form-group col-sm-6'><label>${isi.data[index].maximum_unit_name}</label> <input type='number' id='unit${index+2}' value='0' class='form-control'></div> `)  
                            }
                        }else if (panjang == 1){
                            $('#wadah1').append(`<input type='hidden' id='unit${1}' value='null'> `)
                            $('#wadah1').append(`<input type='hidden' id='unit${2}' value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah1').append(`<div class="form-group col-sm-12"><label>${isi.data[index].maximum_unit_name}</label> <input type='number' id='unit${index+3}' value='0' class='form-control'></div> `)  
                            }
                        }
                    })
                })
            });

            session_cabang = {{session()->get('cabang')}}
            generateinvreturn(session_cabang);
            
    });


    $('#add').click(function(e){
        e.preventDefault();
        let produk_id = $('#produk_id').val();
        let return_id = $('#return_id').val();
        let return_date = $('#return_date').val();
        let id_suplier = $('#id_suplier').val();
        let unit_satuan_price = $('#satuan_price').val();
        let stok_id = $('#stok_id').val();
        let note_return = $('#note_return').val();
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
                        price =  harga * unit3;
                        axios.post('{{url('/api/purchasereturn/')}}',{
                            'stok_id':stok_id,
                            'return_id':return_id,
                            'return_date':return_date,
                            'id_suplier':id_suplier,
                            'stok_id':stok_id,
                            'note_return':note_return,
                            'price':price,
                            'jumlah_return':unit3,
                            'id_cabang':id_cabang,
                            
                        })
                        .then(function (res) {
                            console.log(res)
                            var data = res.data
                            if(data.status == 200)
                            {
                                $("#stok_id").val([]).selectpicker('refresh');
                                $('#produk_id').html('');
                                $('#hiden').html('');
                                $('#wadah').html('');
                                $('#wadah1').html('');
                                tables.ajax.reload()
                                toastr.info(data.message)
                            }else{
                                toastr.info(data.message)
                            }
                        })
            });
    })

    $("#register").on('click', function(e) {
        id_cabang = {{session()->get('cabang')}}
        e.preventDefault();
        cek = window.open('{{url('/api/registerpurchasereturn')}}/'+id_cabang, "_blank");
        $(cek).on("unload", function(){
        tables.ajax.reload();
        session_cabang = {{session()->get('cabang')}};
        generateinvreturn(session_cabang);
        });
        bersih();
    });
    
    function deleted(id)
    {
        
        axios.delete('{{url('/api/purchasereturn/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    function bersih()
    {
        document.getElementById("return_date").value=null;
        $("#stok_id").html('');
        $("#id_suplier").val([]).selectpicker('refresh');
        $("#stok_id").val([]).selectpicker('refresh');
        $('#wadah').html('');
        $('#wadah1').html('');
        
    }

    function generateinvreturn(id){
        axios.get('{{url('/api/purchasereturninv/')}}/'+id)
        .then(function(res){
                isi = res.data
                invoice = isi.invoice
                $('#return_id').val(invoice)
                
        })
    }
</script>
@endsection
