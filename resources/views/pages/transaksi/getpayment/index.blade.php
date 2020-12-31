@extends('layout.index')

@section('page-title','Get Payment')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Filter By</label><br>
                            <Select id="filter" name="filter" class="select2" style="width: 100%;">
                                <option value="Customer Name">Customer Name</option>
                            </Select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">&nbsp;</label><br>
                            <input type="text" name="cari" onkeyup="carigetpayment(this)" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 py-3">
        <p>List Of Credit</p>
        <div class="card">
            <div class="card-body">
                <div id="listcredit"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12 py-3">
        <p>List Of Detail Customer Credit</p>
        <div class="card">
            <div class="card-body">
                <div id="detailcredit"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function carigetpayment(data)
    {
        var nama = data.value
        axios.post("{{url('/api/paymentcustomer')}}",{
            'nama':nama
        }).then(function(res){
            var datas = res.data
            $('#listcredit').html(datas)
        })
    }
</script>
@endsection 