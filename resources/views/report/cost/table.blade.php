<table class="table table-striped table-responsive-sm table-bordered dt-responsive nowrap table-sm" cellspacing="0"
    width="100%">
    <thead>
        <tr>
            <th>Cost ID</th>
            <th>ID Requester</th>
            <th>Name</th>
            <th>Cost Name</th>
            <th>Nominal</th>
            <th>Costing Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datax as $cost)
        <tr>
            <td>{{$cost->inv_cost}}</td>
            <td>{{$cost->id_sales}}</td>
            <td>{{$cost->nama_sales}}</td>
            <td>{{$cost->cost_nama}}</td>
            <td>{{$cost->nominal}}</td>
            <td>{{$cost->tanggal}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
