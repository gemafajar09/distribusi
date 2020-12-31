@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Ini Halaman Stok')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
<div class="col-sm-9 border p-3 mr-3">
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap table-sm"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Stok ID</th>
                    <th>Produk Description</th>
                    <th>Stok Amount</th>
                    <th>Capital Price</th>
                    <th>Selling Price</th>
                    <th>Cabang</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="col border p-3 bg-white rounded">
    <h6>View : </h6>
    <input type="radio" name="view" id="all"> All Stock <br>
    <input type="radio" name="view" id="warehouse"> Based On Warehouse  <br>
    <select name="warehouse" id="filter_warehouse" class="form-control mt-3"></select>
    <br>
    <div class="text-center">
    <button class="btn btn-success btn-sm btn-block" onclick="location.reload()">Refresh Report</button>
    <button class="btn btn-danger btn-sm btn-block" onclick="print_report()">Generate Report</button>
    </div>

</div>
</div>

<script>
    $(document).ready(function(){
      id_cabang = {{session()->get('cabang')}}
      function load_all(id_cabang){
      tables = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/inventory/datatable/') }}/"+id_cabang,
        },
        columns:[
        {
            data:'stok_id'
          },{
            data:'produk_nama'
          },
          {
            data:'jumlah'
          },
          {
            data:'capital_price',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'produk_harga',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'nama_cabang'
          }
        ]
      });
    }
      function load_spesifik(id_cabang,id_warehouse){
      tables1 = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: '{{ url("/api/inventory/datatable/")}}/'+id_cabang+'/'+id_warehouse,
        },
        columns:[
            {
            data:'stok_id'
          },{
            data:'produk_nama'
          },
          {
            data:'jumlah'
          },
          {
            data:'capital_price',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'produk_harga',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'nama_cabang'
          }
        ]
      });
    }
    $('#all').click(function(){
        var other = document.getElementById('all').checked
        if (other == true) {
          if ( $.fn.DataTable.isDataTable('#tabel') ) {
        $('#tabel').DataTable().destroy();
      }
            load_all(id_cabang)
            $('#filter_warehouse').html([''])
        }
    });

    $('#warehouse').click(function(){
        $('#filter_warehouse').html([''])
        var other = document.getElementById('warehouse').checked
        if (other == true) {
          axios.get('{{url('/api/gudangcabang/')}}/'+id_cabang)
            .then(function(res){
              isi = res.data
              data = isi.data
              $('#filter_warehouse').append(`<option>Pilih Gudang</option>`)
              for (let index = 0; index < data.length; index++) {
                
                $('#filter_warehouse').append(`<option value='${data[index].id_gudang}'>${data[index].nama_gudang}</option>`) 
                
              }
              
            })
            
        }
    });

    $('#filter_warehouse').change(function(){
        filter = $('#filter_warehouse').val()
        if ( $.fn.DataTable.isDataTable('#tabel') ) {
  $('#tabel').DataTable().destroy();
}
        load_spesifik(id_cabang,filter);
    })

    
});

function print_report(){
        all = $('#all').val()
        warehouse = $('#warehouse').val()
        filter = $('#filter_warehouse').val()
        if(filter == null){
            // all data
            window.open(`{{url('/inventory/report_stok/')}}/`+id_cabang);
        }else{
            window.open(`{{url('/inventory/report_stok/')}}/`+id_cabang+'/'+filter);
        }
    }

</script>
@endsection
