@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Sales')
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
                            <label for="">Nama Sales</label>
                            <input type="text" id="nama_sales" class="form-control rounded" placeholder="Nama Sales">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Telepon</label>
                            <input type="number" id="telepon" class="form-control rounded" placeholder="Telepon">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-sm-6">
                            <label for="">Alamat</label>
                            <textarea name="" id="alamat" cols="30" rows="1" class="form-control rounded"
                                placeholder="Alamat"></textarea>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Target</label>
                            <input type="number" class="form-control rounded" id="target" placeholder="Target">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="">Username</label>
                            <input type="text" id="username" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Password</label>
                            <input type="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Repeat Password</label>
                            <input type="password" id="password1" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2" align="center">
                            <button type="button" style="width:140px;" id="add"
                                class="btn btn-outline-success btn-sm">Add</button>
                        </div>
                        <div class="col-md-2" align="center">
                            <button type="button" style="width:140px;" class="btn btn-outline-danger btn-sm"
                                onclick="bersih()">Remove All</button>
                        </div>
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
                            <th>Nama Sales</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Target</th>
                            <th>Username</th>
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

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-minus"></i> Edit Data Sales</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama Sales</label>
                    <input type="hidden" id="id_sales">
                    <input type="text" class="form-control" id="nm_sales">
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <textarea name="" id="almt" cols="30" rows="2" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Telepon</label>
                    <input type="number" class="form-control" id="tlpn">
                </div>
                <div class="form-group">
                    <label for="">Target</label>
                    <input type="number" class="form-control rounded" id="target_edit">
                </div>
                <div class="form-group">
                    <label for="">Username</label>
                    <input type="text" class="form-control rounded" id="usern">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" class="form-control" id="pass">
                </div>
                <div class="form-group">
                    <label for="">Repeat Password</label>
                    <input type="password" class="form-control rounded" id="pass1">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="editData()" type="button">Edit Data</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        id_cabang = "{{Session()->get('cabang')}}"
        tables = $('#tabel').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('/api/sales/datatable/') }}/" + id_cabang,
            },
            columns: [{
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'nama_sales'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'telepon'
                },
                {
                    data: 'target'
                },
                {
                    data: 'username'
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return "<div>" +
                            `<button type='button' onclick='deleted("${data.id_sales}")' class='btn btn-danger btn-sm'>Hapus</button> ` +
                            `<button type='button' onclick='ambilData("${data.id_sales}")' class='btn btn-success btn-sm'>Edit</button>` +
                            "</div>";
                    }
                }
            ]
        });
    });

    $('#add').click(function (e) {
        e.preventDefault();
        var nama_sales = $('#nama_sales').val();
        var alamat = $('#alamat').val();
        var telepon = $('#telepon').val();
        var target = $('#target').val();
        var username = $('#username').val();
        var password = $('#password').val();
        var password1 = $('#password1').val();
        var id_sales = '{{$inv}}';
        axios.post('{{url('/api/sales/')}}', {
                id_sales: id_sales,
                nama_sales: nama_sales,
                alamat: alamat,
                telepon: telepon,
                target: target,
                username: username,
                password: password,
                password1: password1,
                id_cabang: id_cabang,
            })
            .then(function (res) {
                var data = res.data
                console.log(data.status)
                if (data.status == 200) {
                    bersih()
                    tables.ajax.reload()
                    toastr.info(data.message)
                } else {
                    toastr.info(data.message)
                }
            })
    })

    function deleted(id) {
        axios.delete('{{url('/api/sales/remove')}}/' + id)
            .then(function (res) {
                var data = res.data
                tables.ajax.reload()
                toastr.info(data.message)
            })
    }

    function ambilData(id) {
        axios.get('{{url('/api/sales')}}/' + id)
            .then(function (res) {
                var isi = res.data
                console.log(isi.data)
                document.getElementById('id_sales').value = id;
                document.getElementById('nm_sales').value = isi.data.nama_sales;
                document.getElementById('almt').value = isi.data.alamat;
                document.getElementById('tlpn').value = isi.data.telepon;
                document.getElementById('target_edit').value = isi.data.target;
                document.getElementById('usern').value = isi.data.username;
                document.getElementById('pass').value = isi.data.password1;
                document.getElementById('pass1').value = isi.data.password1;
                $('#modal').modal('show');
            })
    }

    function editData() {
        var id_sales = document.getElementById('id_sales').value;
        var nama_sales = document.getElementById('nm_sales').value;
        var alamat = document.getElementById('almt').value;
        var telepon = document.getElementById('tlpn').value;
        var target = document.getElementById('target_edit').value;
        var username = document.getElementById('usern').value;
        var password = document.getElementById('pass').value;
        var password1 = document.getElementById('pass1').value;
        axios.put('{{url('/api/sales')}}', {
            'id_sales': id_sales,
            'nama_sales': nama_sales,
            'alamat': alamat,
            'telepon': telepon,
            'target': target,
            'username': username,
            'password': password,
            'password1': password1,
        }).then(function (res) {
            var data = res.data
            toastr.info(data.message)
            $('#modal').modal('hide')
            tables.ajax.reload()
        })
    }

    function bersih() {
        $('#nama_sales').val('')
        $('#alamat').val('')
        $('#telepon').val('')
        $('#target').val('')
    }
</script>
@endsection