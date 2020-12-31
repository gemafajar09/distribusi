@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Harga Khusus')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
    <div class="row bg-white p-3 rounded mb-4" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-row">
                    <div class="form-group col-sm-4">
                        <label for="">Nama Customer</label>
                        <select name="id_customer" id="id_customer" class="selectpicker rounded" data-width="100%" data-live-search="true" title="Pilih Customer" autocomplete="off" data-size="5">
                            
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="">Nama Produk</label>
                        <select name="produk_id" id="produk_id" class="selectpicker rounded" data-width="100%" data-live-search="true" title="Pilih Produk" autocomplete="off" data-size="5"></select>
                    </div>
                    <div class="form-group col-sm-4">
                    <label for="">Harga Khusus</label>
                        <input type="text" class="form-control rounded" id="spesial_nominal" name="spesial_nominal" placeholder="Masukan Harga Khusus">
                    </div>
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
</div>
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                    
                    <th >ID Customer</th>
                    <th >Nama Customer</th>
                    <th  >ID Produk</th>
                    <th >Nama Produk</th>
                    <th >Harga Produk</th>
                    <th >Harga Khsus</th>
                    <th >Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="exampleModalLabel">Form Edit Data</h5>
                <button type="button" class="close" name="tutupModalTambah" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="id_harga_khusus">
                        <select name="id_customer" id="id_customer_edit" class="selectpicker" data-live-search="true" autocomplete="off">
                            </select>
                </div>
                <div class="form-group">
                    <select name="produk_id" id="produk_id_edit" class="selectpicker" data-live-search="true" title="Masukan nama Produk" autocomplete="off"></select>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="spesial_nominal_edit" name="spesial_nominal" placeholder="Masukan Harga Khusus" autocomplete="off">
                </div>
                <button class="btn btn-success" onclick="editData()" type="button">Edit Data</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        id_cabang = {{session()->get('cabang')}}
      tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/spesial/datatable') }}",
        },
        columns:[
        
          {
            data: 'id_customer'
          },
          {
            data:'nama_customer'
          },
          {
            data:'produk_id'
          },
          {
            data:'produk_nama'
          },
          {
            data:'produk_harga',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'spesial_nominal',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_harga_khusus + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
                "<button type='button' onclick='ambilData(" + data.id_harga_khusus + ")' class='btn btn-success btn-sm'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
      });
            // get Customer
            axios.get('{{url('/api/getcustomer/')}}/'+id_cabang)
                .then(function (res) {
                // handle success
                let isi = res.data
                $.each(isi.data, function (i, item) {  
                    $('#id_customer').append("<option value="+item.id_customer+">"+item.nama_customer+"</option>");
                    $('#id_customer_edit').append("<option value="+item.id_customer+">"+item.nama_customer+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });
            // get Produk
            axios.get('{{url('/api/getproduk')}}')
                .then(function (res) {
                // handle success
                isi = res.data
                $.each(isi.data, function (i, item) {
                    
                    $('#produk_id').append("<option value="+item.produk_id+">"+item.produk_nama+"</option>");
                    $('#produk_id_edit').append("<option value="+item.produk_id+">"+item.produk_nama+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });
    });

    

    function deleted(id)
    {
        
        axios.delete('{{url('/api/spesial/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#add').click(function(e){
        e.preventDefault();
        var id_customer = $('#id_customer').val();
        var produk_id = $('#produk_id').val();
        var spesial_nominal = $('#spesial_nominal').val();

        axios.post('{{url('/api/spesial')}}',{
            id_customer: id_customer,
            produk_id: produk_id,
            spesial_nominal:spesial_nominal
        })
        .then(function (res) {
            var data = res.data
            if(data.status == 200)
            {
                bersih()
                tables.ajax.reload()
                toastr.info(data.message)
            }else{
                toastr.info(data.message)
            }
        })
    })

    function ambilData(id)
    {
        axios.get('{{url('/api/spesial')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            document.getElementById('id_harga_khusus').value=isi.data.id_harga_khusus;
            $("#id_customer_edit").val([isi.data.id_customer]).selectpicker('refresh');
            $("#produk_id_edit").val([isi.data.produk_id]).selectpicker('refresh');
            document.getElementById('spesial_nominal_edit').value=isi.data.spesial_nominal;
            $('#modal').modal('show');
        })
    }

    function editData()
    {
        var id_harga_khusus = $('#id_harga_khusus').val(); 
        var id_customer_edit = $('#id_customer_edit').val();
        var produk_id_edit = $('#produk_id_edit').val();
        var spesial_nominal_edit = $('#spesial_nominal_edit').val();

        axios.put('{{url('/api/spesial')}}',{
            'id_harga_khusus':id_harga_khusus,
            'id_customer':id_customer_edit,
            'produk_id':produk_id_edit,
            'spesial_nominal':spesial_nominal_edit
        }).then(function(res){
           
            var data = res.data
            toastr.info(data.message)
            $('#modal').modal('hide')
            tables.ajax.reload()
            bersih()
        })
    }

    function bersih()
    {
        $("#id_customer").val([]).selectpicker('refresh');
        $("#produk_id").val([]).selectpicker('refresh');
        document.getElementById("spesial_nominal").value=null;
    }
</script>
@endsection
