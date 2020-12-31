<table id="datatable-keytable" class="table table-striped table-bordered" style="width:100%; font-size:11px">
    <thead>
        <tr>
            <th>INVOICE ID</th>
            <th>INVOICE DATE</th>
            <th>TYPE</th>
            <th>SALES NAME</th>
            <th>CUSTOMER NAME</th>
            <th>TOTAL</th>
            <th>DISCOUNT</th>
            <!--<th>DP</th>-->
            <th>TOTAL AFTER DISCOUNT</th>
            <th>TRANSACTION</th>
        </tr>
    </thead>
    <tbody>
    @foreach($detail as $a)
        <tr onclick="ambil('{{$a->id_transaksi_sales}}')">
            <td>{{$a->invoice_id}}</td>
            <td>{{$a->invoice_date}}</td>
            <td>{{$a->sales_type}}</td>
            <td>{{$a->sales_type}}</td>
            <td>{{$a->nama_customer}}</td>
            <td>Rp.{{number_format($a->totalsales)}}</td>
            <td>{{$a->diskon}}%</td>
            <!--<td>0</td>-->
            <td>Rp.{{$a->totalsales - ($a->totalsales * $a->diskon / 100)}}</td>
            <td>{{$a->transaksi_tipe}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    function ambil(id)
    {
        axios.post("{{url('/api/ambil')}}",{
            'id_transaksi':id
        }).then(function(res){
            var data = res.data
            console.log(data)
            $('#idsalesinv').val(data.invoice_id)
            $('#salesid').val(data.sales_id)
            $('#invtype').val(data.transaksi_tipe)
            $('#customerid').val(data.customer_id)
            $('#namacustomer').val(data.nama_customer)
            $('#paystatus').val(data.status)
            $('#caridata').modal('hide')
        })
    }
</script>