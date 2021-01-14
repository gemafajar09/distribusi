<style>
    body {
        font-family: Arial;
    }

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>
<table class="table">
    <thead>
        <tr>
            <th>Invoice Id</th>
            <th>Invoice Date</th>
            <th>Customer Name</th>
            <th>Sales Name</th>
            <th>Total Credit Balance</th>
            <th>Total Payment</th>
            <th>Remainening Credit</th>
            <th>Due Date</th>
            <th>Status</th>
            <th style="text-align:center">
                Show
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach($hasil as $a)
        <tr>
            <td>{{$a['invoice_id']}}</td>
            <td>{{tanggal_indonesia($a['invoice_date'])}}</td>
            <td>{{$a['nama_customer']}}</td>
            <td>{{$a['nama_sales']}}</td>
            <td>Rp.{{number_format($a['totalsales'])}}</td>
            <td>Rp.{{number_format($a['payment'])}}</td>
            <td>Rp.{{number_format($a['remaining'])}}</td>
            <td>{{tanggal_indonesia($a['term_until'])}}</td>
            <td>{{$a['status']}}</td>
            <td style="text-align:center">
                <button onclick="cekpayment('{{$a['invoice_id']}}')" type="button" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Modal -->
<div id="payments" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="tab">
                    <button class="tablinks" active onclick="openlink(event, 'Credit')">Credit Info</button>
                    <button class="tablinks" onclick="openlink(event, 'Payment')">Payment Detail</button>
                </div>

                <div id="Credit" class="tabcontent" style="display:bloc">
                    <div class="row">
                        <div class="col-md-3"><b>Credit ID</b></div>
                        <div class="col-md-3"><input type="text" id="creditid" readonly class="form-control"></div>
                        <div class="col-md-3"><b>Sales Invoice</b></div>
                        <div class="col-md-3"><input type="text" id="invoicesales" readonly class="form-control"></div>
                        <div class="col-md-3"><b>Credit Amount</b></div>
                        <div class="col-md-3"><input type="text" id="creditamount" readonly class="form-control"></div>
                        <div class="col-md-3"><b>Due Date</b></div>
                        <div class="col-md-3"><input type="text" id="duedate" readonly class="form-control"></div>
                        <div class="col-md-3"><b>Remaining Credit Amount</b></div>
                        <div class="col-md-3"><input type="text" id="remaining" readonly class="form-control"></div>
                        <div class="col-md-3"><b>Customer Name</b></div>
                        <div class="col-md-3"><input type="text" id="customers" readonly class="form-control"></div>
                        <div class="col-md-3"><b>Tanggal Payment</b></div>
                        <div class="col-md-3"><input type="date" id="tgl_payment" class="form-control"></div>
                        <div class="col-md-12">
                            <input type="radio" name="radio" id="changedue" onclick="getdue()">&nbsp; Change Due Date <br>
                            <input type="radio" name="radio" id="getpayment" onclick="getpay()">&nbsp; Get Payment
                            <!--  -->
                            <div class="form-group" style="display: none;" id="gets">
                                <label for="">Payment Amount</label><br>
                                <input type="radio" name="cash" id="cash" onclick="bayarcash()">&nbsp;Cash
                                <input type="radio" name="cash" id="cash" onclick="bayartransfer()">&nbsp;Transfer
                                <input type="text" id="amountss" class="form-control">
                                <input type="hidden" id="statuss">
                            </div>
                            <div class="form-group" style="display: none;" id="due">
                                <label for="">Chnage Due Date</label><br>
                                <input type="date" id="duedatee" class="form-control">
                                <input type="hidden" id="id_transaksi">
                            </div>
                            <br>
                            <div>
                                <button type="button" style="display:none" class="btn btn-outline-success" id="pay"><i class="fa fa-pay">Get Payment</i></button>
                            </div>
                            <div>
                                <button type="button" style="display:none" class="btn btn-outline-success" id="change"><i class="fa fa-pay">Change Due Date</i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="Payment" class="tabcontent">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>invoice Id</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Jumlah Pembayaran</th>
                                <th>Type Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody id="detailget"></tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

<script>

    $('#pay').click(function(){
        var invoice_credit = $('#creditid').val()
        var invoice_id = $('#invoicesales').val()
        var payment = $('#amountss').val()
        var status = $('#statuss').val()
        var tgl_payment = $('#tgl_payment').val()
        axios.post("{{url('/api/addpayment')}}",{
            'payment_id':invoice_credit,
            'invoice_id':invoice_id,
            'payment':payment,
            'status':status,
            'tgl_payment':tgl_payment
        }).then(function(res){
            var data = res.data
            location.reload()
        })
    })

    function bayarcash()
    {
        $('#statuss').val('Cash')
    }

    function bayartransfer()
    {
        $('#statuss').val('Transfer')
    }

    function getdue() {
        var ceks = document.getElementById('changedue').checked;
        if (ceks == true) {
            $('#due').show()
            $('#gets').hide()
            $('#pay').hide()
            $('#change').show()
        } else {
            $('#due').hide()
        }
    }

    $('#change').click(function(){
        var id_transaksi = $('#id_transaksi').val()
        var tanggal = $('#duedatee').val()
        axios.post("{{url('/api/changedue')}}",{
            'id_transaksi':id_transaksi,
            'tanggal':tanggal
        }).then(function(res){
            var data = res.data
            if(data.status == 200)
            {
                window.location.reload()
            }else{
                $('#payments').modal('hide')
                toastr.error('Change Due Date ERROR!')
            }
        })
    })

    function getpay() {
        var ceks = document.getElementById('getpayment').checked;
        if (ceks == true) {
            $('#gets').show()
            $('#due').hide()
            $('#pay').show()
            $('#change').hide()
        } else {
            $('#gets').hide()
        }
    }

    function openlink(evt, tabactif) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabactif).style.display = "block";
        evt.currentTarget.className += " active";
    }

    function cekpayment(id) {
        var id_user = $('#id_suer').val()
        axios.post("{{url('/api/detailtrans')}}", {
            'id_transaksi': id
        }).then(function(res) {
            var data = res.data
            credit(id, id_user)
            payments(id)
            $('#detailcredit').html(data)
            $('#payments').modal()

        })
    }

    function credit(id, id_user) {
        axios.post("{{url('/api/getcredit')}}", {
            'id_transkasi': id,
            'id_user': id_user
        }).then(function(res) {
            var data = res.data.data
            if (res.data.status == 200) {
                $('#creditid').val(res.data.invoice)
                $('#invoicesales').val(data.invoice_id)
                $('#id_transaksi').val(data.id_transaksi_sales)
                $('#creditamount').val(convertToRupiah(data.totalsales))
                $('#duedate').val(data.term_until)
                $('#remaining').val(convertToRupiah(data.totalsales - res.data.payment))
                $('#customers').val(data.nama_customer)
            }
            document.getElementsByClassName("tabcontent")[0].style.display = "bloc";
        })
    }

    function payments(id)
    {
        axios.post("{{url('/api/getpayments')}}", {
            'id_transkasi': id
        }).then(function(res) {
            var data = res.data
            $('#detailget').html(data)
        })
    }
</script>
