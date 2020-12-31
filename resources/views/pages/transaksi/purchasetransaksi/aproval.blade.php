@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Aproval Purchase Order')
<!-- Page Content -->
@section('content')
 
<div class="row">
<div class="col-sm-12">
<button class="btn btn-info" onclick="refresh()"><i class="fa fa-refresh"></i> Refresh</button>
    <div class="card-box table-responsive">
        <table id="tabel" class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap"
            cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th >Invoice ID</th>
                    <th >Invoice Date</th>
                    <th >Nama Suplier</th>
                    <th>Total</th>
                    <th>Diskon</th>
                    <th>Bayar</th>
                    <th>Sisa</th>
                    <th>Detail</th>
                    <th>Aproval</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                </tr>
            </tbody>
        </table>
        <!-- <button onclick="register()" class="btn btn-danger btn-sm">Register Transaksi</button> -->
        
    </div>
</div>
</div>

<!-- Modal -->
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
      {{$cabang = session()->get('cabang')}}
      tables = $('#tabel').DataTable({
        processing : true,
        serverSide : true,
        ajax:{
          url: '{{ url("/api/purchasedetail/datatable/".$cabang) }}', 
        },
        columns:[
          {
            data:'invoice_id',
            defaultContent:""
          },
          {
            data:'invoice_date',
            defaultContent:""
          },
          {
            data:'nama_suplier',
            defaultContent:""
          },
          {
            data:'total',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'diskon',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'bayar',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
          },
          {
            data:'sisa',
            defaultContent:"",
            render: $.fn.dataTable.render.number( ',', '.', 2 )
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
            return "<div><select id='approval'><option value='1'>Approval</option><option value='2'>Not Approval</option></select></div>";
            }  
          }
          ,
          {
            defaultContent:"",
            data: null,
            render: function(data, type, row, meta) {
            return "<div>" +
                "<button type='button'  onclick='aproval(" + data.id_transaksi_purchase + ")' class='btn btn-success btn-sm'>Simpan</button> "+
            "</div>" ;
            }
          }
        ]
      });
            });


    function aproval(id){
        aprove = $('#approval').val();
        axios.post('{{url('/api/purchasedetail/approval/')}}',{
            'id':id,
            'status':aprove
        }).then(function(res){
            var data = res.data
            tables.ajax.reload()
            toastr.info(data.message)
        })
    }

    function refresh(){
      tables.ajax.reload()
    }

    function detail(invoice_id){
      $('#detailinvoice').html('');
      cabang = {{session()->get('cabang')}};
      axios.get('{{url('/api/purchasedetailproduk/')}}/'+cabang+'/'+invoice_id)
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
