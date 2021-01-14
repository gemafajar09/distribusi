<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class GetPaymentReport extends Controller
{
    public function index()
    {
        return view("report.getpayment.index");
    }

    public function table($ket_waktu, $filtertahun, $filterbulan, $filter_year, $waktuawal, $waktuakhir, $select, $input)
    {
        $datas = DB::table('transaksi_sales')
            ->leftJoin('tbl_customer', 'tbl_customer.id_customer', 'transaksi_sales.customer_id')
            ->leftJoin('tbl_sales', 'transaksi_sales.sales_id', 'tbl_sales.id_sales')
            ->where('transaksi_sales.transaksi_tipe', 'Credit')
            ->where('transaksi_sales.approve', '1');
        if (!empty($select)) {
            if ($select == 'namatoko') {
                $datas = $datas->where('nama_customer', 'like', '%' . $input . '%');
            } elseif ($select == 'namasales') {
                $datas = $datas->where('nama_sales', 'like', '%' . $input . '%');
            }
        }
        if ($ket_waktu == 1) {
            $datas = $datas->whereRaw('Date(invoice_date) = CURDATE()');
        }
        if ($ket_waktu == 2) {
            $datas = $datas->whereMonth('invoice_date', $filterbulan)->whereYear('invoice_date', $filtertahun);
        }
        if ($ket_waktu == 3) {
            $datas = $datas->whereYear('invoice_date', $filter_year);
        }
        if ($ket_waktu == 4) {
            $datas = $datas->whereBetween('invoice_date', [$waktuawal, $waktuakhir]);
        }
        $datas = $datas->get();
        // dd($datas);

        if ($datas == TRUE) {
            $data['hasil'] = [];
            foreach ($datas as $a) {
                $cek = DB::table('tbl_getpayment')->selectRaw('SUM(payment) as payment')->where('invoice_id', $a->invoice_id)->first();
                $sales = DB::table('tbl_sales')->where('id_sales', $a->sales_id)->first();
                if ($cek != NULL) {
                    $payment = $cek->payment;
                    $sisa = $a->totalsales - $cek->payment;
                } else {
                    $payment = 0;
                    $sisa = $a->totalsales - 0;
                }
                if ($sales != NULL) {
                    $namasales = $sales->nama_sales;
                } else {
                    $namasales = '-';
                }
                $data['hasil'][] = array(
                    'invoice_id' => $a->invoice_id,
                    'invoice_date' => $a->invoice_date,
                    'nama_customer' => $a->nama_customer,
                    'nama_sales' => $namasales,
                    'totalsales' => $a->totalsales,
                    'payment' => $payment,
                    'remaining' => $sisa,
                    'term_until' => $a->term_until,
                    'status' => $a->status,
                    'sales_type' => $a->sales_type
                );
            }
        } else {
            $data['hasil'] = array(
                'invoice_id' => '',
                'invoice_date' => '',
                'nama_customer' => '',
                'nama_sales' => '',
                'totalsales' => '',
                'payment' => '',
                'remaining' => '',
                'term_until' => '',
                'status' => '',
                'sales_type' => ''
            );
        }
        // dd($data['hasil']);
        return view("report.getpayment.table", $data);
    }
}
