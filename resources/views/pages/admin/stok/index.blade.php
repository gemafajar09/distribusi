@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Halaman Stok')
<!-- Page Content -->
@section('content')
<div class="mt-2">
    <div class="x_content">
        <div class="row">
            <div class="col-sm-3">
               <div class="form-group">
                   
                   <select name="id_gudang" id="id_gudang" class="form-control rounded" title="Pilih Gudang">
                            @foreach ($gudang as $c)
                            <option value="{{$c->id_gudang}}">{{$c->nama_gudang}}</option>
                            @endforeach
                    </select>
               </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                <button class="btn btn-warning" onclick="cleaning()">Stok Cleaning</button>
                </div>
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
                    <th >Jumlah Quantity</th>
                    <th>Stok In Price</th> 
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
<!-- <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div> -->

<script>
    $(document).ready(function(){
        id_cabang = {{session()->get('cabang')}}
        id_gudang = $('#id_gudang').val();
        load_gudang(id_gudang);
    //     load_all(id_cabang)
    //     function load_all(id_cabang){
    //     tables = $('#tabel').DataTable({
    //     processing : true,
    //     serverSide : true,
    //     ajax:{
    //       url: "{{ url('/api/stok/datatable/') }}/"+id_cabang,
    //     },
    //     columns:[
    //       {
    //         data:'produk_nama'
    //       },
    //       {
    //         data:'jumlah'
    //       },
    //       {
    //         data:'capital_price'
    //       },
    //       {
    //         data:'stok_harga'
    //       }
    //     ]
    //   });
    // }
    function load_gudang(id_gudang){
        tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/stok/datatablegudang/') }}/"+id_gudang,
        },
        columns:[
          {
            data:'produk_nama'
          },
          {
            data:'jumlah'
          },
          {
            data:'stok_harga'
          }
        ]
      });
    }

    //   data inventory per gudang
    $('#id_gudang').change(function(){
        id_gudang = $('#id_gudang').val();
        if ( $.fn.DataTable.isDataTable('#tabel') ) {
              $('#tabel').DataTable().destroy();
            }
        load_gudang(id_gudang)
    });

      // get Produk
      axios.get('{{url('/api/getproduk/')}}')
                .then(function (res) {
                // handle success
                isi = res.data
                $.each(isi.data, function (i, item) {
                    
                    $('#produk_id').append("<option value="+item.produk_id+">"+item.produk_nama+"</option>");
                    $('#produk_id_edit').append("<option value="+item.produk_id+">"+item.produk_nama+"</option>");
                 });
                 $('.selectpicker').selectpicker('refresh');
            });

            $('#produk_id').on('change', function (e) {
                var optionSelected = $("option:selected", this); 
                let id = this.value;
                axios.get('{{url('/api/getunit/')}}/'+id)
                    .then(function(res){
                        isi = res.data
                        console.log(isi.data)
                        panjang = isi.data.length
                        $('#wadah').html('');
                        if (panjang == 3){
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah').append(`<label>${isi.data[index].maximum_unit_name}</label> <input type='text' id='unit${index+1}' value='0'> `)   
                            }
                        }else if (panjang == 2){
                            $('#wadah').append(`<input type='hidden' id='unit${1}' value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah').append(`<label>${isi.data[index].maximum_unit_name}</label> <input type='text' id='unit${index+2}' value='0'> `)  
                            }
                        }else if (panjang == 1){
                            $('#wadah').append(`<input type='hidden' id='unit${1}' value='null'> `)
                            $('#wadah').append(`<input type='hidden' id='unit${2}' value='null'> `)
                            for (let index = 0; index < panjang; index++) {
                                $('#wadah').append(`<label>${isi.data[index].maximum_unit_name}</label> <input type='text' id='unit${index+3}' value='0'> `)  
                            }
                        }
                    })
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
        let produk_id = $('#produk_id').val();
        let unit1 = $('#unit1').val();
        let unit2 = $('#unit2').val();
        let unit3 = $('#unit3').val();
        
        axios.get('{{url('/api/getunit/')}}/'+produk_id)
                    .then(function(res){
                        isi = res.data
                        panjang = isi.data.length
                        if(unit1 == 'null' && unit2 == 'null'){
                            unit3 = (parseInt(unit3))* isi.data[0].default_value;
                        }else if(unit1 == 'null') {
                            unit2 = (parseInt(unit2)) * isi.data[0].default_value;
                            unit3 = (parseInt(unit2)+parseInt(unit3))* isi.data[1].default_value; 
                        }else{
                            unit1 = parseInt(unit1) * isi.data[0].default_value;
                            unit2 = (parseInt(unit1)+parseInt(unit2)) * isi.data[1].default_value;
                            unit3 = (parseInt(unit2)+parseInt(unit3))* isi.data[2].default_value; 
                        }
                        console.log(unit3)
                    });
        
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
        document.getElementById("note").value=null;
        
    }
    
    function cleaning(){
        var txt;
        var r = confirm("Cleaning Stok Akan Dilakukan ?");
        if (r == true) {
            axios.get('{{url('/api/stokcleaning')}}/'+id_cabang)
            .then(function(res){
                location.reload();
            })
        } else {
            txt = "You pressed Cancel!";
        }
    }
</script>
@endsection
