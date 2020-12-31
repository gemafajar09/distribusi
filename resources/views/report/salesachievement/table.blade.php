<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<table id="myTable" class="table table-striped table-bordered" id="tabelsatu" width="100%">
    <thead>
        <tr>
            <th>INVOICE ID</th>
            <th>INVOICE DATE</th>
            <th>INVOICE TYPE</th>
            <th>CUSTOMER NAME</th>
            <th>TOTAL</th>
            <th>DISCOUT</th>
            <th>DP</th>
            <th>TOTAL AFTER DISCOUNT</th>
            <th>PAY STATUS</th>
            <th>TRANSACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $total=0;
            $totaldis=0;
        ?>
        @foreach ($data as $d)
        <?php 
            $total += $d->totalsales;
            $totaldis = $d->totalsales - $d->diskon;
        ?>
        <tr>
            <td>{{$d->invoice_id}}</td>
            <td>{{$d->invoice_date}}</td>
            <td>{{$d->transaksi_tipe}}</td>
            <td>{{$d->nama_customer}}</td>
            <td>{{$d->totalsales}}</td>
            <td>{{$d->diskon}}</td>
            <td>{{$d->dp}}</td>
            <td>{{$totaldis}}</td>
            <td>{{$d->status}}</td>
            <td>{{$d->sales_type}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <td colspan="2">Total Transaction :</td>
        <td colspan="4"><?=$total?></td>
        <td colspan="2">Total Profit / Loss :</td>
        <td colspan="4">1</td>
    </tfoot>
</table>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>