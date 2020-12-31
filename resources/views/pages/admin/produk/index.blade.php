@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Produk')
<!-- Page Content -->
@section('content')
@if(session()->get('level') == 4)
<div class="mt-2">
    <div class="x_content">
        <div class="row bg-white p-3 rounded mb-3" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-row">
                    <div class="form-group col-sm-2">
                        <label for="">Tipe Produk</label>
                        <select name="id_type_produk" id="id_type_produk" class="selectpicker rounded"   data-width="100%" data-live-search="true" title="Pilih Tipe Produk" autocomplete="off" data-size="5">
                        </select>
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="">Produk Brand</label>
                        <input type="text" class="form-control rounded" name="produk_brand" id="produk_brand" placeholder="Produk Brand">
                    </div>
                    <div class="form-group col-sm-5">
                            <label for="">Produk Nama</label>
                            <input type="text" name="produk_nama" id="produk_nama" class="form-control rounded" placeholder="Produk Nama">

                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-4">
                        <label for="">Harga Modal</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">Rp</div>
                                </div>

                            <input type="number" name="harga_modal" id="harga_modal" class="form-control rounded" value="0">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="">Produk Harga</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">Rp</div>
                                </div>

                            <input type="number" name="produk_harga" id="produk_harga" class="form-control rounded" value="0">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="">Stok</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">Pcs</div>
                                </div>
                            <input type="number" name="stok" id="stok" class="form-control rounded" value="0">
                        </div>
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
@endif
<div class="row">
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                    
                    <th >ID Produk</th>
                    <th >Type Produk</th>
                    <th >Brand</th>
                    <th >Nama Produk</th>
                    <th >Harga Jual</th>
                    <th >Capital Price</th>
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
            <div class="form-row">
                    <div class="form-group col-sm-6">
                        <input type="hidden" id="produk_id">
                        <label for="">Tipe Produk</label>
                        <select name="id_type_produk_edit" id="id_type_produk_edit" class="selectpicker form-control" data-live-search="true" autocomplete="off">
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Produk Brand</label>
                        <input type="text" class="form-control" name="produk_brand_edit" id="produk_brand_edit">
                    </div>
                    </div>
                    
                        <div class="form-group">
                            <label for="">Produk Nama</label>
                            <input type="text" name="produk_nama_edit" id="produk_nama_edit" class="form-control">

                        </div>
                        <div class="form-row">
                            <div class="form-group col-sm-4">
                            <label for="">Harga Modal</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">Rp</div>
                                </div>

                            <input type="number" name="harga_modal_edit" id="harga_modal_edit" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="">Produk Harga</label>
                                <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">Rp</div>
                                </div>

                            <input type="number" name="produk_harga_edit" id="produk_harga_edit" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="">Stok</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <div class="input-group-text">Pcs</div>
                                </div>
                            <input type="number" name="stok_edit" id="stok_edit" class="form-control">
                        </div>
                    </div>
                    </div>
                <button class="btn btn-success" onclick="editData()" type="button">Edit Data</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
      tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/produk/datatable') }}",
        },
        columns:[
        
          {
            data: 'produk_id'
          },
          {
            data:'nama_type_produk'
          },
          {
            data:'produk_brand'
          },
          {
            data:'produk_nama'
          },
          {
            data:'produk_harga',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            @if(session()->get('level') == 4)
                data:'harga_modal',
                render: $.fn.dataTable.render.number( ',', '.', 2 )
            @else
                data:null,
                render: function(data, type, row, meta) {
            return " " ;}
            @endif
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
            @if(session()->get('level') == 4)
                `<button type='button' onclick='deleted("${data.produk_id}")' class='btn btn-danger btn-sm'>Hapus</button> ` +
                `<button type='button' onclick='ambilData("${data.produk_id}")' class='btn btn-success btn-sm'>Edit</button>` +
            @endif
            "</div>" ;
            }
          }
        ]
      });
            // get Customer
            axios.get('{{url('/api/gettype')}}')
                .then(function (res) {
                // handle success
                let isi = res.data
                $.each(isi.data, function (i, item) {  
                    $('#id_type_produk').append("<option value="+item.id_type_produk+">"+item.nama_type_produk+"</option>");
                    $('#id_type_produk_edit').append("<option value="+item.id_type_produk+">"+item.nama_type_produk+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });
            
    });

    

    function deleted(id)
    {
        
        axios.delete('{{url('/api/produk/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#add').click(function(e){
        e.preventDefault();
        var produk_id = '{{$inv}}';
        axios.post('{{url('/api/produk')}}',{
            produk_id:produk_id,
            id_type_produk : $('#id_type_produk').val(),
            produk_brand : $('#produk_brand').val(),
            produk_nama : $('#produk_nama').val(),
            produk_harga : $('#produk_harga').val(),
            stok : $('#stok').val(),
            harga_modal : $('#harga_modal').val(),
        })
        .then(function (res) {
            var data = res.data
            console.log(data)
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
        axios.get('{{url('/api/produk')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            document.getElementById('produk_id').value=id;
            $("#id_type_produk_edit").val([isi.data.id_type_produk]).selectpicker('refresh');
            document.getElementById('produk_brand_edit').value=isi.data.produk_brand;
            document.getElementById('produk_nama_edit').value=isi.data.produk_nama;
            document.getElementById('produk_harga_edit').value=isi.data.produk_harga;
            document.getElementById('stok_edit').value=isi.data.stok;
            document.getElementById('harga_modal_edit').value=isi.data.harga_modal;
            $('#modal').modal('show');
        })
    }

    function editData()
    {
        axios.put('{{url('/api/produk')}}',{
            produk_id: $('#produk_id').val(),
            id_type_produk : $('#id_type_produk_edit').val(),
            produk_brand : $('#produk_brand_edit').val(),
            produk_nama : $('#produk_nama_edit').val(),
            produk_harga : $('#produk_harga_edit').val(),
            stok : $('#stok_edit').val(),
            harga_modal : $('#harga_modal_edit').val(),
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
        $("#id_type_produk").val([]).selectpicker('refresh');
        
        document.getElementById("produk_brand").value=null;
        document.getElementById("produk_nama").value=null;
        document.getElementById("produk_harga").value=null;
        document.getElementById("stok").value=null;
        document.getElementById("harga_modal").value=null;
        
    }
</script>
@endsection
