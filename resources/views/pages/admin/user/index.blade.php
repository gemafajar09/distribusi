@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman User')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
    <div class="row bg-white p-3 rounded mb-4" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="">Nama User</label>
                        <input type="text" name="nama_user" id="nama_user" class="form-control rounded" placeholder="Masukan Nama User">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Email</label>
                        <input type="email" name="email" id="email" class="form-control rounded" placeholder="Masukan Email">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="">Username</label>
                        <input type="text" name="username" id="username" class="form-control rounded" placeholder="Masukan Username">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Password</label>
                        <input type="password" name="password" id="password" class="form-control rounded">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-sm-4">
                        <label for="">Telepon</label>
                        <input type="number" name="telepon" id="telepon" class="form-control rounded">
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="">Level</label>
                        <select name="level" id="level" class="form-control rounded">
                        @foreach($role as $r)
                                <option value="{{$r->id_role}}">{{$r->nama_role}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="">Cabang</label>
                        <select name="id_cabang" id="id_cabang" class="form-control rounded" title="Pilih Cabang">
                            @foreach ($cabang as $c)
                            <option value="{{$c->id_cabang}}">{{$c->nama_cabang}}</option>
                            @endforeach
                        </select>
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
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap table-sm"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                    
                    
                    <th >Nama User</th>
                    <th >Username</th>
                    <th>Level</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Nama Cabang</th>
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
                        <input type="hidden" id="id_user">
                        <label for="">Nama User</label>
                        <input type="text" name="nama_user_edit" id="nama_user_edit" class="form-control" placeholder="Masukan Nama User">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Email</label>
                        <input type="email" name="email_edit" id="email_edit" class="form-control" placeholder="Masukan Email">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="">Username</label>
                        <input type="text" name="username_edit" id="username_edit" class="form-control" placeholder="Masukan Username">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Password</label>
                        <input type="password" name="password_edit" id="password_edit" class="form-control">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="">Level</label>
                        <select name="level_edit" id="level_edit" class="form-control">
                            @foreach($role as $r)
                                <option value="{{$r->id_role}}">{{$r->nama_role}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">Cabang</label>
                        <select name="id_cabang_edit" id="id_cabang_edit" class="form-control" title="Pilih Cabang">
                            @foreach ($cabang as $c)
                            <option value="{{$c->id_cabang}}">{{$c->nama_cabang}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="form-group">
                        <label for="">Telepon</label>
                        <input type="number" name="telepon_edit" id="telepon_edit" class="form-control">
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
          url: "{{ url('/api/user/datatable') }}",
        },
        columns:[
          {
            data:'nama_user'
          },
          {
            data:'username'
          },
          {
            data:'nama_role'
          },
          {
            data:'telepon'
          },
          {
            data:'email'
          },
          {
            data:'nama_cabang'
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_user + ")' class='btn btn-danger btn-sm'>Hapus</button> | " +
                "<button type='button' onclick='ambilData(" + data.id_user + ")' class='btn btn-success btn-sm'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
      });
           
    });

    

    function deleted(id)
    {
        
        axios.delete('{{url('/api/user/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#add').click(function(e){
        e.preventDefault();
        axios.post('{{url('/api/user/')}}',{
            nama_user: $('#nama_user').val(),
            username: $('#username').val(),
            password: $('#password').val(),
            level: $('#level').val(),
            telepon: $('#telepon').val(),
            email: $('#email').val(),
            id_cabang: $('#id_cabang').val(),
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
        axios.get('{{url('/api/user/')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            document.getElementById('id_user').value=isi.data.id_user;
            document.getElementById("nama_user_edit").value=isi.data.nama_user;
            document.getElementById("username_edit").value=isi.data.username;
            document.getElementById("level_edit").value=isi.data.level;
            document.getElementById("telepon_edit").value=isi.data.telepon;
            document.getElementById("email_edit").value=isi.data.email;
            document.getElementById("id_cabang_edit").value=isi.data.id_cabang;
            document.getElementById("password_edit").value=isi.data.password;

            $('#modal').modal('show');
        })
    }

    function editData()
    {
        

        axios.put('{{url('/api/user/')}}',{
            id_user:$('#id_user').val(),
            nama_user: $('#nama_user_edit').val(),
            username: $('#username_edit').val(),
            password: $('#password_edit').val(),
            level: $('#level_edit').val(),
            telepon: $('#telepon_edit').val(),
            email: $('#email_edit').val(),
            id_cabang: $('#id_cabang_edit').val(),

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
        document.getElementById("nama_user").value=null;
        document.getElementById("username").value=null;
        document.getElementById("level").value=null;
        document.getElementById("telepon").value=null;
        document.getElementById("email").value=null;
        document.getElementById("id_cabang").value=null;
        document.getElementById("password").value=null;
    }
</script>
@endsection
