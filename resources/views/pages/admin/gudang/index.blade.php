@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Ini Halaman Gudang')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
        <div class="row">
            <div class="col-sm-4 mb-2">
                <form action="" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama Gudang</label>
                        <input type="text" class="form-control" id="nama_gudang">
                    </div>
                    <div class="form-group">
                        <label for="">Telepon Gudang</label>
                        <input type="text" class="form-control" id="telepon_gudang">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat Gudang</label>
                        <textarea name="" id="alamat_gudang" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <button type="button" class="btn btn-success btn-round" id="add"><i class="fa fa-plus"></i></button>
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
                    <th style="width: 5%">No</th>
                    <th style="width: 25%">Nama Gudang</th>
                    <th style="width: 25%">Telepon Gudang</th>
                    <th style="width: 50%">Alamat Gudang</th>
                    <th style="width: 20%">Aksi</th>
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
                    <label for="">Nama Gudang</label>
                    <input type="hidden" id="id_gudang_edit" readonly>
                    <input type="text" id="nama_gudang_edit" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Telepon Gudang</label>
                    <input type="text" id="telepon_gudang_edit" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Alamat Gudang</label>
                    <textarea name="" class="form-control" id="alamat_gudang_edit" cols="50" rows="5"></textarea>
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
          url: "{{ url('/api/gudang/datatable') }}",
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
          {
            data: 'nama_gudang'
          },
          {
            data:'telepon_gudang'
          },
          {
            data:'alamat_gudang'
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_gudang + ")' class='btn btn-danger'>Hapus</button> | " +
                "<button type='button' onclick='ambilData(" + data.id_gudang + ")' class='btn btn-success'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
      });
    });

    function deleted(id)
    {
        axios.delete('{{url('/api/gudang/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#add').click(function(e){
        e.preventDefault();
        var nama_gudang = $('#nama_gudang').val();
        var alamat_gudang = $('#alamat_gudang').val();
        var telepon_gudang = $('#telepon_gudang').val();

        axios.post('{{url('/api/gudang/')}}',{
            nama_gudang: nama_gudang,
            alamat_gudang: alamat_gudang,
            telepon_gudang:telepon_gudang
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
        axios.get('{{url('/api/gudang')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            document.getElementById('id_gudang_edit').value=isi.data.id_gudang;
            document.getElementById('nama_gudang_edit').value=isi.data.nama_gudang;
            document.getElementById('alamat_gudang_edit').value=isi.data.alamat_gudang;
            document.getElementById('telepon_gudang_edit').value=isi.data.telepon_gudang;
            $('#modal').modal('show');
        })
    }

    function editData()
    {
        var id_gudang = document.getElementById('id_gudang_edit').value;
        var nama_gudang = document.getElementById('nama_gudang_edit').value;
        var alamat_gudang = document.getElementById('alamat_gudang_edit').value;
        var telepon_gudang = document.getElementById('telepon_gudang_edit').value;
        axios.put('{{url('/api/gudang')}}',{
            'id_gudang':id_gudang,
            'nama_gudang':nama_gudang,
            'alamat_gudang':alamat_gudang,
            'telepon_gudang':telepon_gudang
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
        document.getElementById("nama_gudang").value=null;
        document.getElementById("alamat_gudang").value=null;
        document.getElementById("telepon_gudang").value=null;
    }
</script>
@endsection