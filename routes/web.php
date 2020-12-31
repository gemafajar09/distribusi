<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Session()->has('id')) {
        if(session()->get('level') == 1)
        return view('home_pimpinan');
        else 
        if(session()->get('level') == 2 || session()->get('level') == 4 )
        return view('home_superadmin');
        else 
        if(session()->get('level') == 3)
        return view('home');
    } else {
        return view('pages.login');
    }
});

// call controller folder admin

Route::group(['namespace' => 'Admin'], function () {
    // call controller modul

    Route::group(['prefix' => 'product'], function () {
        Route::get('/view_product', 'ProductController@index')->name('view_product');
    });
    Route::group(['prefix' => 'cabang'], function () {
        Route::get('/index', 'CabangController@index')->name('cabang');
    });
    Route::group(['prefix' => 'satuan'], function () {
        Route::get('/index', 'SatuanController@index')->name('satuan');
    });
    Route::group(['prefix' => 'cost'], function () {
        Route::get('/index', 'CostController@index')->name('cost');
    });
    Route::group(['prefix' => 'sales'], function () {
        Route::get('/index', 'SalesController@index')->name('sales');
    });
    Route::group(['prefix' => 'gudang'], function () {
        Route::get('/index', 'GudangController@index')->name('gudang');
    });
    Route::group(['prefix' => 'spesial'], function () {
        Route::get('/index', 'SpesialHargaController@index')->name('spesial');
    });
    Route::group(['prefix' => 'suplier'], function () {
        Route::get('/index', 'SuplierController@index')->name('suplier');
    });
    Route::group(['prefix' => 'type'], function () {
        Route::get('/index', 'TypeController@index')->name('type');
    });
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/index', 'CustomerController@index')->name('customer');
    });
    Route::group(['prefix' => 'produk'], function () {
        Route::get('/index', 'ProductController@index')->name('produk');
    });
    Route::group(['prefix' => 'user'], function () {
        Route::get('/index', 'UserController@index')->name('user');
        Route::get('/profile', 'UserController@view_profile')->name('view_profile');
    });
    Route::group(['prefix' => 'stok'], function () {
        Route::get('/index', 'StokController@index')->name('stok');
    });
    Route::group(['prefix' => 'unit'], function () {
        Route::get('/index', 'UnitController@index')->name('unit');
    });
});

Route::group(['namespace' => 'Transaksi'], function () {
    Route::group(['prefix' => 'sales_transaksi'], function () {
        Route::get('/sales_transaction', 'TransaksiSalesController@index')->name('sales_transaction');
        Route::get('/datatablessales', 'TransaksiSalesController@datatablessales')->name('datatablessales');
        Route::get('/fakturs/{id}/{type}/{id_cabang}', 'TransaksiSalesController@faktur');
        // return
        Route::get('/returnsales', 'ReturnsalesController@index')->name('returnsales');
        Route::get('/tmpdata', 'ReturnsalesController@tmpdata')->name('tmpdata');
        Route::get('/showreturdetail/{nama}/{serch}/{view}/{type}', 'ReturnsalesController@showreturdetail');
        // get payment
        Route::get('/getpayment', 'GetpaymentController@index')->name('getpayment');
        // approve
        Route::get('/approvesales', 'ApprovesalesController@index')->name('approvesales');
    });
    Route::group(['prefix' => 'purchase_transaksi'], function () {
        // Purchase
        Route::get('/purchase_order', 'TransaksiPurchaseController@index')->name('purchase_order');
        Route::get('/datatablessales', 'TransaksiSalesController@datatablessales')->name('datatablessales');
        Route::get('/purchase_order', 'TransaksiPurchaseController@index')->name('purchase_order');
        Route::get('/purchase_aproval', 'TransaksiPurchaseDetailController@index')->name('aproval_purchase_order');
        Route::get('/purchase_return', 'TransaksiPurchaseReturnController@index')->name('return_purchase_order');
        Route::get('/edit_purchase', 'TransaksiPurchaseController@view_edit_purchase')->name('edit_purchase_order');
        Route::get('/edit_purchase_order/{id_transaksi_purchase}', 'TransaksiPurchaseDetailController@edit_transaksi');

        // broken exp movement
        Route::get('/broken_exp', 'BrokenExpMovementController@index')->name('broken_exp');
        Route::get('/datatablesbem', 'BrokenExpMovementController@datatablesbem')->name('datatablesbem');
    });
    Route::group(['prefix' => 'opname'], function () {

        Route::get('/stok_opname','OpnameController@index')->name('opname');
        Route::get('/datatablesopname','OpnameController@datatablesopname')->name('datatablesopname');
        Route::get('/list_aproval_opname','OpnameController@list_aproval')->name('aprovalopname');

    });
    
});

Route::group(['namespace' => 'Report'], function () {
    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/report', 'StokReportController@index')->name('stok-report');
        Route::get('/report_stok/{id_cabang}', 'StokReportController@report');
        Route::get('/report_stok/{id_cabang}/{id_warehouse}', 'StokReportController@report');
    });
    Route::group(['prefix' => 'purchase'], function () {
        Route::get('/report', 'PurchaseReportController@index')->name('purchase-report');
        Route::get('/report_purchase/{status}/{id_cabang}', 'PurchaseReportController@report_all');
        Route::get('/report_purchase_today/{status}/{id_cabang}', 'PurchaseReportController@report_today');
        Route::get('/report_purchase_edit_today/{id_transaksi_purchase}', 'PurchaseReportController@report_edit_today');
        Route::get('/report_purchase_month/{month}/{year}/{status}/{id_cabang}', 'PurchaseReportController@report_month');
        Route::get('/report_purchase_year/{year}/{status}/{id_cabang}', 'PurchaseReportController@report_year');
        Route::get('/report_purchase_range/{awal}/{akhir}/{status}/{id_cabang}', 'PurchaseReportController@report_range');
    });
    Route::group(['prefix' => 'purchase_return'], function () {
        Route::get('/report', 'PurchaseReturnReportController@index')->name('purchase-return-report');
        Route::get('/report_purchase_return/{id_cabang}', 'PurchaseReturnReportController@report_all');
        Route::get('/report_purchase_return_today/{id_cabang}', 'PurchaseReturnReportController@report_today');
        Route::get('/report_purchase_return_month/{month}/{year}/{id_cabang}', 'PurchaseReturnReportController@report_month');
        Route::get('/report_purchase_return_year/{year}/{id_cabang}', 'PurchaseReturnReportController@report_year');
        Route::get('/report_purchase_return_range/{awal}/{akhir}/{id_cabang}', 'PurchaseReturnReportController@report_range');
    });
    Route::group(['prefix' => 'cost_report'], function () {
        Route::get('/report', 'CostReport@index')->name('cost_report');
        Route::get('/table_cost/{id_cabang?}/{select?}/{input?}/{ket_waktu?}/{filtertahun?}/{filterbulan?}/{filter_year?}/{waktuawal?}/{waktuakhir?}', 'CostReport@table')->name('table_cost');
        Route::get('/generate_cost/{id_cabang}/{select}/{input}/{ket_waktu}/{filtertahun}/{filterbulan}/{filter_year}/{waktuawal}/{waktuakhir}', 'CostReport@generatereport');
    });
    
    Route::group(['prefix' => 'sales_transaksi'], function () {
        Route::get('/report', 'SalesTransaksiReport@index')->name('sales_transaksi');
        Route::get('/table_sales_transaksi/{ket_waktu?}/{filtertahun?}/{filterbulan?}/{filter_year?}/{waktuawal?}/{waktuakhir?}', 'SalesTransaksiReport@table')->name('table_sales_transaksi');
    });
    
    Route::group(['prefix' => 'sales_achievement'], function () {
        Route::get('/report', 'SalesAchievementReport@index')->name('sales_achievement');
        Route::get('/table_achievement/{id_sales?}/{select?}/{ket_waktu?}/{filtertahun?}/{filterbulan?}/{filter_year?}/{waktuawal?}/{waktuakhir?}', 'SalesAchievementReport@transaksisales')->name('table_achievement');
        Route::get('/report_all_stock', 'SalesAchievementReport@printallstock');
        Route::get('/report_to_stock', 'SalesAchievementReport@printtostock');
        Route::get('/report_canvas_stock', 'SalesAchievementReport@printcanvasstock');
    });
    
    Route::group(['prefix' => 'broken'], function () {
        // broken exp movement
        Route::get('/report', 'BrokenExpReport@index')->name('broken_exp_report');
        Route::get('/print', 'BrokenExpReport@reportprint')->name('printbroken');
        Route::get('/printproduct/{search?}', 'BrokenExpReport@reportprint')->name('printproduct');
        Route::get('/tablereport/{search?}', 'BrokenExpReport@tablereport')->name('tablereport');
    });
    
    Route::group(['prefix' => 'produk'], function () {
        Route::get('/report', 'ProdukReport@index')->name('report_produk');
        Route::get('/report_all_stock_spesifik/{id_cabang}/{produk_id}', 'ProdukReport@report_all_spesifik');
        Route::get('/report_all_stock_spesifik_today/{id_cabang}/{produk_id}', 'ProdukReport@report_today_spesifik');
        Route::get('/report_all_stock_spesifik_month/{month}/{year}/{id_cabang}/{produk_id}', 'ProdukReport@report_month_spesifik');
        Route::get('/report_all_stock_spesifik_year/{year}/{id_cabang}/{produk_id}', 'ProdukReport@report_year_spesifik');
        Route::get('/report_all_stock_spesifik_range/{awal}/{akhir}/{id_cabang}/{produk_id}', 'ProdukReport@report_range_spesifik');
    });
    
    Route::group(['prefix' => 'rekap'],function(){
        Route::get('/rekap_transaksi/{id_cabang}/{waktu}', 'StokReportController@rekap_transaksi');
    });
});


Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login', 'LoginController@postLogin')->name('postLogin');
Route::get('/logout', 'LoginController@postLogout')->name('postLogout');
