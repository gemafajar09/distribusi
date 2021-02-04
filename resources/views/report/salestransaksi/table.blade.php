<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<table id="myTable" class="table table-striped table-bordered" style="width:100%; font-size:11px">
    <thead>
        <tr style="text-align: center; font-size:10px">
            <th style="width: 10px;">No</th>
            <th>NAMA CUSTOMER</th>
            <th>INVOICE ID</th>
            <th>INVOICE DATE</th>
            <th>TRANSACTION TYPE</th>
            <th>TERM UNTIL</th>
            <th>SALESMAN</th>
            <th>AMOUNT</th>
            <th>DISCOUNT</th>
            <th>DP</th>
            <th>STATUS</th>
            <th>APPROVE</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0; ?>
        @foreach($init as $i => $a)
        <?php $total += $a['totalsales']?>
        <tr>
            <td>{{$i+1}}</td>
            <td>{{$a['nama_customer']}}</td>
            <td>{{$a['invoice_id']}}</td>
            <td>{{date('d-m-Y',strtotime($a['invoice_date']))}}</td>
            <td>{{$a['transaksi_tipe']}}</td>
            <td>{{date('d-m-Y',strtotime($a['term_until']))}}</td>
            <td>{{$a['nama_sales']}}</td>
            <td>Rp.{{number_format($a['totalsales'])}}</td>
            <td>Rp.{{number_format($a['diskon'])}}</td>
            <td>Rp.{{number_format($a['dp'])}}</td>
            <td>{{$a['status']}}</td>
            <td><?php
            if($a['approve'] == 1)
            {
                echo "Approval";
            }elseif($a['approve'] == 2)
            {
                echo "Disapprove";
            }elseif($a['approve'] == 0)
            {
                echo "Belum Diperiksa";
            }
            ?></td>
            <td style="text-align: center;">
                <button id="" onclick="cekdetail('{{$a['invoice_id']}}')" class="btn btn-outline-success btn-sm"><i
                        class="fa fa-info"></i></button>
                <button onclick="cetak('{{$a['invoice_id']}}','{{$a['transaksi_tipe']}}','{{$a['id_cabang']}}')"
                    class="btn btn-outline-danger btn-sm"><i class="fa fa-print"></i></button>
                <a href="{{ route('transaksi_sales_edit', $a['invoice_id'])}}" id="tombol" class="btn btn-outline-warning btn-sm"><i class="fa fa-edit"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7">Total Pendapatan :</th>
            <td colspan="6" align=left>Rp. <?= number_format($total)?></td>
        </tr>
    </tfoot>
</table>

<script>
    function cetak(invoice_id,salestype,id_cabang)
    {
        window.open("{{url('/sales_transaksi/fakturs')}}/" + invoice_id + "/" + salestype + "/" + id_cabang,
                            '_blank');
    }

    function cekdetail(id)
    {
        axios.get("{{url('/api/sales_transaksi/detailreport')}}/"+id).then(function(res){
            var data = res.data
            $('#detailm').modal()
            $('#detailsemua').html(data)
        })
    }

    $('#myTable').DataTable();

</script>
