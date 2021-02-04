<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransaksiSales;
use DB;

class ApprovesalesController extends Controller
{
    public function index()
    {
        $trans = DB::table('transaksi_sales')
            ->leftJoin('tbl_customer', 'transaksi_sales.customer_id', 'tbl_customer.id_customer')
            ->where('transaksi_sales.approve', '0')
            ->get();
        $data['list'] = array();
        foreach ($trans as $a) {
            $sales = DB::table('tbl_sales')->where('id_sales', $a->sales_id)->first();
            if ($sales == NULL) {
                $namasales = '';
            } else {
                $namasales = $sales->nama_sales;
            }
            $data['list'][] = array(
                'invoice_id' => $a->invoice_id,
                'invoice_date' => $a->invoice_date,
                'totalsales' => $a->totalsales,
                'diskon' => $a->diskon,
                'nama_customer' => $a->nama_customer,
                'id_transaksi_sales' => $a->id_transaksi_sales,
                'nama_sales' => $namasales,
                'status' => $a->status,
                'transaksi_tipe' => $a->transaksi_tipe
            );
        }
        return view('pages.transaksi.salestransaksi.approve', $data);
    }

    public function approve(Request $r)
    {
        $status = $r->status;
        $id_transaksi = $r->id_transaksi;
        if ($status == 1) {
            $edit = DB::table('transaksi_sales')->where('id_transaksi_sales', $id_transaksi)->update(['approve' => $status]);
            if ($edit == TRUE) {
                return response()->json(['status' => 200]);
            } else {
                return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
            }
        } else if ($status == 2) {
            $disaprove = DB::table('transaksi_sales_details')->where('invoice_id', $r->invoice_id)->get();
            foreach ($disaprove as $i => $d) {
                $cek = DB::table('tbl_stok')->where('stok_id', $d->stok_id)->first();
                $jml = $cek->jumlah + $d->quantity;
                $update = DB::table('tbl_stok')->where('stok_id', $d->stok_id)->update(['jumlah' => $jml]);
                DB::table('transaksi_sales')->where('invoice_id', $r->invoice_id)->update(['approve' => 2]);
                if ($update == TRUE) {
                    return response()->json(['status' => 200]);
                } else {
                    return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
                }
            }
        } else {
            $select = DB::table('transaksi_sales_details')->where('invoice_id', $r->invoice_id)->get();
            foreach ($select as $i => $d) {
                // $cek = DB::table('tbl_stok')->where('stok_id', $d->stok_id)->first();
                // $jml = $cek->jumlah + $d->quantity;
                // $update = DB::table('tbl_stok')->where('stok_id', $d->stok_id)->update(['jumlah' => $jml]);
                $update = DB::table('transaksi_sales')->where('invoice_id', $r->invoice_id)->update(['approve' => 0]);
                if ($update == TRUE) {
                    return response()->json(['status' => 200]);
                } else {
                    return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
                }
            }
        }
    }

    public function detailapp($id)
    {
        $data = DB::table('transaksi_sales_details')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_details.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->where('transaksi_sales_details.invoice_id', $id)
            ->get();
        $format = '%d %s |';
        $stok = [];
        foreach ($data as $a) {
            $proses = DB::table('tbl_unit')->where('produk_id', $a->produk_id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            $jumlah = $a->quantity;
            foreach ($proses as $index => $list) {
                $banyak = sizeof($proses);
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
        return view('pages.transaksi.salestransaksi.tabledetailapp', compact('datas'));
    }
}
