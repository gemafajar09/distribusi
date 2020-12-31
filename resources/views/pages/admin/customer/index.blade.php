@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Customer')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
        <div class="row bg-white p-3 rounded mb-3">
            <div class="col-sm-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Nama Customer</label>
                        <input type="text" name="nama_customer" id="nama_customer" class="form-control rounded" placeholder="Masukan Nama Customer">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control rounded" placeholder="Masukan Nama Perusahaan">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Credit Plafond</label>
                        <input type="number" name="credit_plafond" id="credit_plafond" class="form-control rounded" placeholder="Masukan Credit Plafond">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control rounded" placeholder="Masukan Alamat">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Kota</label>
                        <input type="text" name="kota" id="kota" class="form-control rounded" placeholder="Masukan Kota">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Negara</label>
                        <input type="text" name="negara" id="negara" class="form-control rounded" placeholder="Masukan Negara">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="">Telepon</label>
                        <input type="number" name="telepon" id="telepon" class="form-control rounded" placeholder="Ex : 08..">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Kartu Kredit</label>
                        <input type="number" name="kartu_kredit" id="kartu_kredit" class="form-control rounded" placeholder="Ex : 4156..">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Fax</label>
                        <input type="number" name="fax" id="fax" class="form-control rounded" placeholder="Ex : 9982..">
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="">Nama Sales</label>
                        <select name="id_sales" id="id_sales" class="selectpicker rounded" data-size="5" data-width="100%" data-live-search="true" autocomplete="off" title="Pilih Nama Sales">
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Keterangan</label>
                        <input type="text" name="note" id="note" class="form-control rounded" placeholder="Masukan Keterangan">
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
<div class="row">
<div class="col-sm-12">
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th >Nama Customer</th>
                    <th >Nama Perusahaan</th>
                    <th>Credit Plafond</th>
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
                    <div class="form-group col-md-4">
                        <input type="hidden" id="id_customer">
                        <label for="">Nama Customer</label>
                        <input type="text" name="nama_customer_edit" id="nama_customer_edit" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Nama Perusahaan</label>
                        <input type="text" name="nama_perusahaan_edit" id="nama_perusahaan_edit" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Credit Plafond</label>
                        <input type="text" name="credit_plafond_edit" id="credit_plafond_edit" class="form-control">
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
                        <select name="id_sales_edit" id="id_sales_edit" class="selectpicker" data-live-search="true" data-width="100%" data-size="5" autocomplete="off">
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
        id_cabang = {{session()->get('cabang')}}
      tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/customer/datatable/') }}/"+id_cabang,
        },
        columns:[
        
          
          {
            data:'nama_customer'
          },
          {
            data:'nama_perusahaan'
          },
          {
            data:'credit_plafond'
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
                "<button type='button' onclick='deleted(" + data.id_customer + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
                "<button type='button' onclick='ambilData(" + data.id_customer + ")' class='btn btn-success btn-sm'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
      });

      // get sales
      axios.get('{{url('/api/getsales/')}}/'+id_cabang)
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
        var credit_plafond = $('#credit_plafond').val();
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
            credit_plafond:credit_plafond,
            alamat: alamat,
            negara: negara,
            kota: kota,
            telepon: telepon,
            kartu_kredit: kartu_kredit,
            fax: fax,
            id_sales: id_sales,
            note:note,
            id_cabang:id_cabang
            
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
            document.getElementById('credit_plafond_edit').value=isi.data.credit_plafond;
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
        var credit_plafond = $('#credit_plafond_edit').val();
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
            'credit_plafond':credit_plafond,
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
        document.getElementById("credit_plafond").value=null;
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
