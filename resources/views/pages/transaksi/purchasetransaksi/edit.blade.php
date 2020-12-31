@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Edit Transaksi Purchase')
<!-- Page Content -->
@section('content')
<div class="row mt-3">
<div class="col-sm-12 border p-3 mr-3">
<button class="btn btn-info btn-sm" onclick="refresh()">Refresh</button>
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap table-sm"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                   <th>Invoice ID</th>
                   <th>Invoice Date</th>
                   <th>Type</th>
                   <th>Total</th>
                   <th>Diskon</th>
                   <th>Bayar</th>
                   <th>Total After Diskon</th>
                   <th>Payment Status</th>
                   <th>Detail</th>
                   <th>Aksi</th>
                   <th>Print</th>
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
      id_cabang = {{session()->get('cabang')}}
      load_today(id_cabang);
    function load_today(id_cabang){
      tables = $('#tabel').DataTable({
        processing : true,
        ordering:false,
        serverSide : true,
        ajax:{
          url: "{{ url('/api/report_purchase/edit_today_datatable/') }}/"+id_cabang,
        },
        columns:[
        {
            data:'invoice_id'
          },{
            data:'invoice_date'
          },
          {
            data:'transaksi_tipe'
          },
          {
            data:'total',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'diskon',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'bayar',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          }
          ,
          {
            data:'sisa',
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'status'
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return "<div><button id='detail' onclick='detail(" + data.id_transaksi_purchase + ")'><i class='fa fa-list-ol'></i></button></div>";
            }  
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return "<div><button id='detail' onclick='edit(" + data.id_transaksi_purchase + ")'><i class='fa fa-edit'></i></button></div>";
            }  
          },
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return "<div><button id='detail' onclick='print(" + data.id_transaksi_purchase + ")'><i class='fa fa-print'></i></button></div>";
            }  
          }
        ]
      });
    }
});

    function detail(id_transaksi_purchase){
      $('#detailinvoice').html('');
      cabang = {{session()->get('cabang')}};
      axios.get('{{url('/api/report_purchase/editdetailpurchase/')}}/'+cabang+'/'+id_transaksi_purchase)
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

    function edit(id_transaksi_purchase){
      window.open(`{{url('/purchase_transaksi/edit_purchase_order/')}}/`+id_transaksi_purchase);
    }

    function print(id_transaksi_purchase){
      
      window.open(`{{url('/purchase/report_purchase_edit_today/')}}/`+id_transaksi_purchase);
    }

    function refresh(){
      tables.ajax.reload();
    }

</script>
@endsection
