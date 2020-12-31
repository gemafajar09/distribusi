@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Report Purchase Return')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
<div class="col-sm-9 border p-3 mr-3">
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap table-sm"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                   <th>RETURN ID</th>
                   <th>SUPPLIER NAME</th>
                   <th>RETURN DATE</th>
                   <th>TOTAL RETURN</th>
                   <th>DETAIL</th>
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
    <select name="ket_waktu" id="ket_waktu" class="form-control">
    <option value="">Silahkan Pilih</option>
      <option value="0">All Report</option>
      <option value="1">Today</option>
      <!-- <option value="2">Weekly</option> -->
      <option value="3">Monthly</option>
      <option value="4">Yearly</option>
      <option value="5">Range</option>
    </select>
    <br>
    <div id="wadah"></div>
    <br>
    <div class="range" id="range">
      <legend>Range</legend>
      <input type="date" class="form-control" id="waktu_awal" onchange="range_report()"><br>
      <input type="date" class="form-control" id="waktu_akhir" onchange="range_report()">
    </div>
    <br>
    <div class="text-center">
    <button class="btn btn-success btn-sm btn-block" onclick="location.reload()">Refresh Report</button>
    <button class="btn btn-danger btn-sm btn-block" onclick="print_report()">Generate Report</button>
    </div>
</div>
</div>

<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-minus"></i> Detail Purchase</h5>
            </div>
            <div class="modal-body" style="overflow-y: scroll;height:400px;">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <td>Produk Nama</td>
                      <td>Quantity</td>
                      <td>Price</td>
                    </tr>
                  </thead>
                  <tbody id="detailinvoice">

                  </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

      $("#waktu_awal" ).prop( "disabled", true );
      $("#waktu_akhir" ).prop( "disabled", true );
      id_cabang = {{session()->get('cabang')}}
      function load_all(id_cabang){
      tables = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/report_purchase_return/datatable/') }}/"+id_cabang,
        },
        columns:[
        {
            data:'return_id'
          },{
            data:'nama_suplier'
          },
          {
            data:'return_date'
          },
          {
            data:'price',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return `<div><button id='detail' onclick='detail("${data.return_id}")'><i class='fa fa-list-ol'></i></button></div>`;
            }  
          }
        ]
      });
    }
    function load_today(id_cabang){
      tables = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/report_purchase_return/today_datatable/') }}/"+id_cabang,
        },
        columns:[
        {
            data:'return_id'
          },{
            data:'nama_suplier'
          },
          {
            data:'return_date'
          },
          {
            data:'price',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return `<div><button id='detail' onclick='detail("${data.return_id}")'><i class='fa fa-list-ol'></i></button></div>`;
            }  
          }
        ]
      });
    }
    function load_month(month,year,id_cabang){
      tables = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/report_purchase_return/month_datatable/') }}/"+month+'/'+year+'/'+id_cabang,
        },
        columns:[
        {
            data:'return_id'
          },{
            data:'nama_suplier'
          },
          {
            data:'return_date'
          },
          {
            data:'price',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return `<div><button id='detail' onclick='detail("${data.return_id}")'><i class='fa fa-list-ol'></i></button></div>`;
            }  
          }
        ]
      });
    }function load_year(year,id_cabang){
      tables = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/report_purchase_return/year_datatable/') }}/"+year+'/'+id_cabang,
        },
        columns:[
        {
            data:'return_id'
          },{
            data:'nama_suplier'
          },
          {
            data:'return_date'
          },
          {
            data:'price',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return `<div><button id='detail' onclick='detail("${data.return_id}")'><i class='fa fa-list-ol'></i></button></div>`;
            }  
          }
        ]
      });
    }
    

    $('#ket_waktu').change(function(){
          $("#waktu_awal" ).prop( "disabled", true );
          $("#waktu_akhir" ).prop( "disabled", true );
        nilai = $('#ket_waktu').val();
        $('#wadah').html('');
        // weekly
        if(nilai == 0){
          if ( $.fn.DataTable.isDataTable('#tabel') ) {
              $('#tabel').DataTable().destroy();
            }
            load_all(id_cabang)
        }else if(nilai ==1){
          if ( $.fn.DataTable.isDataTable('#tabel') ) {
              $('#tabel').DataTable().destroy();
            }
          load_today(id_cabang)
        }else if(nilai ==2){
              console.log("oke2");
        }else if(nilai ==3){
          $('#wadah').append(`<select name="year" id="year" class="form-control">
            <?php 
              $year = date('Y');
              $min = $year - 10;
              $max = $year;
              for( $i=$max; $i>=$min; $i-- ) {
                echo '<option value='.$i.'>'.$i.'</option>';
              }
            ?>
          </select><br> <select class="form-control" name="month" id="month"><option>Pilih Bulan</option>
            <?php for( $m=1; $m<=12; ++$m ) { 
              $month_label = date('F', mktime(0, 0, 0, $m, 1));
            ?>
              <option value="<?php echo $m; ?>"><?php echo $month_label; ?></option>
            <?php } ?>
              </select>  `);
        }else if(nilai ==4){
          $('#wadah').append(`<select name="year_filter" id="year_filter" class="form-control"><option>Pilih Tahun</option>
            <?php 
              $year = date('Y');
              $min = $year - 10;
              $max = $year;
              for( $i=$max; $i>=$min; $i-- ) {
                echo '<option value='.$i.'>'.$i.'</option>';
              }
            ?>
          </select>`);
        }else if(nilai ==5){
          $("#waktu_awal" ).prop( "disabled", false );
          $("#waktu_akhir" ).prop( "disabled", false );
        }
    })

    $('#wadah').on('change', '#month', function() {
        month = $('#month').val();
        year = $('#year').val();
        if ( $.fn.DataTable.isDataTable('#tabel') ) {
              $('#tabel').DataTable().destroy();
            }
        load_month(month,year,id_cabang)
    });

    $('#wadah').on('change', '#year_filter', function() {
        year = $('#year_filter').val();
        if ( $.fn.DataTable.isDataTable('#tabel') ) {
              $('#tabel').DataTable().destroy();
            }
        load_year(year,id_cabang)
    });

    

});
function load_range(waktu_awal,waktu_akhir,id_cabang){
      tables = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/report_purchase_return/range_datatable/') }}/"+waktu_awal+'/'+waktu_akhir+'/'+id_cabang,
        },
        columns:[
        {
            data:'return_id'
          },{
            data:'nama_suplier'
          },
          {
            data:'return_date'
          },
          {
            data:'price',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return `<div><button id='detail' onclick='detail("${data.return_id}")'><i class='fa fa-list-ol'></i></button></div>`;
            }  
          }
        ]
      });
    }

function range_report(){
    waktu_awal = $('#waktu_awal').val();
    if(waktu_awal == ""){
      return false;
    }
    waktu_akhir = $('#waktu_akhir').val();
    if(waktu_akhir == ""){
      return false;
    }
    if ( $.fn.DataTable.isDataTable('#tabel') ) {
              $('#tabel').DataTable().destroy();
            }
    load_range(waktu_awal,waktu_akhir,id_cabang)
}


function print_report(){
  ket_waktu = $('#ket_waktu').val();
  if(ket_waktu == 0){
    window.open(`{{url('/purchase_return/report_purchase_return/')}}/`+id_cabang);
  }else if(ket_waktu == 1){
    window.open(`{{url('/purchase_return/report_purchase_return_today/')}}/`+id_cabang);
  }else if(ket_waktu == 2){
    // window.open(`{{url('/purchase_return/report_purchase_return_today')}}`);
  }else if(ket_waktu == 3){
        month = $('#month').val();
        year = $('#year').val();
        window.open(`{{url('/purchase_return/report_purchase_return_month/')}}/`+month+'/'+year+'/'+id_cabang);
  }
  else if(ket_waktu == 4){
        
        year = $('#year_filter').val();
        window.open(`{{url('/purchase_return/report_purchase_return_year/')}}/`+year+'/'+id_cabang);
  }
  else if(ket_waktu == 5){
        
          waktu_awal = $('#waktu_awal').val();
          if(waktu_awal == ""){
            return false;
          }
          waktu_akhir = $('#waktu_akhir').val();
          if(waktu_akhir == ""){
            return false;
          }
        window.open(`{{url('/purchase_return/report_purchase_return_range/')}}/`+waktu_awal+'/'+waktu_akhir+'/'+id_cabang);
  }
  
    }

    function detail(return_id){
      $('#detailinvoice').html('');
      cabang = {{session()->get('cabang')}};
      axios.post('{{url('/api/report_purchase_return/detailpurchasereturn')}}',{
          'return_id':return_id,
          'id_cabang':cabang
      })
        .then(function(res){
          isi = res.data;
          result = isi.data;
          console.log(result);
          for (let index = 0; index < result.length; index++) {
            // console.log(result[index]['produk_nama']);
            $('#detailinvoice').append(`
              <tr>
                  <td>${result[index]['produk_nama']}</td>
                  <td>${result[index]['stok_quantity']}</td>
                  <td>${result[index]['total_price']}</td>
              </tr>
            `);
          }
          $('#modal').modal('show');
        });
    }

</script>
@endsection
