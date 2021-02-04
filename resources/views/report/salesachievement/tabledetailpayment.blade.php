<table id="myTable" class="table table-striped table-bordered" id="tabelsatu" width="100%">
    <thead>
        <tr>
            <th>INVOICE ID</th>
            <th>INVOICE DATE</th>
            <th>DUE DATE</th>
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
            <td>{{date('d-m-Y',strtotime($a['invoice_date']))}}</td>
            <td>{{date('d-m-Y',strtotime($a['term_until']))}}</td>
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
