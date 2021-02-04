@extends('layout.index')

<!-- main content -->
<!-- page Title -->
@section('page-title','Aproval Sales Transaction')
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
                    <th>Invoice ID</th>
                    <th>Invoice Date</th>
                    <th>Nama Sales</th>
                    <th>Nama Toko</th>
                    <th>Total</th>
                    <th>Diskon</th>
                    <th>Transaksi Tipe</th>
                    <th>Status</th>
                    <th>Aproval</th>
                    <th style="text-align: center;">Detail</th>
                    <!--<th style="text-align: center;">Action</th>-->
                </tr>
            </thead>
            <tbody>
                @foreach($list as $a)
                <tr>
                    <td>{{$a['invoice_id']}}</td>
                    <td>{{$a['invoice_date']}}</td>
                    <td>{{$a['nama_sales']}}</td>
                    <td>{{$a['nama_customer']}}</td>
                    <td>Rp.{{number_format($a['totalsales'])}}</td>
                    <td>Rp.{{number_format($a['diskon'])}}</td>
                    <td>{{$a['transaksi_tipe']}}</td>
                    <td>{{$a['status']}}</td>
                    <td>
                        <select name="aproves" id="aprove" onchange="approves('{{$a['id_transaksi_sales']}}','{{$a['invoice_id']}}',this)" class="form-control">
                            <option value="">-SELECT-</option>
                            <option value="1">Approve</option>
                            <option value="2">Disapprove</option>
                        </select>
                    </td>
                    <td style="text-align: center;">
                        <button type="button" onclick="cekdetail('{{$a['invoice_id']}}')" class="btn btn-success">Detail</button>
                    </td>
                    <!--<td style="text-align: center;">-->
                    <!--    <button onclick="approves('{{$a['id_transaksi_sales']}}','{{$a['invoice_id']}}')" class="btn btn-outline-success">Approve</button>-->
                    <!--</td>-->
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- <button onclick="register()" class="btn btn-danger btn-sm">Register Transaksi</button> -->

    </div>
</div>
</div>

<div id="detailm" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Detail Transaction</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="detailsemua">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
    function approves(id,invoice,status)
    {
        var status = status.value;
        // alert(status);
        axios.post("{{url('/api/approval')}}",{
            'id_transaksi':id,
            'status':status,
            'invoice_id':invoice
        }).then(function(res){
            window.location.reload()
        })
    }

    function cekdetail(id)
    {
        axios.get("{{url('/api/detailapp')}}/"+id).then(function(res){
            var data = res.data
            $('#detailm').modal()
            $('#detailsemua').html(data)
        })
    }

    function refresh(){
        window.location.reload()
    }
</script>

@endsection
