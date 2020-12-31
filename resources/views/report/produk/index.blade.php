@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Report Produk')
<!-- Page Content -->
@section('content')
<input style="margin-left:66%;" type="checkbox" onclick="anjim(this)"> Check Semua
<div class="row mt-3">
<div class="col-sm-9 border p-3 mr-3">
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap table-sm"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                   <th>PRODUK ID</th>
                   <th>PRODUK BRAND</th>
                   <th>NAMA PRODUK</th>
                   <th>JUMLAH</th>
                   <th>PRINT</th>
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
          url: "{{ url('/api/produk_report/datatable/') }}/"+id_cabang,
        },
        columns:[
        {
            data:'produk_id'
          },
          {
            data:'produk_brand'
          },
          {
            data:'produk_nama'
          },
          {
            data:'quantity'
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return `<div><input type="checkbox" id="print" name="print[]" value="${data.produk_id}"> Print</div>`;
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
          url: "{{ url('/api/produk_report/today_datatable/') }}/"+id_cabang,
        },
        columns:[
        {
            data:'produk_id'
          },
          {
            data:'produk_brand'
          },
          {
            data:'produk_nama'
          },
          {
            data:'quantity'
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
              return `<div><input type="checkbox" id="print" name="print[]" value="${data.produk_id}"> Print</div>`;
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
          url: "{{ url('/api/produk_report/month_datatable/') }}/"+month+'/'+year+'/'+id_cabang,
        },
        columns:[
        {
            data:'produk_id'
          },
          {
            data:'produk_brand'
          },
          {
            data:'produk_nama'
          },
          {
            data:'quantity'
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
              return `<div><input type="checkbox" id="print" name="print[]" value="${data.produk_id}"> Print</div>`;
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
          url: "{{ url('/api/produk_report/year_datatable/') }}/"+year+'/'+id_cabang,
        },
        columns:[
        {
            data:'produk_id'
          },
          {
            data:'produk_brand'
          },
          {
            data:'produk_nama'
          },
          {
            data:'quantity'
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
              return `<div><input type="checkbox" id="print" name="print[]" value="${data.produk_id}"> Print</div>`;
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
          url: "{{ url('/api/produk_report/range_datatable/') }}/"+waktu_awal+'/'+waktu_akhir+'/'+id_cabang,
        },
        columns:[
        {
            data:'produk_id'
          },
          {
            data:'produk_brand'
          },
          {
            data:'produk_nama'
          },
          {
            data:'quantity'
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
              return `<div><input type="checkbox" id="print" name="print[]" value="${data.produk_id}"> Print</div>`;
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


// function print_report(){
//   ket_waktu = $('#ket_waktu').val();
//   if(ket_waktu == 0){
//     window.open(`{{url('/purchase_return/produk_report/')}}/`+id_cabang);
//   }else if(ket_waktu == 1){
//     window.open(`{{url('/purchase_return/produk_report_today/')}}/`+id_cabang);
//   }else if(ket_waktu == 2){
//     // window.open(`{{url('/purchase_return/produk_report_today')}}`);
//   }else if(ket_waktu == 3){
//         month = $('#month').val();
//         year = $('#year').val();
//         window.open(`{{url('/purchase_return/report_purchase_return_month/')}}/`+month+'/'+year+'/'+id_cabang);
//   }
//   else if(ket_waktu == 4){
        
//         year = $('#year_filter').val();
//         window.open(`{{url('/purchase_return/report_purchase_return_year/')}}/`+year+'/'+id_cabang);
//   }
//   else if(ket_waktu == 5){
        
//           waktu_awal = $('#waktu_awal').val();
//           if(waktu_awal == ""){
//             return false;
//           }
//           waktu_akhir = $('#waktu_akhir').val();
//           if(waktu_akhir == ""){
//             return false;
//           }
//         window.open(`{{url('/purchase_return/report_purchase_return_range/')}}/`+waktu_awal+'/'+waktu_akhir+'/'+id_cabang);
//   }
  
//     }

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
          for (var index = 0; index < result.length; index++) {
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

    function print_report(){
      // all
      var checkboxes = document.getElementsByName('print[]');
      var vals = "";
        for (var i=0, n=checkboxes.length;i<n;i++) 
        {
            if (checkboxes[i].checked) 
            {
                vals += ","+checkboxes[i].value;
            }
        }
        if (vals) vals = vals.substring(1);
        ket_waktu = $('#ket_waktu').val();
        status = $('#ket_status').val();
        if(ket_waktu == 0){
          window.open(`{{url('/produk/report_all_stock_spesifik/')}}/`+id_cabang+'/'+vals);
        }else if(ket_waktu == 1){
          window.open(`{{url('/produk/report_all_stock_spesifik_today/')}}/`+id_cabang+'/'+vals);
        }else if(ket_waktu == 2){
          // window.open(`{{url('/purchase/report_purchase_today')}}`);
        }else if(ket_waktu == 3){
              month = $('#month').val();
              year = $('#year').val();
              window.open(`{{url('/produk/report_all_stock_spesifik_month/')}}/`+month+'/'+year+'/'+id_cabang+'/'+vals);
        }
        else if(ket_waktu == 4){
              
              year = $('#year_filter').val();
              window.open(`{{url('/produk/report_all_stock_spesifik_year/')}}/`+year+'/'+id_cabang+'/'+vals);
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
              window.open(`{{url('/produk/report_all_stock_spesifik_range/')}}/`+waktu_awal+'/'+waktu_akhir+'/'+id_cabang+'/'+vals);
        }
  
    }
    
    function anjim(checkboxElem){
        if (checkboxElem.checked == true) {
          $('input[name="print[]"]').prop('checked', true);
        }else{
          $('input[name="print[]"]').prop('checked', false);
        }
    }
    
    

</script>
@endsection
