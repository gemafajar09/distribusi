@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Type Produk')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
    <div class="row bg-white p-3 rounded mb-4" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama Type Produk</label>
                        <input type="text" name="nama_type_produk" id="nama_type_produk" class="form-control rounded" placeholder="Masukan Type Produk Ex : MAKANAN">
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
                    
                    <th >ID Type Produk</th>
                    <th >Nama Type Produk</th>
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
                    <input type="hidden" id="id_type_produk">
                    <label for="">Nama Type Produk</label>
                    <input type="text" name="nama_type_produk_edit" id="nama_type_produk_edit" class="form-control">
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
          url: "{{ url('/api/type/datatable') }}",
        },
        columns:[
        
          {
            data: 'id_type_produk'
          },
          {
            data:'nama_type_produk'
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_type_produk + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
                "<button type='button' onclick='ambilData(" + data.id_type_produk + ")' class='btn btn-success btn-sm'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
      });
           
    });

    

    function deleted(id)
    {
        
        axios.delete('{{url('/api/type/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#add').click(function(e){
        e.preventDefault();
        
        var nama_type_produk = $('#nama_type_produk').val();

        axios.post('{{url('/api/type')}}',{
            nama_type_produk: nama_type_produk,
            
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
        axios.get('{{url('/api/type')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            document.getElementById('id_type_produk').value=isi.data.id_type_produk;
            document.getElementById('nama_type_produk_edit').value=isi.data.nama_type_produk;
            $('#modal').modal('show');
        })
    }

    function editData()
    {
        var id_type_produk = $('#id_type_produk').val(); 
        var nama_type_produk = $('#nama_type_produk_edit').val();
        ;

        axios.put('{{url('/api/type')}}',{
            'id_type_produk':id_type_produk,
            'nama_type_produk':nama_type_produk,
            
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
        document.getElementById("nama_type_produk").value=null;
    }
</script>
@endsection
