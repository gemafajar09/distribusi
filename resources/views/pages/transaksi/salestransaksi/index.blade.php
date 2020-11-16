@extends('layout.index')

@section('page-title','Sales Trasaction')
@section('content')
<div class="row">
    <div class="col-md-12" id="entrySales" style="display:none">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-lg-6 col-xl-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Sales Type</label>
                                    <select name="salesType" id="salesType" class="select2" style="width:100%">
                                        <option value="">SALES TYPE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Invoice ID</label>
                                    <input type="text" style="border-radius:3px" name="invoiceid" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Invoice Date</label>
                                    <input type="date" style="border-radius:3px" name="invoiceDate" placeholder="invoiceDate" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Transaction Type</label><br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" style="border-radius:3px" name="transType" placeholder="transType"> <b>Cash</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" style="border-radius:3px" name="transType" placeholder="transType"> <b>Credit</b>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Term Until</label>
                                    <input type="date" style="border-radius:3px" name="invoiceDate" placeholder="invoiceDate" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-lg-6 col-xl-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Salesman ID</label>
                                    <select name="salesmanId" id="salesmanId" class="select2" style="width:100%">
                                        <option value="">SALES ID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Salesman Nama</label>
                                    <input type="text" readonly style="border-radius:3px" class="form-control" name="namaSales" id="namasales">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Customer ID</label>
                                    <select name="customerid" id="customerid" class="select2" style="width:100%">
                                        <option value="">CUSTOMER ID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Customer Nama</label>
                                    <input type="text" readonly style="border-radius:3px" class="form-control" name="namaSales" id="namasales">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="">Note</label>
                                <textarea name="" id="" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-lg-2 col-xl-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Stok ID</label>
                                    <select name="stockId" id="stockId" class="select2" style="width:100%">
                                        <option value="">STOCK ID</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Product ID</label>
                                    <input type="text" style="border-radius:3px" name="productid" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Product Type</label>
                                    <input type="text" style="border-radius:3px" name="producttype" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Product Brand</label>
                                    <input type="text" style="border-radius:3px" name="productbrand" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Product Name</label>
                                    <input type="text" style="border-radius:3px" name="productname" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-lg-6 col-xl-5">
                        <div class="card">
                            <div class="card-body">
                                <b>Quantity</b>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input readonly type="text" class="form-control" id="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Box</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input readonly type="text" class="form-control" id="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Cup</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input  type="text" class="form-control" id="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Box</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input  type="text" class="form-control" id="">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Cup</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Price</div>
                                            </div>
                                            <input type="text" class="form-control" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <Button type="button" id="show" onclick="show()" style="display:block" class="btn btn-outline-warning btn-round"><i class="fa fa-plus"></i></Button>
                        <Button type="button" id="hide" onclick="hide()" style="display:none" class="btn btn-outline-warning btn-round"><i class="fa fa-close"></i></Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function show()
    {
        $('#entrySales').show()
        $('#show').hide()
        $('#hide').show()
    }

    function hide()
    {
        $('#entrySales').hide()
        $('#show').show()
        $('#hide').hide()
    }
</script>
@endsection