<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransaksiSales;
use App\TransaksiSalesDetail;
use App\TransaksiSalesTmp;
use App\Models\Sales;
use App\Models\Customer;
use DB;
use Session;

class SalesTransaksiReport extends Controller
{
    public function index()
    {
        $data['toko'] = DB::table('tbl_customer')->get();
        return view('report.salestransaksi.index', $data);
    }

    public function table($id_cabang, $status, $namatoko, $ket_waktu, $filtertahun, $filterbulan, $filter_year, $waktuawal, $waktuakhir)
    {
        $data = DB::table('transaksi_sales');
        if ($status == 1) {
            $tgl = date('Y-m-d');
            if ($namatoko == 0) {
                $data = DB::table('transaksi_sales')->where('approve', '1')->get();
            } else {
                if ($ket_waktu == 1) {
                    $data = $data->where('approve', '1')->where('invoice_date', $tgl)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 2) {
                    $data = $data->where('approve', '1')->whereMonth('invoice_date', $filterbulan)->whereYear('invoice_date', $filtertahun)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 3) {
                    $data = $data->where('approve', '1')->whereYear('invoice_date', $filter_year)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 4) {
                    $data = $data->where('approve', '1')->whereBetween('invoice_date', [$waktuawal, $waktuakhir])->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 0) {
                    $data = DB::table('transaksi_sales')->where('approve', '1')->where('customer_id', $namatoko)->get();
                }
            }
        } else if ($status == 2) {
            $tgl = date('Y-m-d');
            if ($namatoko == 0) {
                $data = DB::table('transaksi_sales')->where('approve', '2')->get();
            } else {
                if ($ket_waktu == 1) {
                    $data = $data->where('approve', '2')->where('invoice_date', $tgl)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 2) {
                    $data = $data->where('approve', '2')->whereMonth('invoice_date', $filterbulan)->whereYear('invoice_date', $filtertahun)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 3) {
                    $data = $data->where('approve', '2')->whereYear('invoice_date', $filter_year)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 4) {
                    $data = $data->where('approve', '2')->whereBetween('invoice_date', [$waktuawal, $waktuakhir])->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 0) {
                    $data = DB::table('transaksi_sales')->where('approve', '2')->where('customer_id', $namatoko)->get();
                }
            }
        } else {
            $tgl = date('Y-m-d');
            if ($namatoko == 0) {
                $data = DB::table('transaksi_sales')->where('approve', '0')->get();
            } else {
                if ($ket_waktu == 1) {
                    $data = $data->where('approve', '0')->where('invoice_date', $tgl)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 2) {
                    $data = $data->where('approve', '0')->whereMonth('invoice_date', $filterbulan)->whereYear('invoice_date', $filtertahun)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 3) {
                    $data = $data->where('approve', '0')->whereYear('invoice_date', $filter_year)->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 4) {
                    $data = $data->where('approve', '0')->whereBetween('invoice_date', [$waktuawal, $waktuakhir])->where('customer_id', $namatoko)->get();
                } elseif ($ket_waktu == 0) {
                    $data = DB::table('transaksi_sales')->where('approve', '0')->where('customer_id', $namatoko)->get();
                }
            }
        }

        $init = array();
        foreach ($data as $a) {
            $sales = DB::table('tbl_sales')->where('id_sales', $a->sales_id)->first();
            if ($sales == NULL) {
                $namasales = '';
            } else {
                $namasales = $sales->nama_sales;
            }
            // ======================================
            $customer = DB::table('tbl_customer')->where('id_customer', $a->customer_id)->first();
            if (!empty($customer)) {
                $init[] = array(
                    'id_transaksi_sales' => $a->id_transaksi_sales,
                    'invoice_id' => $a->invoice_id,
                    'invoice_date' => $a->invoice_date,
                    'term_until' => $a->term_until,
                    'totalsales' => $a->totalsales,
                    'dp' => $a->dp,
                    'status' => $a->status,
                    'diskon' => $a->diskon,
                    'transaksi_tipe' => $a->transaksi_tipe,
                    'nama_sales' => $namasales,
                    'nama_customer' => $customer->nama_customer,
                    'id_cabang' => $customer->id_cabang,
                    'approve' => $a->approve
                );
            }
        }
        return view('report.salestransaksi.table', compact('init'));
    }

    public function detailreport($id)
    {
        $data = DB::table('transaksi_sales_details')
            ->leftJoin('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_details.stok_id')
            ->leftJoin('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->leftJoin('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->where('transaksi_sales_details.invoice_id', $id)
            ->get();
        $format = '%d %s |';
        $stok = [];
        $datas = [];
        foreach ($data as $a) {
            $proses = DB::table('tbl_unit')->where('produk_id', $a->produk_id)
                ->leftJoin('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            foreach ($proses as $index => $list) {
                $banyak = sizeof($proses);
                $jumlah = $a->quantity;
                if ($index == 0) {
                    $sisa = $jumlah % $list->default_value;
                    $hasilbagi = ($jumlah - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
                    $value[$index] = $list->default_value;
                    $lebih[$index] = $sisa;
                    if ($sisa > 0) {
                        $stok[] = sprintf($format, $sisa, $list->unit);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                } else if ($index == 1) {
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
                    $value[$index] = $list->default_value;
                    $lebih[$index] = $sisa;
                    if ($sisa > 0) {
                        $stok[] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                } else if ($index == 2) {
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
                    $value[$index] = $list->default_value;
                    $lebih[$index] = $sisa;
                    if ($sisa > 0) {
                        $stok[] = sprintf($format, $sisa, $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }
            }
            $datas[] = array(
                'stok_id' => $a->stok_id,
                'produk_id' => $a->produk_id,
                'nama_type_produk' => $a->nama_type_produk,
                'produk_brand' => $a->produk_brand,
                'produk_nama' => $a->produk_nama,
                'capital_price' => $a->capital_price,
                'total' => $a->quantity * $a->harga_satuan,
                'diskon' => $a->diskon,
                'amount' => ($a->quantity * $a->harga_satuan) - $a->diskon,
                'id_transaksi_tmp' => $a->id_transaksi_detail,
                'quantity' => implode(" ", $stokquantity)
            );
        }
        return view('report.salestransaksi.tabledetail', compact('datas'));
    }

    // public function datatransaksi($id)
    // {
    //     $data = DB::table('transaksi_sales')->where('invoice_id', $id)->get();
    // dd($data);
    //     return view('pages.transaksi.salestransaksi.edit', compact('data'));
    // }
}
