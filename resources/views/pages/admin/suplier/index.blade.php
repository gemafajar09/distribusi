@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Suplier')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
    <div class="row bg-white p-3 rounded mb-4" style="box-shadow:1px 1px 4px grey;">
            <div class="col-sm-12 mb-2">
                <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="">Nama Suplier</label>
                    <input type="text" class="form-control rounded" id="nama_suplier" placeholder="Nama Suplier">
                </div>
                <div class="form-group col-sm-4">
                    <label for="">Nama Perusahaan</label>
                    <input type="text" class="form-control rounded" id="nama_perusahaan" placeholder="Nama Perusahaan">
                </div>
                <div class="form-group col-sm-4">
                    <label for="">Alamat</label>
                    <textarea name="" id="alamat" cols="30" rows="1" class="form-control rounded" placeholder="Alamat"></textarea>
                </div>
                </div>
                <div class="form-row">
                <div class="form-group col-sm-3">
                    <label for="">Kota</label>
                    <input type="text" id="kota" class="form-control rounded" placeholder="Kota">
                </div>
                <div class="form-group col-sm-3">
                    <label for="">Negara</label>
                    <input type="text" id="negara" class="form-control rounded" placeholder="Negara">
                </div>
                <div class="form-group col-sm-3">
                    <label for="">Telepon</label>
                    <input type="number" id="telepon" class="form-control rounded" placeholder="Telepon">
                </div>
                <div class="form-group col-sm-3">
                    <label for="">Fax</label>
                    <input type="number" id="fax" class="form-control rounded" placeholder="Fax">
                </div>
                </div>
                <div class="form-row">
                <div class="form-group col-sm-3">
                    <label for="">Bank</label>
                    <input type="text" id="bank" class="form-control rounded" placeholder="Nama Bank">
                </div>
                <div class="form-group col-sm-3">
                    <label for="">Nama Akun</label>
                    <input type="text" id="nama_akun" class="form-control rounded" placeholder="Atas Nama">
                </div>
                <div class="form-group col-sm-3">
                    <label for="">No. Akun</label>
                    <input type="number" id="no_akun" class="form-control rounded" placeholder="No Rekening">
                </div>
                <div class="form-group col-sm-3">
                    <label for="">Note</label>
                    <textarea name="" id="note" cols="30" rows="1" class="form-control rounded" placeholder="Catatan"></textarea>
                </div>
                </div>
                </div>
                <div class="row">
                                        <div class="col-md-6" align="center">
                                            <button type="button" style="width:140px;" id="add" class="btn btn-outline-success btn-sm">Add</button>
                                        </div>
                                        <div class="col-md-6" align="center">
                                            <button type="button" style="width:140px;" class="btn btn-outline-danger btn-sm" onclick="bersih()">Remove All</button>
                                        </div>
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
                    <th style="width: 25%">Nama Suplier</th>
                    <th style="width: 50%">Nama Perusahaan</th>
                    <th style="width: 20%">Alamat</th>
                    <th style="width: 20%">Kota</th>
                    <th style="width: 20%">Negara</th>
                    <th style="width: 20%">Telepon</th>
                    <th style="width: 20%">Fax</th>
                    <th style="width: 20%">Bank</th>
                    <th style="width: 20%">No. Akun</th>
                    <th style="width: 20%">Nama Akun</th>
                    <th style="width: 20%">Note</th>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-minus"></i> Edit Data Suplier</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Nama Suplier</label>
                            <input type="hidden" name="id_suplier">
                            <input type="text" class="form-control" name="nama_suplier">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Perusahaan</label>
                            <input type="text" class="form-control" name="nama_perusahaan">
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="alamat" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Kota</label>
                            <input type="text" name="kota" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Negara</label>
                            <input type="text" name="negara" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Telepon</label>
                            <input type="number" name="telepon" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Fax</label>
                            <input type="number" name="fax" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Bank</label>
                            <input type="text" name="bank" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Akun</label>
                            <input type="text" name="nama_akun" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">No. Akun</label>
                            <input type="number" name="no_akun" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Note</label>
                            <textarea name="note" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
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
    $(document).ready(function(){
        id_cabang = {{session()->get('cabang')}}
      tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/suplier/datatable/') }}/"+id_cabang,
        },
        columns:[
        {
            data: null,
            render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
          {
            data: 'nama_suplier'
          },
          {
            data:'nama_perusahaan'
          },
          {
            data:'alamat'
          },
          {
            data:'kota'
          },
          {
            data:'negara'
          },
          {
            data:'telepon'
          },
          {
            data:'fax'
          },
          {
            data:'bank'
          },
          {
            data:'no_akun'
          },
          {
            data:'nama_akun'
          },
          {
            data:'note'
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                `<button type='button' onclick='deleted("${data.id_suplier}")' class='btn btn-danger btn-sm'>Hapus</button> ` +
                `<button type='button' onclick='ambilData("${data.id_suplier}")' class='btn btn-success btn-sm'>Edit</button>` +
            "</div>" ;
            }
          }
        ]
      });
    });

    $('#add').click(function(e){
        e.preventDefault();
        var nama_suplier = $('#nama_suplier').val();
        var nama_perusahaan = $('#nama_perusahaan').val();
        var alamat = $('#alamat').val();
        var kota = $('#kota').val();
        var negara = $('#negara').val();
        var telepon = $('#telepon').val();
        var fax = $('#fax').val();
        var bank = $('#bank').val();
        var no_akun = $('#no_akun').val();
        var nama_akun = $('#nama_akun').val();
        var note = $('#note').val();
        var id_suplier = '{{$inv}}';
        axios.post('{{url('/api/suplier/')}}',{
            id_suplier:id_suplier,
            nama_suplier: nama_suplier,
            nama_perusahaan: nama_perusahaan,
            alamat: alamat,
            kota: kota,
            negara: negara,
            telepon: telepon,
            fax: fax,
            bank: bank,
            no_akun: no_akun,
            nama_akun: nama_akun,
            note: note,
            id_cabang:id_cabang
        })
        .then(function (res) {
            var data = res.data
            console.log(data.status)
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
        axios.get('{{url('/api/suplier')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            console.log(isi.data)
            document.getElementsByName('id_suplier')[0].value=id;
            document.getElementsByName('nama_suplier')[0].value=isi.data.nama_suplier;
            document.getElementsByName('nama_perusahaan')[0].value=isi.data.nama_perusahaan;
            document.getElementsByName('alamat')[0].value=isi.data.alamat;
            document.getElementsByName('kota')[0].value=isi.data.kota;
            document.getElementsByName('negara')[0].value=isi.data.negara;
            document.getElementsByName('telepon')[0].value=isi.data.telepon;
            document.getElementsByName('fax')[0].value=isi.data.fax;
            document.getElementsByName('bank')[0].value=isi.data.bank;
            document.getElementsByName('nama_akun')[0].value=isi.data.nama_akun;
            document.getElementsByName('no_akun')[0].value=isi.data.no_akun;
            document.getElementsByName('note')[0].value=isi.data.note;
            $('#modal').modal('show');
        })
    }

    function editData()
    {
        var id_suplier = document.getElementsByName("id_suplier")[0].value;
        var nama_suplier = document.getElementsByName("nama_suplier")[0].value;
        var nama_perusahaan = document.getElementsByName("nama_perusahaan")[0].value;
        var alamat = document.getElementsByName("alamat")[0].value;
        var kota = document.getElementsByName("kota")[0].value;
        var negara = document.getElementsByName("negara")[0].value;
        var telepon = document.getElementsByName("telepon")[0].value;
        var fax = document.getElementsByName("fax")[0].value;
        var nama_akun = document.getElementsByName("nama_akun")[0].value;
        var bank = document.getElementsByName("bank")[0].value;
        var nama_akun = document.getElementsByName("nama_akun")[0].value;
        var no_akun = document.getElementsByName("no_akun")[0].value;
        var note = document.getElementsByName("note")[0].value;
        axios.put('{{url('/api/suplier')}}',{
            'id_suplier':id_suplier,
            'nama_suplier':nama_suplier,
            'nama_perusahaan':nama_perusahaan,
            'alamat':alamat,
            'kota':kota,
            'negara':negara,
            'telepon':telepon,
            'fax':fax,
            'bank':bank,
            'nama_akun':nama_akun,
            'no_akun':no_akun,
            'note':note,
        }).then(function(res){
            var data = res.data
            toastr.info(data.message)
            $('#modal').modal('hide')
            tables.ajax.reload()
        })
    }

    function deleted(id)
    {
        axios.delete('{{url('/api/suplier/remove')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    function bersih()
    {
        $('#nama_suplier').val('');
        $('#nama_perusahaan').val('');
        $('#alamat').val('');
        $('#kota').val('');
        $('#negara').val('');
        $('#telepon').val('');
        $('#fax').val('');
        $('#bank').val('');
        $('#no_akun').val('');
        $('#nama_akun').val('');
        $('#note').val('');
    }
</script>
@endsection
