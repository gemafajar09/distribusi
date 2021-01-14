<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SalesAchievementReport extends Controller
{
    public function index()
    {
        $cabang = Session()->get('cabang');
        $data = DB::table('tbl_sales')->where('id_cabang', $cabang)->get();
        return view("report.salesachievement.index", compact('data'));
    }

    public function ambiltarget($id)
    {
        $data = DB::table('tbl_sales')->where('id_sales', $id)->first();
        echo json_encode($data);
    }

    public function datacredit($id, $ket_waktu, $filtertahun, $filterbulan, $filter_year, $waktuawal, $waktuakhir)
    {
        $datas = DB::table('transaksi_sales')
            ->leftJoin('tbl_customer', 'tbl_customer.id_customer', 'transaksi_sales.customer_id')
            ->where('transaksi_sales.transaksi_tipe', 'Credit')
            ->where('transaksi_sales.approve', '1')
            ->where('transaksi_sales.sales_id', $id);
        if (!empty($ket_waktu)) {
            if ($ket_waktu == 0) {
                $datas = $datas->select('*')->get();
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
        return view("report.salesachievement.tabledetailpayment", $data);
    }

    public function transaksisales($id, $select, $ket_waktu, $filtertahun, $filterbulan, $filter_year, $waktuawal, $waktuakhir)
    {
        $data = DB::table('transaksi_sales')
            ->leftJoin('tbl_customer', 'transaksi_sales.customer_id', 'tbl_customer.id_customer')
            ->leftJoin('transaksi_sales_details as d', 'd.invoice_id', '=', 'transaksi_sales.invoice_id')
            ->leftJoin('tbl_stok as s', 's.stok_id', 'd.stok_id')
            ->leftJoin('tbl_user', 'tbl_user.id_user', '=', 'transaksi_sales.id_user')
            ->where('approve', 1)
            ->where('sales_id', $id);

        if (!empty($ket_waktu)) {
            if ($ket_waktu == 0) {
                $data = $data->select('*')->get();
            }
            if ($ket_waktu == 1) {
                $data = $data->whereRaw('Date(transaksi_sales.invoice_date) = CURDATE()');
            }
            if ($ket_waktu == 2) {
                $data = $data->whereMonth('transaksi_sales.invoice_date', $filterbulan)->whereYear('transaksi_sales. invoice_date', $filtertahun);
            }
            if ($ket_waktu == 3) {
                $data = $data->whereYear('transaksi_sales.invoice_date', $filter_year);
            }
            if ($ket_waktu == 4) {
                $data = $data->whereBetween('transaksi_sales.invoice_date', [$waktuawal, $waktuakhir]);
            }
        }
        if ($select == 1) {
            $data = $data->where("transaksi_sales.sales_type", "=",  "TAKING ORDER");
        } elseif ($select == 2) {
            $data = $data->where("transaksi_sales.transaksi_tipe", "=", "Cash");
        } elseif ($select == 3) {
            $data = $data->where("transaksi_sales.transaksi_tipe", "=", "Credit");
        }
        $data = $data->get();
        $total_modal = 0;
        $total_jual = 0;
        foreach ($data as $d) {
            $id = $d->produk_id;
            $harga_modal = $d->capital_price;
            $harga_jual = $d->price;
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            foreach ($proses as $index => $list) {
                if ($index == 0) {
                    $harga_modal = $harga_modal / $list->default_value;
                    $harga_jual = $harga_modal / $list->default_value;
                } else if ($index == 1) {
                    $harga_modal = $harga_modal / $list->default_value;
                    $harga_jual = $harga_modal / $list->default_value;
                } else if ($index == 2) {
                    $harga_modal = $harga_modal / $list->default_value;
                    $harga_jual = $harga_modal / $list->default_value;
                }
            }
            $total_modal += $harga_modal * $d->quantity;
            $total_jual += $harga_jual * $d->quantity;
            $profit = $total_jual - $total_modal;
        }
        dd($total_modal, $total_jual);
        return view("report.salesachievement.table", compact(['data']));
    }

    public function printallstock($id_cabang)
    {
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang', $id_cabang)->first();
        $data = DB::table('transaksi_sales_details')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_details.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->join('transaksi_sales', 'transaksi_sales.invoice_id', 'transaksi_sales_details.invoice_id')
            ->get();
        // dd($data);
        $format = '%d %s |';
        $stok = [];
        foreach ($data as $a) {
            $proses = DB::table('tbl_unit')->where('produk_id', $a->produk_id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            foreach ($proses as $index => $list) {
                $banyak =  sizeof($proses);
                $sisa = $a->quantity % $list->default_value;
                $hasilbagi = ($a->quantity - $sisa) / $list->default_value;
                $satuan[$index] = $list->unit;
                $value_default[$index] = $list->default_value;
                $lebih[$index] = $sisa;
                if ($index == 0) {
                    if ($sisa > 0) {
                        $stok[$index] = sprintf($format, $sisa, $list->unit);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                } else if ($index == 1) {
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                } else if ($index == 2) {
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa,  $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                }
            }
            $datas[] = array(
                'produk_id' => $a->produk_id,
                'produk_nama' => $a->produk_nama,
                'quantity' => implode(" ", $stok)
            );
        }
        return view("report.salesachievement.printallstock", compact(['datas', 'data_cabang']));
    }

    public function printtostock($id_cabang)
    {
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang', $id_cabang)->first();
        $data = DB::table('transaksi_sales_details')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_details.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->join('transaksi_sales', 'transaksi_sales.invoice_id', 'transaksi_sales_details.invoice_id')
            ->where('transaksi_sales.sales_type', 'TAKING ORDER')
            ->get();
        // dd($data);
        $format = '%d %s |';
        $stok = [];
        foreach ($data as $a) {
            $proses = DB::table('tbl_unit')->where('produk_id', $a->produk_id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            foreach ($proses as $index => $list) {
                $banyak =  sizeof($proses);
                $sisa = $a->quantity % $list->default_value;
                $hasilbagi = ($a->quantity - $sisa) / $list->default_value;
                $satuan[$index] = $list->unit;
                $value_default[$index] = $list->default_value;
                $lebih[$index] = $sisa;
                if ($index == 0) {
                    if ($sisa > 0) {
                        $stok[$index] = sprintf($format, $sisa, $list->unit);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                } else if ($index == 1) {
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                } else if ($index == 2) {
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa,  $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                }
            }
            $datas[] = array(
                'produk_id' => $a->produk_id,
                'produk_nama' => $a->produk_nama,
                'quantity' => implode(" ", $stok)
            );
        }
        return view("report.salesachievement.printtostock", compact(['datas', 'data_cabang']));
    }

    public function printcanvasstock($id_cabang)
    {
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang', $id_cabang)->first();
        $data = DB::table('transaksi_sales_details')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_details.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->join('transaksi_sales', 'transaksi_sales.invoice_id', 'transaksi_sales_details.invoice_id')
            ->where('transaksi_sales.sales_type', 'CANVASING')
            ->get();
        // dd($data);
        $format = '%d %s |';
        $stok = [];
        foreach ($data as $a) {
            $proses = DB::table('tbl_unit')->where('produk_id', $a->produk_id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            foreach ($proses as $index => $list) {
                $banyak =  sizeof($proses);
                $sisa = $a->quantity % $list->default_value;
                $hasilbagi = ($a->quantity - $sisa) / $list->default_value;
                $satuan[$index] = $list->unit;
                $value_default[$index] = $list->default_value;
                $lebih[$index] = $sisa;
                if ($index == 0) {
                    if ($sisa > 0) {
                        $stok[$index] = sprintf($format, $sisa, $list->unit);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                } else if ($index == 1) {
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                } else if ($index == 2) {
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa,  $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    }
                }
            }
            $datas[] = array(
                'produk_id' => $a->produk_id,
                'produk_nama' => $a->produk_nama,
                'quantity' => implode(" ", $stok)
            );
        }
        return view("report.salesachievement.printcanvasstock", compact(['datas', 'data_cabang']));
    }
}
