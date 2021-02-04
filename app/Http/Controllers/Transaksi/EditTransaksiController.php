<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TransaksiSalesTmp;
use App\TransaksiSales;
use DB;

class EditTransaksiController extends Controller
{
    public function index($id)
    {
        $cabang = session()->get('cabang');
        $data['salesid'] = DB::table('tbl_sales')->where('id_cabang', $cabang)->get();
        $data['customerid'] = DB::table('tbl_customer')->get();
        $data['stockid'] = DB::table('tbl_produk')
            ->join('tbl_stok', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->where('tbl_stok.id_cabang', $cabang)
            ->select('*')
            ->get();
        $data['warehouse'] = DB::table('tbl_gudang')->where('id_cabang', $cabang)->get();
        $data['transaksi'] = DB::table('transaksi_sales')
            ->leftJoin('tbl_customer', 'transaksi_sales.customer_id', 'tbl_customer.id_customer')
            ->leftJoin('tbl_sales', 'transaksi_sales.sales_id', 'tbl_sales.id_sales')
            ->where('transaksi_sales.invoice_id', $id)->first();
        $details = DB::table('transaksi_sales_details')->where('invoice_id', $data['transaksi']->invoice_id)->get();
        foreach ($details as $a) {
            DB::table('transaksi_sales_tmps')->insert([
                'invoice_id' => $a->invoice_id,
                'invoice_date' => $a->invoice_date,
                'stok_id' => $a->stok_id,
                'price' => $a->price,
                'quantity' => $a->quantity,
                'diskon' => $a->diskon,
                'id_user' => $a->id_user,
                'harga_satuan' => $a->harga_satuan
            ]);
            DB::table('transaksi_sales_details')->where('id_transaksi_detail', $a->id_transaksi_detail)->delete();
        }
        return view('pages.transaksi.salestransaksi.edit', $data);
    }

    public function tambahkeranjang(Request $r)
    {
        $cek = DB::table('transaksi_sales_tmps')
            ->where('invoice_id', $r->invoiceid)
            ->where('stok_id', $r->produkid)
            ->where('id_user', $r->id_user)
            ->first();

        if ($cek == TRUE) {
            $total = $r->quantity + $cek->quantity;
            $input = DB::table('transaksi_sales_tmps')->where('id_transaksi_tmp', $cek->id_transaksi_tmp)->update(['quantity' => $total]);
            if ($input == TRUE) {
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            }
        } else {
            $input = new TransaksiSalesTmp();
            $input->invoice_id = $r->invoiceid;
            $input->invoice_date = date('Y-m-d');
            $input->stok_id = $r->stockId;
            $input->price = $r->prices;
            $input->quantity = $r->quantity;
            $input->diskon = $r->amount;
            $input->id_user = $r->id_user;
            $input->harga_satuan = $r->satuan;
            $input->save();
            if ($input == TRUE) {
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            }
        }
    }

    public function datatableskeranjang(Request $r)
    {
        $id = Session()->get('id');
        $data = DB::table('transaksi_sales_tmps')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_tmps.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->select('tbl_produk.produk_id', 'tbl_produk.produk_brand', 'tbl_produk.produk_nama', 'tbl_produk.produk_harga', 'price', 'transaksi_sales_tmps.*', 'tbl_type_produk.nama_type_produk', 'tbl_stok.stok_id')
            ->where('transaksi_sales_tmps.id_user', $id)
            ->get();
        // dd($data);
        $init = [];
        $format = '%d %s |';
        $stok = [];
        foreach ($data as $i => $a) {
            $proses = DB::table('tbl_unit')->where('produk_id', $a->produk_id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            // nilai jumlah dari tabel stok
            $jumlah = $a->quantity;
            $hasilbagi = 0;
            $stokquantity = [];
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
            $init[] = array(
                'stok_id' => $a->stok_id,
                'produk_id' => $a->produk_id,
                'nama_type_produk' => $a->nama_type_produk,
                'produk_brand' => $a->produk_brand,
                'produk_nama' => $a->produk_nama,
                'produk_harga' => number_format($a->price),
                'total' => number_format($a->quantity * $a->harga_satuan),
                'diskon' => number_format($a->diskon),
                'tot' => 0,
                'amount' => ($a->quantity * $a->harga_satuan) - $a->diskon,
                'id_transaksi_tmp' => $a->id_transaksi_tmp,
                'quantity' => implode(" ", $stokquantity)
            );
        }
        // dd($init);
        return view('pages.transaksi.salestransaksi.tableedit', compact('init'));
    }

    public function registransaksiedit(Request $r)
    {
        if ($r->radiocash == 'Credit') {
            $status = 'UNPAID';
        } else {
            $status =  'PAID';
        }
        $update = DB::table('transaksi_sales')->where('invoice_id', $r->invoiceid)
            ->update([
                'sales_type' => $r->salestype,
                'invoice_date' => $r->invoicedate,
                'transaksi_tipe' => $r->radiocash,
                'term_until' => $r->term_util,
                'sales_id' => $r->salesmanid,
                'customer_id' => $r->customerid,
                'note' => $r->note,
                'totalsales' => $r->totalsales,
                'diskon' => $r->potongan,
                'dp' => $r->dp,
                'id_user' => $r->id_user,
                'id_warehouse' => $r->warehouse,
                'status' => $status
            ]);
        $id_user = $r->id_user;
        $data = DB::table('transaksi_sales_tmps')->where('invoice_id', $r->invoiceid)->where('id_user', $id_user)->get();
        // dd($data);
        foreach ($data as $a) {
            DB::table('transaksi_sales_details')->insert([
                'invoice_id' => $a->invoice_id,
                'invoice_date' => $a->invoice_date,
                'stok_id' => $a->stok_id,
                'price' => $a->price,
                'quantity' => $a->quantity,
                'diskon' => $a->diskon,
                'id_user' => $a->id_user,
                'harga_satuan' => $a->harga_satuan
            ]);
            $lihat = DB::table('tbl_stok')->where('stok_id', $a->stok_id)->first();
            $stok = $lihat->jumlah - $a->quantity;
            DB::table('tbl_stok')->where('stok_id', $a->stok_id)->update(['jumlah' => $stok]);
            DB::table('transaksi_sales_tmps')->where('id_transaksi_tmp', $a->id_transaksi_tmp)->delete();
        }
        if ($update == TRUE) {
            return response()->json(['status' => 200, 'invoice_id' => $r->invoiceid]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
