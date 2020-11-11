@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Ini Halaman Customer')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
        <div class="row">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Nama Customer</label>
                        <input type="text" name="nama_customer" id="nama_customer" class="form-control" placeholder="Masukan Nama Customer">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control" placeholder="Masukan Nama Perusahaan">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukan Alamat">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Kota</label>
                        <input type="text" name="kota" id="kota" class="form-control" placeholder="Masukan Alamat">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Negara</label>
                        <input type="text" name="negara" id="negara" class="form-control" placeholder="Masukan Negara">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Telepon</label>
                        <input type="text" name="telepon" id="telepon" class="form-control" placeholder="Ex : 08..">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Kartu Kredit</label>
                        <input type="text" name="kartu_kredit" id="kartu_kredit" class="form-control" placeholder="Ex : 4156..">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Fax</label>
                        <input type="text" name="fax" id="fax" class="form-control" placeholder="Ex : 9982..">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Pilih Nama Sales</label>
                        <select name="id_sales" id="id_sales" class="selectpicker form-control" data-live-search="true" autocomplete="off" title="Pilih Nama Sales">
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Keterangan</label>
                        <input type="text" name="note" id="note" class="form-control" placeholder="Masukan Keterangan">
                    </div>
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
                    
                    <th >ID Customer</th>
                    <th >Nama Customer</th>
                    <th >Nama Perusahaan</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Nama Sales</th>
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
                    <div class="form-group col-md-6">
                        <input type="hidden" id="id_customer">
                        <label for="">Nama Customer</label>
                        <input type="text" name="nama_customer_edit" id="nama_customer_edit" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan_edit" id="nama_perusahaan_edit" class="form-control">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat_edit" id="alamat_edit" class="form-control" >
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Kota</label>
                        <input type="text" name="kota_edit" id="kota_edit" class="form-control" >
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Negara</label>
                        <input type="text" name="negara_edit" id="negara_edit" class="form-control">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Telepon</label>
                        <input type="text" name="telepon_edit" id="telepon_edit" class="form-control" placeholder="Ex : 08..">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Kartu Kredit</label>
                        <input type="text" name="kartu_kredit_edit" id="kartu_kredit_edit" class="form-control" placeholder="Ex : 4156..">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Fax</label>
                        <input type="text" name="fax_edit" id="fax_edit" class="form-control" placeholder="Ex : 9982..">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Pilih Nama Sales</label>
                        <select name="id_sales_edit" id="id_sales_edit" class="selectpicker form-control" data-live-search="true" autocomplete="off">
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Keterangan</label>
                        <input type="text" name="note_edit" id="note_edit" class="form-control" placeholder="Masukan Keterangan">
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
          url: "{{ url('/api/customer/datatable') }}",
        },
        columns:[
        
          {
            data: 'id_customer'
          },
          {
            data:'nama_customer'
          },
          {
            data:'nama_perusahaan'
          },
          {
            data:'alamat'
          },
          {
            data:'telepon'
          },
          {
            data:'nama_sales'
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_customer + ")' class='btn btn-danger btn-sm'>Hapus</button> | " +
                "<button type='button' onclick='ambilData(" + data.id_customer + ")' class='btn btn-success btn-sm'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
      });

      // get Customer
      axios.get('{{url('/api/getsales')}}')
                .then(function (res) {
                // handle success
                let isi = res.data
                $.each(isi.data, function (i, item) {  
                    $('#id_sales').append("<option value="+item.id_sales+">"+item.nama_sales+"</option>");
                    $('#id_sales_edit').append("<option value="+item.id_sales+">"+item.nama_sales+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });
           
    });

    

    function deleted(id)
    {  
        axios.delete('{{url('/api/customer/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#add').click(function(e){
        e.preventDefault();
        
        var nama_customer = $('#nama_customer').val();
        var nama_perusahaan = $('#nama_perusahaan').val();
        var alamat = $('#alamat').val();
        var negara = $('#negara').val();
        var kota = $('#kota').val();
        var telepon = $('#telepon').val();
        var kartu_kredit = $('#kartu_kredit').val();
        var fax = $('#fax').val();
        var id_sales = $('#id_sales').val();
        var note = $('#note').val();

        axios.post('{{url('/api/customer')}}',{
            nama_customer: nama_customer,
            nama_perusahaan: nama_perusahaan,
            alamat: alamat,
            negara: negara,
            kota: kota,
            telepon: telepon,
            kartu_kredit: kartu_kredit,
            fax: fax,
            id_sales: id_sales,
            note:note
            
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
        axios.get('{{url('/api/customer')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            document.getElementById('id_customer').value=isi.data.id_customer;
            document.getElementById('nama_customer_edit').value=isi.data.nama_customer;
            document.getElementById('nama_perusahaan_edit').value=isi.data.nama_perusahaan;
            document.getElementById('alamat_edit').value=isi.data.alamat;
            document.getElementById('negara_edit').value=isi.data.negara;
            document.getElementById('kota_edit').value=isi.data.kota;
            document.getElementById('telepon_edit').value=isi.data.telepon;
            document.getElementById('kartu_kredit_edit').value=isi.data.kartu_kredit;
            document.getElementById('fax_edit').value=isi.data.fax;
            $("#id_sales_edit").val([isi.data.id_sales]).selectpicker('refresh');
            document.getElementById('note_edit').value=isi.data.note;
            $('#modal').modal('show');
        })
    }

    function editData()
    {
        var id_customer = $('#id_customer').val(); 
        var nama_customer = $('#nama_customer_edit').val();
        var nama_perusahaan = $('#nama_perusahaan_edit').val();
        var alamat = $('#alamat_edit').val();
        var negara = $('#negara_edit').val();
        var kota = $('#kota_edit').val();
        var telepon = $('#telepon_edit').val();
        var kartu_kredit = $('#kartu_kredit_edit').val();
        var fax = $('#fax_edit').val();
        var id_sales = $('#id_sales_edit').val();
        var note = $('#note_edit').val();
        

        axios.put('{{url('/api/customer')}}',{
            'id_customer':id_customer,
            'nama_customer':nama_customer,
            'nama_perusahaan':nama_perusahaan,
            'alamat':alamat,
            'negara':negara,
            'kota':kota,
            'telepon':telepon,
            'kartu_kredit':kartu_kredit,
            'fax':fax,
            'id_sales':id_sales,
            'note':note,
            
        }).then(function(res){
           
            var data = res.data
            toastr.info(data.message)
            $('#modal').modal('hide')
            tables.ajax.reload()
            
        })
    }

    function bersih()
    {
        document.getElementById("nama_customer").value=null;
        document.getElementById("nama_perusahaan").value=null;
        document.getElementById("alamat").value=null;
        document.getElementById("negara").value=null;
        document.getElementById("kota").value=null;
        document.getElementById("telepon").value=null;
        document.getElementById("kartu_kredit").value=null;
        document.getElementById("fax").value=null;
        $("#id_sales").val([]).selectpicker('refresh');
        document.getElementById("note").value=null;
    }
</script>
@endsection