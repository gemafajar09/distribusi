<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// API Front END
Route::group(['namespace' => 'Admin'], function () {
    // api produk
    Route::get('produk/datatable', 'ProductController@datatable');
    Route::get('produk/{id}', 'ProductController@get');
    Route::get('produk', 'ProductController@get');
    Route::post('produk', 'ProductController@add');
    Route::put('produk', 'ProductController@edit');
    Route::delete('produk/{id}', 'ProductController@remove');

    // api stok
    Route::get('stok/datatable/{id_cabang}', 'StokController@datatable');
    Route::get('stok/datatablegudang/{id_gudang}', 'StokController@datatablegudang');
    Route::get('stok/{id}', 'StokController@get');
    Route::get('stok', 'StokController@get');
    Route::post('stok', 'StokController@add');
    Route::put('stok', 'StokController@edit');
    Route::delete('stok/{id}', 'StokController@remove');
    Route::get('stokcleaning/{id_cabang}', 'StokController@stokCleaning');
    Route::get('stokprice/{id_cabang}', 'StokController@stokprice');
    Route::get('profit/{id_cabang}/{date}', 'StokController@profit');

    // api cabang
    Route::get('cabang/datatable', 'CabangController@datatable');
    Route::get('cabang/{id}', 'CabangController@get');
    Route::get('cabang', 'CabangController@get');
    Route::post('cabang', 'CabangController@add');
    Route::put('cabang', 'CabangController@edit');
    Route::delete('cabang/remove/{id}', 'CabangController@remove');

    // api satuan
    Route::get('satuan/datatable', 'SatuanController@datatable');
    Route::get('satuan/{id}', 'SatuanController@get');
    Route::get('satuan', 'SatuanController@get');
    Route::post('satuan', 'SatuanController@add');
    Route::put('satuan', 'SatuanController@edit');
    Route::delete('satuan/remove/{id}', 'SatuanController@remove');

    // api cost
    Route::get('cost/datatable/{id_cabang}', 'CostController@datatable');
    Route::get('cost/{id}', 'CostController@get');
    Route::get('cost', 'CostController@get');
    Route::post('cost', 'CostController@add');
    Route::put('cost', 'CostController@edit');
    Route::delete('cost/remove/{id}', 'CostController@remove');


    // api sales
    Route::get('sales/datatable/{id_cabang}', 'SalesController@datatable');
    Route::get('sales/{id}', 'SalesController@get');
    Route::get('sales', 'SalesController@get');
    Route::post('sales', 'SalesController@add');
    Route::put('sales', 'SalesController@edit');

    Route::delete('sales/{id}', 'SalesController@remove');
    Route::get('getsales/{id_cabang}', 'SalesController@getSales');
    Route::delete('sales/remove/{id}', 'SalesController@remove');
    Route::post('loginsales', 'SalesController@loginsales');

    // api suplier
    Route::get('suplier/datatable/{id_cabang}', 'SuplierController@datatable');
    Route::get('suplier/{id}', 'SuplierController@get');
    Route::get('suplier', 'SuplierController@get');
    Route::post('suplier', 'SuplierController@add');
    Route::put('suplier', 'SuplierController@edit');
    Route::delete('suplier/remove/{id}', 'SuplierController@remove');
    Route::get('getsuplier/{id_cabang}', 'SuplierController@getSuplier');
    Route::get('getsuplier/produk/{id}/{id_cabang}', 'SuplierController@getSuplierProduk');

    // api customer
    Route::get('customer/datatable/{id_cabang}', 'CustomerController@datatable');
    Route::get('customer/{id}', 'CustomerController@get');
    Route::get('customer', 'CustomerController@get');
    Route::post('customer', 'CustomerController@add');
    Route::put('customer', 'CustomerController@edit');
    Route::delete('customer/{id}', 'CustomerController@remove');

    // api brand
    Route::get('type/datatable', 'TypeController@datatable');
    Route::get('type/{id}', 'TypeController@get');
    Route::get('type', 'TypeController@get');
    Route::post('type', 'TypeController@add');
    Route::put('type', 'TypeController@edit');
    Route::delete('type/{id}', 'TypeController@remove');
    Route::get('gettype', 'TypeController@getType');

    // api gudang
    Route::get('gudang/datatable', 'GudangController@datatable');
    Route::get('gudang/{id}', 'GudangController@get');
    Route::get('gudang', 'GudangController@get');
    Route::post('gudang', 'GudangController@add');
    Route::put('gudang', 'GudangController@edit');
    Route::delete('gudang/{id}', 'GudangController@remove');
    Route::get('gudangcabang/{id_cabang}', 'GudangController@gudangcabang');

    // api spesial
    Route::get('spesial/datatable', 'SpesialHargaController@datatable');
    Route::get('spesial/{id}', 'SpesialHargaController@get');
    Route::get('spesial', 'SpesialHargaController@get');
    Route::post('spesial', 'SpesialHargaController@add');
    Route::put('spesial', 'SpesialHargaController@edit');
    Route::delete('spesial/{id}', 'SpesialHargaController@remove');

    // api spesial
    Route::get('user/datatable', 'UserController@datatable');
    Route::get('user/{id}', 'UserController@get');
    Route::get('user', 'UserController@get');
    Route::post('user', 'UserController@add');
    Route::put('user', 'UserController@edit');
    Route::delete('user/{id}', 'UserController@remove');
    Route::put('profile', 'UserController@edit_profile');

    // api spesial
    Route::get('unit/datatable', 'UnitController@datatable');
    Route::get('unit/{id}', 'UnitController@get');
    Route::get('unit', 'UnitController@get');
    Route::post('unit', 'UnitController@add');
    Route::put('unit', 'UnitController@edit');
    Route::delete('unit/{id}', 'UnitController@remove');
    Route::get('getunit/{id}', 'UnitController@getUnit');

    // untuk opname
    Route::get('getunitopname/{id}/{cabang}', 'UnitController@get_unit_opname');
    Route::get('getcustomer/{id_cabang}', 'SpesialHargaController@getCustomer');
    Route::get('getproduk', 'SpesialHargaController@getProduk');
});

Route::group(['namespace' => 'Transaksi'], function () {

    // api purchase
    Route::get('purchasedetail/datatable/{cabang}', 'TransaksiPurchaseDetailController@all_data');
    Route::get('purchasedetail/edit_datatable/{id_transaksi_purchase}', 'TransaksiPurchaseDetailController@edit_datatable');
    Route::get('purchasedetailproduk/{cabang}/{invoice_id}', 'TransaksiPurchaseDetailController@datatable');
    Route::post('purchasedetail/approval', 'TransaksiPurchaseDetailController@approvalPurchase');
    Route::delete('purchasedetail/remove/{id_transaksi_purchase}', 'TransaksiPurchaseDetailController@remove');
    Route::post('purchasedetail', 'TransaksiPurchaseDetailController@add');
    Route::get('calculatedetail/{id_transaksi_purchase}', 'TransaksiPurchaseDetailController@calculateDetail');
    Route::get('editregisterpurchase/{tot}/{dis}/{down}/{debt}/{id_transaksi_purchase}', 'TransaksiPurchaseDetailController@register');
    Route::get('totalpembelian/{id_cabang}/{date}', 'TransaksiPurchaseController@totalpembelian');

    // api purchase tmp
    Route::get('purchasetmp/datatable/{id_cabang}', 'TransaksiPurchaseTmpController@datatable');
    Route::get('purchasetmp/{id}', 'TransaksiPurchaseTmpController@get');
    Route::get('purchasetmp', 'TransaksiPurchaseTmpController@get');
    Route::post('purchasetmp', 'TransaksiPurchaseTmpController@add');
    Route::put('purchasetmp', 'TransaksiPurchaseTmpController@edit');
    Route::delete('purchasetmp/{id}', 'TransaksiPurchaseTmpController@remove');
    Route::get('calculatetmp/{id_cabang}', 'TransaksiPurchaseTmpController@calculateTmp');
    Route::get('registerpurchase/{tot}/{dis}/{down}/{debt}/{id_cabang}', 'TransaksiPurchaseTmpController@register');

    // api transaksi sales
    Route::post('getsalestrans', 'TransaksiSalesController@getSales');
    Route::post('getCustomer', 'TransaksiSalesController@getCustomer');
    Route::post('getProduk', 'TransaksiSalesController@getProduk');
    Route::post('cekstok', 'TransaksiSalesController@cekstok');
    Route::post('hargakusus', 'TransaksiSalesController@hargakusus');
    Route::post('addkeranjang', 'TransaksiSalesController@addkeranjang');
    Route::get('datatable/{id}', 'TransaksiSalesController@datatable');
    Route::post('deleteitem', 'TransaksiSalesController@deleteitem');
    Route::post('rekaptransaksi', 'TransaksiSalesController@rekaptransaksi');
    Route::post('invoice', 'TransaksiSalesController@invoice');
    Route::get('tampilstok/{cabang}', 'TransaksiSalesController@tampilstok');
    Route::get('totalpenjualan/{id_cabang}/{date}', 'TransaksiSalesController@totalpenjualan');

    // api android produk
    Route::get('apiproduk/{cabang}', 'TransaksiSalesController@apiproduk');
    Route::post('apistok/', 'TransaksiSalesController@apistok');
    Route::post('apidatatable/', 'TransaksiSalesController@apidatatable');
    Route::post('addkeranjangapi/', 'TransaksiSalesController@addkeranjangapi');

    // api return sales
    Route::post('ambil', 'ReturnsalesController@ambil');
    Route::post('deleteitemr', 'ReturnsalesController@deleteitemr');
    Route::post('addkeranjangr', 'ReturnsalesController@addkeranjangr');
    Route::post('rekaptransaksir', 'ReturnsalesController@rekaptransaksir');

    // api get payment
    Route::post('paymentcustomer', 'GetpaymentController@caricustomer');
    Route::post('detailtrans', 'GetpaymentController@detailtrans');
    Route::post('getcredit', 'GetpaymentController@getcredit');
    Route::post('changedue', 'GetpaymentController@changedue');
    Route::post('addpayment', 'GetpaymentController@addpayment');
    Route::post('getpayments', 'GetpaymentController@getpayments');

    // api approval
    Route::post('approval', 'ApprovesalesController@approve');
    Route::get('detailapp/{id}', 'ApprovesalesController@detailapp');

    // api Purchase Return
    // Route::get('purchasereturn/datatable', 'TransaksiPurchareturnController@datatable');
    Route::get('purchasereturn/{id}', 'TransaksiPurchaseReturnController@get');
    Route::get('returnpurchase/datatable/{id_cabang}', 'TransaksiPurchaseReturnController@datatable');
    Route::post('purchasereturn', 'TransaksiPurchaseReturnController@add');
    Route::delete('purchasereturn/{id}', 'TransaksiPurchaseReturnController@remove');
    Route::get('registerpurchasereturn/{id_cabang}', 'TransaksiPurchaseReturnController@register')->name('register-transaksi-purchase-return');
    Route::get('getsuplierproduk/{id}', 'TransaksiPurchaseReturnController@getStok');

    // test inv
    Route::get('purchaseinv/{id}', 'TransaksiPurchaseTmpController@generateInvoicePurchase');
    Route::get('purchasereturninv/{id}', 'TransaksiPurchaseReturnController@generateInvoicePurchaseReturn');

    // opname
    Route::get('stok_opname/{fisik}/{stok_id}', 'OpnameController@cekbalance');
    Route::post('stok_opname', 'OpnameController@add');
    Route::get('reportopname/{id_cabang}/{id_gudang}', 'OpnameController@print_faktur');
    Route::get('makeadjust/{stok_id}', 'OpnameController@adjust');
    Route::get('aprovalopname/datatable/{id_cabang}', 'OpnameController@datatablesaprovalopname');
    Route::post('opname/approval', 'OpnameController@opname_aproval');

    // broken
    Route::get('brokenexp/datatable/{id_cabang}', 'BrokenExpMovementController@datatablesbroken');
    Route::get('ambildatastok/{id}', 'BrokenExpMovementController@ambildatastok');
    Route::post('cekdatastok/', 'BrokenExpMovementController@cekdatastok');
    Route::post('add_broken', 'BrokenExpMovementController@add');
    Route::delete('broken/remove/{id}', 'BrokenExpMovementController@remove');
    Route::get('registerbroken/{id_cabang}', 'BrokenExpMovementController@register');
});

Route::group(['namespace' => 'Report'], function () {
    Route::group(['prefix' => 'inventory'], function () {
        Route::get('datatable/{id_cabang}', 'StokReportController@datatable');
        Route::get('datatable/{id_cabang}/{id}', 'StokReportController@datatable');
    });
    Route::group(['prefix' => 'report_purchase'], function () {
        Route::get('datatable/{status}/{id_cabang}', 'PurchaseReportController@all_datatable');
        Route::get('today_datatable/{status}/{id_cabang}', 'PurchaseReportController@today_datatable');
        Route::get('month_datatable/{month}/{year}/{status}/{id_cabang}', 'PurchaseReportController@month_datatable');
        Route::get('year_datatable/{year}/{status}/{id_cabang}', 'PurchaseReportController@year_datatable');
        Route::get('range_datatable/{awal}/{akhir}/{status}/{id_cabang}', 'PurchaseReportController@range_datatable');
        Route::get('detailpurchase/{cabang}/{invoice_id}', 'PurchaseReportController@datatable_detail_report');
        // edit transaksi
        Route::get('edit_today_datatable/{id_cabang}', 'PurchaseReportController@edit_today_datatable');
        Route::get('editdetailpurchase/{cabang}/{invoice_id}', 'PurchaseReportController@datatable_edit_detail_report');
    });
    Route::group(['prefix' => 'report_purchase_return'], function () {
        Route::get('datatable/{id_cabang}', 'PurchaseReturnReportController@all_datatable');
        Route::get('today_datatable/{id_cabang}', 'PurchaseReturnReportController@today_datatable');
        Route::get('month_datatable/{month}/{year}/{id_cabang}', 'PurchaseReturnReportController@month_datatable');
        Route::get('year_datatable/{year}/{id_cabang}', 'PurchaseReturnReportController@year_datatable');
        Route::get('range_datatable/{awal}/{akhir}/{id_cabang}', 'PurchaseReturnReportController@range_datatable');
        Route::post('detailpurchasereturn', 'PurchaseReturnReportController@datatable_detail_report');
    });

    Route::group(['prefix' => 'cost_report'], function () {
        Route::get('datatable', 'CostReport@datatable');
        Route::get('findid/{select}/{input}/{ket_waktu}/{filtertahun}/{filterbulan}/{filter_year}/{waktuawal}/{waktuakhir}', 'CostReport@findId');
    });

    Route::group(['prefix' => 'sales_achievement'], function () {
        Route::get('ambiltarget/{id}', 'SalesAchievementReport@ambiltarget');
        Route::get('transaksisalesachievement/{id}/{select}/{ket_waktu}/{filtertahun}/{filterbulan}/{filter_year}/{waktuawal}/{waktuakhir}', 'SalesAchievementReport@transaksisales');
    });
    //     Route::get('purchaseinv/{id}', 'TransaksiPurchaseTmpController@generateInvoicePurchase');
    // Route::get('purchasereturninv/{id}', 'TransaksiPurchaseReturnController@generateInvoicePurchaseReturn');

    // broken and exp movement

    // edit transaksi

    Route::group(['prefix' => 'produk_report'], function () {
        Route::get('datatable/{id_cabang}', 'ProdukReport@all_datatable');
        Route::get('today_datatable/{id_cabang}', 'ProdukReport@today_datatable');
        Route::get('month_datatable/{month}/{year}/{id_cabang}', 'ProdukReport@month_datatable');
        Route::get('year_datatable/{year}/{id_cabang}', 'ProdukReport@year_datatable');
        Route::get('range_datatable/{awal}/{akhir}/{id_cabang}', 'ProdukReport@range_datatable');
    });

    Route::group(['prefix' => 'sales_transaksi'], function () {
        Route::get('detailreport/{id}', 'SalesTransaksiReport@detailreport');
    });
});

Route::group(['namespace' => 'Report'], function () {
    Route::get('tablereport/{search}', 'BrokenExpReport@tablereport');
});
