<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<table id="myTable" class="table table-striped table-bordered" id="tabelsatu">
    <thead>
        <tr>
            <th>STOCK ID</th>
            <th>PRODUCT NAME</th>
            <th>QUANTITY</th>
            <th>CAPITAL PRICE</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $a)
        <tr>
            <td>{{$a['produk_id']}}</td>
            <td>{{$a['produk_nama']}}</td>
            <td>{{$a['quantity']}}</td>
            <td>Rp.{{number_format($a['capital_price'])}}</td>
            <td style="text-align:center">
                <button class="btn btn-danger btn-sm" onclick="pilihstok('{{$a['stok_id']}}')" style="button">PILIH</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>