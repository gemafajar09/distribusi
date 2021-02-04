<table id="datatable-keytable" class="table table-striped table-bordered" style="width:100%; font-size:11px">
    <thead>
        <tr style="text-align: center; font-size:10px">
            <th style="width: 10px;">No</th>
            <th>STOCK ID</th>
            <th>PRODUCT ID</th>
            <th>PRODUCT TYPE</th>
            <th>PRODUCT BRAND</th>
            <th>PRODUCT NAME</th>
            <th>PRICE</th>
            <th>Quantity</th>
            <th>TOTAL</th>
            <th>DISCOUNT</th>
            <th>AMOUNT</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0; ?>
        @foreach($init as $i => $a)
        <?php $total += round($a['amount']) ?>
        <tr>
            <td>{{$i+1}}</td>
            <td>{{$a['stok_id']}}</td>
            <td>{{$a['produk_id']}}</td>
            <td>{{$a['nama_type_produk']}}</td>
            <td>{{$a['produk_brand']}}</td>
            <td>{{$a['produk_nama']}}</td>
            <td style="text-align: right;">Rp.{{$a['produk_harga']}}</td>
            <td style="text-align: center;">{{$a['quantity']}}</td>
            <td style="text-align: right;">Rp.{{$a['total']}}</td>
            <td style="text-align: center;">{{$a['diskon']}}</td>
            <td style="text-align: right;">Rp.{{number_format(round($a['amount']))}}</td>
            <td style="text-align: center;">
                <button id="" onclick="deletes('{{$a['id_transaksi_tmp']}}')" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<input type="hidden" id="totalss" value="{{$total}}">
<script>
    var total = $('#totalss').val()
    $('#terbilang').html(terbilang(total) + ' Rupiah')
    $('#totalsales').val(convertToRupiah(total))
    $('#afterdiscount').val(convertToRupiah(total))
    $('#creditbalance').val(convertToRupiah(total))


    function deletes(id_transksi)
    {
        axios.post('{{url('/api/deleteitem')}}',{
            'id_transksi':id_transksi
        }).then(function(res){
            var data = res.data
            if(data.status == 200)
            {
                $('#isibody').load("{{ route('datatableskeranjang')}}")
            }
        })
    }
</script>
