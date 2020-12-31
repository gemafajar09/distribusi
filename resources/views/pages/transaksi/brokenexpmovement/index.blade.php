@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Broken & Exp Movement')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
        <div class="row bg-white p-3 rounded mb-3" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <div class="col-sm-5">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Movement ID</label>
                            <input type="text" class="form-control" readonly value="{{$invoice}}" id="inv_broken_exp">
                        </div>
                        <div class="form-group">
                            <label for="">Form Warehouse</label>
                            <select name="" id="dari_gudang" class="form-control">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">To Warehouse</label>
                            <select name="" id="menuju_gudang" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Movement Date</label>
                            <input type="date" class="form-control" id="tanggal">
                        </div>
                        <div class="form-group">
                            <label for="">Note</label>
                            <textarea name="" id="note" cols="30" rows="5" class="form-control">Fix</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Stock ID</label>
                                    <input type="text" class="form-control" readonly id="stok_id">
                                </div>
                                <button type="button" class="btn btn-outline-success" id="CariData">Cari
                                    Data</button>
                                <div class="form-group">
                                    <label for="">Product ID</label>
                                    <input type="text" readonly id="produk_id" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Product Type</label>
                                    <input type="text" class="form-control" id="produk_type" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Product Brand</label>
                                    <input type="text" class="form-control" id="produk_brand" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Product Name</label>
                                    <input type="text" class="form-control" id="produk_nama" readonly>
                                </div>
                            </div>
                            </div>
                            <div class="form-group" id="">
                                <label for="">Quantity</label>
                                <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div id="wadah"></div>
                                    </div>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div id="wadah1"></div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <button class="btn btn-outline-success" type="button" id="add"><i class="fa fa-plus"></i>
                                Add To
                                List</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-2">
    <div class="x_content">
        <div class="row bg-white p-3 rounded mb-3" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <table class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap" id="tabel" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>STOCK ID</th>
                        <th>PRODUCT TYPE</th>
                        <th>PRODUCT BRAND</th>
                        <th>PRODUCT NAME</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Aksi</th> 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-outline-info" id="register"><i class="fa fa-save"></i> Save</button>
</div>

<div class="modal" tabindex="-1" id="modalData"> 
  <div class="modal-dialog" >
    <div class="modal-content" style="width: 600px;">
      <div class="modal-header">
        <h5 class="modal-title">Stok Inventory</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Warehouse</label>
                            <input type="text" class="form-control" readonly id="warehouse_name">
                            
                        </div>
                    </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
        <table class="table table-striped table-bordered" id="tabelsatu">
                    <thead>
                        <tr>
                            <th>STOCK ID</th>
                            <th>PRODUCT NAME</th>
                            <th>QUANTITY</th>
                            <th>CAPITAL PRICE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
                </div>
                </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function () {
        id_cabang = {{session()->get('cabang')}}
        tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/brokenexp/datatable/') }}/"+id_cabang,
        },
        columns:[
       
          {
            data: 'stok_id'
          },
          {
            data:'nama_type'
          },
          {
            data:'produk_brand'
          },
          {
            data:'produk_nama'
          },
          {
            data:'jumlah_broken'
          },
          {
            data:'total',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_broken_exp + ")' class='btn btn-danger btn-sm'>Hapus</button> "+
            "</div>" ;
            }
          }
        ]
      });

      ambilDataGudang(id_cabang)

      $('#add').click(function(){
        let inv = $('#inv_broken_exp').val();
        let dari_gudang = $('#dari_gudang').val();
        let menuju_gudang = $('#menuju_gudang').val();
        let movement_date = $('#tanggal').val();
        let note = $('#note').val();
        let stok_id = $('#stok_id').val();
        let produk_id = $('#produk_id').val();
        let unit_satuan_price= $('#satuan_price').val();
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
                        axios.post('{{url('/api/add_broken/')}}',{
                            'inv_broken_exp':inv,
                            'id_gudang_dari':dari_gudang,
                            'id_gudang_tujuan':menuju_gudang,
                            'stok_id':stok_id,
                            'jumlah_broken':unit3,
                            'movement_date':movement_date,
                            'note':note,
                            'id_cabang':id_cabang,
                            'status_broken':'0'
                            
                        })
                        .then(function (res) {
                            console.log(res)
                            var data = res.data
                            if(data.status == 200)
                            {
                                $("#stok_id").val('');
                                $('#produk_id').val('');
                                $('#produk_nama').val('');
                                $('#produk_brand').val('');
                                $('#produk_type').val('');
                                $('#hiden').html('');
                                $('#wadah').html('');
                                $('#wadah1').html('');
                                toastr.info(data.message);
                                tables.ajax.reload();
                            }else{
                                toastr.info(data.message)
                            }
                        })
            });
    });
    });

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    var warehouse_asal = [];
    $('#tanggal').val(today)

    var dari_gudang = null;
    var menuju_gudang = null;
    var data_gudang = null;

    function load_gudang(id_gudang){
        tables1 = $('#tabelsatu').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/stok/datatablegudang/') }}/"+id_gudang,
        },
        columns:[
          {
            data:'produk_nama'
          },
          {
            data:'jumlah'
          },
          {
            data:'capital_price'
          },
          {
            data:'stok_harga'
          },{
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='PilihData(" + data.stok_id + ")' class='btn btn-success btn-sm'>Pilih</button>" +
            "</div>" ;
            }
          }
        ]
      });
    }


    function tampilGudangAsal(data)
    {
        document.getElementById("dari_gudang").innerHTML = "<option value='0'>-- Pilih Gudang --</option>"
        warehouse_asal = [
            {
                id_gudang: 0,
                nama_gudang: "-- Pilih Gudang --"
            }
        ]

        for(var x = 0; x < data.length; x++)
        {
            warehouse_asal.push({id_gudang: data[x].id_gudang, nama_gudang: data[x].nama_gudang})
            document.getElementById("dari_gudang").innerHTML += "<option value='" + data[x].id_gudang + "'>" + data[x].nama_gudang + "</option>"
        }

    }

    function tampilGudangTujuan()
    {
        var id_gudang_asal = document.getElementById("dari_gudang").value;
        var nama_gudang_asal = document.getElementById("dari_gudang").value;
        document.getElementById("menuju_gudang").innerHTML = "";
        if(id_gudang_asal != "0")
        {
            for(var x = 0; x < warehouse_asal.length; x++)
            {
                if(warehouse_asal[x].id_gudang != id_gudang_asal && id_gudang_asal != "0")
                {
                    document.getElementById("menuju_gudang").innerHTML += "<option value='" + warehouse_asal[x].id_gudang + "'>" + warehouse_asal[x].nama_gudang + "</option>";
                }
            }
        }
    }

    function tampilListStok()
    {
        // id = $('#dari_gudang').val();
        $("#modalData").modal("show");
        document.getElementById("warehouse_name").value = warehouse_asal[document.getElementById("dari_gudang").selectedIndex].nama_gudang;
        
    }

    function ambilDataGudang(id_cabang)
    {
        document.getElementById("dari_gudang").innerHTML = "<option value=''>-- Pilih Gudang --</option>";
        axios.get('{{url('api/gudangcabang/')}}/'+id_cabang)
            .then(function(res){
                tampilGudangAsal(res.data.data);
            })
    }

    function PilihData(id)
    {
        axios.get('{{url('/api/ambildatastok/')}}/'+ id)
        .then(function(res){
            var data = res.data.data
            console.log(data);
            // stok(data.produk_id,data.jumlah)
            $('#stok_id').val(data[0].stok_id)
            $('#produk_id').val(data[0].produk_id)
            $('#produk_nama').val(data[0].produk_nama)
            $('#produk_brand').val(data[0].produk_brand)
            $('#produk_type').val(data[0].nama_type_produk)
            $("#modalData").modal("hide");
        })

        // wadah
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
    }


    // document.getElementById('dari_gudang').addEventListener("change", tampilGudangTujuan);
    $('#dari_gudang').change(function(){
        id_gudang = $('#dari_gudang').val();
        tampilGudangTujuan();
        if ( $.fn.DataTable.isDataTable('#tabelsatu') ) {
              $('#tabelsatu').DataTable().destroy();
            }
        load_gudang(id_gudang);
    });

    function deleted(id)
    {
        axios.delete('{{url('/api/broken/remove/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#register').click(function(){
        id_cabang = {{session()->get('cabang')}}
        window.open('{{url('/api/registerbroken')}}/'+id_cabang, "_blank");
        location.reload();
        
    });

    
    document.getElementById('CariData').addEventListener("click", tampilListStok);
    
</script>
@endsection
