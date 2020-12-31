@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Unit')
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
                       <label for="">Nama Produk</label>
                        <select name="produk_id" id="produk_id" class="selectpicker rounded" data-width="100%" data-live-search="true" title="Pilih Produk" autocomplete="off" data-size="5">
                            
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                    <label for="">Maximum Unit Name</label>
                        <select name="maximum_unit_name" id="maximum_unit_name" data-width="100%" class="selectpicker rounded" data-live-search="true" title="Pilih Maximum Unit" data-size="5" autocomplete="off">
                            
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                    <label for="">Minimum Unit Name</label>
                        <select name="minimum_unit_name" id="minimum_unit_name" data-width="100%" class="selectpicker rounded" data-live-search="true" title="Pilih Minimum Unit" data-size="5" autocomplete="off"></select>
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="">Default Value</label>    
                        <input type="text" class="form-control rounded" id="default_value" name="default_value" placeholder="Masukan nilai nya">
                    </div>
                    <div class="form-group col-md-6">
                    <label for="">Keterangan</label>     
                        <input type="text" class="form-control rounded" id="note" name="note" value="Fix" placeholder="Keterangan">
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
                    
                    
                    <th >Nama Produk</th>
                    <th  >Maximum Unit Name</th>
                    <th >Minimum Unit Name</th>
                    <th >Default Value</th>
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

            <div class="form-row">
                    <div class="form-group col-sm-4">
                        <input type="hidden" name="id_unit" id="id_unit">
                        <label for="">Nama Produk</label>
                        <select name="produk_id_edit" id="produk_id_edit" class="selectpicker form-control" data-live-search="true" title="Masukan nama Produk" autocomplete="off">
                            
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                    <label for="">Maximum Unit Name</label>
                        <select name="maximum_unit_name_edit" id="maximum_unit_name_edit" class="selectpicker form-control" data-live-search="true" title="Masukan Maximum Unit" autocomplete="off">
                            
                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                    <label for="">Minimum Unit Name</label>
                        <select name="minimum_unit_name_edit" id="minimum_unit_name_edit" class="selectpicker form-control" data-live-search="true" title="Masukan Minimum Unit" autocomplete="off"></select>
                    </div>
                    </div>
                    <div class="form-row">
                    <div class="form-group col-md-6">
                    <label for="">Default Value</label>    
                        <input type="text" class="form-control" id="default_value_edit" name="default_value_edit" placeholder="Masukan nilai nya">
                    </div>
                    <div class="form-group col-md-6">
                    <label for="">Keterangan</label>     
                        <input type="text" class="form-control" id="note_edit" name="note_edit" placeholder="Keterangan">
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
          url: "{{ url('/api/unit/datatable') }}",
        },
        columns:[
          {
            data:'produk_nama'
          },
          {
            data:'maximum_unit_name'
          },
          {
            data:'minimum_unit_name'
          },
          {
            data:'default_value'
          },
          {
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button' onclick='deleted(" + data.id_unit + ")' class='btn btn-danger btn-sm'>Hapus</button> " +
                "<button type='button' onclick='ambilData(" + data.id_unit + ")' class='btn btn-success btn-sm'>Edit</button>" +
            "</div>" ;
            }
          }
        ]
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
        // get Produk
      axios.get('{{url('/api/satuan')}}')
                .then(function (res) {
                // handle success
                isi = res.data
                $.each(isi.data, function (i, item) {
                    
                    $('#maximum_unit_name').append("<option value="+item.id_satuan+">"+item.nama_satuan+"</option>");
                    $('#minimum_unit_name').append("<option value="+item.id_satuan+">"+item.nama_satuan+"</option>");
                    $('#maximum_unit_name_edit').append("<option value="+item.id_satuan+">"+item.nama_satuan+"</option>");
                    $('#minimum_unit_name_edit').append("<option value="+item.id_satuan+">"+item.nama_satuan+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });
           
    });

    

    function deleted(id)
    {
        
        axios.delete('{{url('/api/unit/')}}/'+id)
            .then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    $('#add').click(function(e){
        e.preventDefault();
        axios.post('{{url('/api/unit')}}',{
            produk_id: $('#produk_id').val(),
            maximum_unit_name: $('#maximum_unit_name').val(),
            minimum_unit_name: $('#minimum_unit_name').val(),
            default_value: $('#default_value').val(),
            note:$('#note').val(),
            
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
        axios.get('{{url('/api/unit')}}/'+ id)
        .then(function(res) {
            var isi = res.data
            
            document.getElementById('id_unit').value=isi.data.id_unit;
            $("#produk_id_edit").val([isi.data.produk_id]).selectpicker('refresh');
            $("#maximum_unit_name_edit").val([isi.data.maximum_unit_name]).selectpicker('refresh');
            $("#minimum_unit_name_edit").val([isi.data.minimum_unit_name]).selectpicker('refresh');
            document.getElementById('default_value_edit').value=isi.data.default_value;
            document.getElementById('note_edit').value=isi.data.note;

            $('#modal').modal('show');
        })
    }

    function editData()
    {
        

        axios.put('{{url('/api/unit')}}',{
            id_unit:$('#id_unit').val(),
            produk_id: $('#produk_id_edit').val(),
            maximum_unit_name: $('#maximum_unit_name_edit').val(),
            minimum_unit_name: $('#minimum_unit_name_edit').val(),
            default_value: $('#default_value_edit').val(),
            note: $('#note_edit').val(),
           

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
        $("#maximum_unit_name").val([]).selectpicker('refresh');
        $("#minimum_unit_name").val([]).selectpicker('refresh');
        $("#produk_id").val([]).selectpicker('refresh');
        document.getElementById("default_value").value=null;
        
        
    }
</script>
@endsection
