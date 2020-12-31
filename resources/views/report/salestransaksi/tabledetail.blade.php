<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<table id="myTable" class="table table-striped table-bordered" id="tabelsatu">
    <thead>
        <tr>
            <th>Item Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Diskon</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $a)
        <tr>
            <td>{{$a['produk_id']}} | {{$a['produk_nama']}}</td>
            <td>Rp.{{number_format($a['capital_price'])}}</td>
            <td>{{$a['quantity']}}</td>
            <td>{{$a['total']}}</td>
            <td>{{$a['diskon']}}</td>
            <td>{{$a['amount']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    // $(document).ready( function () {
    //     $('#myTable').html('');
    //     $('#myTable').DataTable();
    // } );
</script>