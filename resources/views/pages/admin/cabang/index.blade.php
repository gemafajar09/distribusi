@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Cabang')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
    <div class="row bg-white p-3 rounded mb-3" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-5 mb-2">
                <form action="" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">Nama Cabang</label>
                        <input type="text" id="nama_cabang" class="form-control rounded">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea name="" id="alamat" cols="30" rows="1" class="form-control rounded"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Kode Cabang</label>
                        <input type="text" id="kode_cabang" class="form-control rounded">
                    </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label for="">No. Telepon</label>
                    <input type="number" id="telepon" class="form-control rounded">
                </div>
                <div class="form-group">
                    <label for="">E-mail</label>
                    <input type="email" id="email" class="form-control rounded">
                </div>
                <div class="row">
                                        <div class="col-md-5" align="center">
                                            <button type="button" style="width:140px;" id="add" class="btn btn-outline-success btn-sm">Add</button>
                                        </div>
                                        <div class="col-md-5" align="center">
                                            <button type="button" style="width:140px;" class="btn btn-outline-danger btn-sm" onclick="bersih()">Remove All</button>
                                        </div>
                                    </div>
                </form>
            </div>
    </div>
    <div class="row">
            <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <table id="tabel" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Cabang</th>
                                <th>Alamat</th>
                                <th>Kode Cabang</th>
                                <th>No. Telepon</th>
                                <th>E-mail</th>
                                <th>Aksi</th>
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
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-minus"></i> Edit Data Cabang</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama Cabang</label>
                    <input type="hidden" id="id_cabang">
                    <input type="text" class="form-control" id="nm_cabang">
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <textarea name="" id="almt" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Kode Cabang</label>
                    <input type="text" class="form-control" id="kd_cabang">
                </div>
                <div class="form-group">
                    <label for="">No. Telepon</label>
                    <input type="text" class="form-control" id="tlpn">
                </div>
                <div class="form-group">
                    <label for="">E-mail</label>
                    <input type="text" class="form-control" id="emails">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" onclick="editData()" type="button">Edit Data</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
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
          url: "{{ url('/api/cabang/datatable') }}",
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
          {
            data: 'nama_cabang'
          },
          {
            data:'alamat'
          },
          {
            data:'kode_cabang'
          },
          {
            data:'telepon'
          },
          {
            data:'email'
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_cabang + ")' class='btn btn-danger'>Hapus</button> | " +
                "<button type='button' onclick='ambilData(" + data.id_cabang + ")' class='btn btn-success'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
      });
    });

    $('#add').click(function(e){
        e.preventDefault();
        var nama_cabang = $('#nama_cabang').val();
        var alamat = $('#alamat').val();
        var kode_cabang = $('#kode_cabang').val();
        var telepon = $('#telepon').val();
        var email = $('#email').val();

        axios.post('{{url('/api/cabang/')}}',{
            nama_cabang: nama_cabang,
            alamat: alamat,
            kode_cabang:kode_cabang,
            telepon:telepon,
            email:email
        })
        .then(function (res) {
            var data = res.data
            
            if(data.status == 200)
            {
                tables.ajax.reload()
                toastr.info(data.message)
                bersih()
            }else{
                toastr.info(data.message)
            }
        })
    })

    function deleted(id)
    {
        axios.delete('{{url('/api/cabang/remove/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    function ambilData(id)
    {
        axios.get('{{url('/api/cabang/')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            console.log(isi.data)
            document.getElementById('id_cabang').value=isi.data.id_cabang;
            document.getElementById('nm_cabang').value=isi.data.nama_cabang;
            document.getElementById('kd_cabang').value=isi.data.kode_cabang;
            document.getElementById('almt').value=isi.data.alamat;
            document.getElementById('tlpn').value=isi.data.telepon;
            document.getElementById('emails').value=isi.data.email;
            $('#modal').modal('show');
        })
    }

    function editData()
    {
        var id_cabang = document.getElementById('id_cabang').value;
        var nama_cabang = document.getElementById('nm_cabang').value;
        var alamat = document.getElementById('almt').value;
        var kode_cabang = document.getElementById('kd_cabang').value;
        var telepon = document.getElementById('tlpn').value;
        var email = document.getElementById('emails').value;
        axios.put('{{url('/api/cabang/')}}',{
            'id_cabang':id_cabang,
            'nama_cabang':nama_cabang,
            'alamat':alamat,
            'kode_cabang':kode_cabang,
            'telepon':telepon,
            'email':email
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
        document.getElementById('nama_cabang').value =null;
        document.getElementById('kode_cabang').value =null;
        document.getElementById('alamat').value =null;
        document.getElementById('telepon').value =null;
        document.getElementById('email').value =null;
    }
</script>
@endsection
