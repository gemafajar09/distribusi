<table id="datatable-keytable" class="table table-striped table-bordered" style="width:100%; font-size:11px">
    <thead>
        <tr>
            <th>PRODUCT ID</th>
            <th>PRODUCT DESCRIPTION</th>
            <th>QUANTITY</th>
            <th>PRICE</th>
            <th>DISKON</th>
            <th>NOTE</th>
            <th>TOTAL</th>
            <th style="text-align:center">ACTION</th>
        </tr>
    </thead>
    <tbody>
    <?php $total = 0; ?>
    @foreach($init as $i => $a)
    <?php $total += $a['amount']; ?>
        <tr>
            <td>{{$a['stok_id']}}</td>
            <td>{{$a['produk_nama']}}</td>
            <td>{{$a['quantity']}}</td>
            <td>Rp.{{number_format($a['produk_harga'])}}</td>
            <td>Rp.{{number_format($a['diskon'])}}</td>
            <td>{{$a['note']}}</td>
            <td>Rp.{{number_format($a['amount'])}}</td>
            <td style="text-align:center">
            <button id="" onclick="deletes('{{$a['id_tmpreturn']}}')" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
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

    
    function deletes(id_transksi)
    {
        axios.post('{{url('/api/deleteitemr')}}',{
            'id_transksi':id_transksi
        }).then(function(res){
            var data = res.data
            if(data.status == 200)
            {
                $('#isibody').load("{{ route('tmpdata')}}")
            }
        })
    }
</script>