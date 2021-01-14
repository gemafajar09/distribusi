<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<table id="myTable" class="table table-striped table-bordered" id="tabelsatu" width="100%">
    <thead>
        <tr>
            <th>INVOICE ID</th>
            <th>INVOICE DATE</th>
            <th>SALES NAME</th>
            <th>CUSTOMER NAME</th>
            <th>CREDIT TOTAL</th>
            <th>TOTAL PAYMENT</th>
            <th>REMAINING CREDIT</th>
            <th>PAYMENT STATUS</th>
            <th>TRANSACTION</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($hasil as $a)
        <tr>
        <td>{{$a['invoice_id']}}</td>
        <td>{{tanggal_indonesia($a['invoice_date'])}}</td>
        <td>{{$a['nama_sales']}}</td>
        <td>{{$a['nama_customer']}}</td>
        <td>Rp.{{number_format($a['totalsales'])}}</td>
        <td>Rp.{{number_format($a['payment'])}}</td>
        <td>Rp.{{number_format($a['remaining'])}}</td>
        <td>{{$a['status']}}</td>
        <td>{{$a['sales_type']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>
