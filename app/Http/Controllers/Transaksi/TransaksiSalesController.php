<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\TransaksiSales;
use App\TransaksiSalesDetail;
use App\TransaksiSalesTmp;
use App\Models\Sales;
use App\Models\Customer;
use DB;
use Session;

class TransaksiSalesController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_transaksi_sales' => 'numeric',
            'id_transaksi_detail' => 'numeric',
            'id_transaksi_tmp' => 'numeric',
            'sales_id' => '',
            'stok_id' => 'numeric',
            'qty_carton' => 'numeric',
            'qty_cup' => 'numeric',
            'qty_pcs' => 'numeric',
            'qty_bungkus' => 'numeric',
            'customer_id' => 'numeric',
            'invoice_id' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'transaksi_tipe' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'note' => 'required|regex:/(^[A-Za-z0-9 .]+$)+/',

        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed'

        );
    }

    public function index()
    {
        $cabang = session()->get('cabang');
        $data['salesid'] = DB::table('tbl_sales')->where('id_cabang', $cabang)->get();
        $data['customerid'] = Customer::all();
        $data['stockid'] = DB::table('tbl_produk')
            ->join('tbl_stok', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->where('tbl_stok.id_cabang', $cabang)
            ->select('*')
            ->get();
        $data['warehouse'] = DB::table('tbl_gudang')->where('id_cabang', $cabang)->get();
        return view('pages.transaksi.salestransaksi.index', $data);
    }

    public function addkeranjang(Request $r)
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
    // api
    public function invoice(Request $r)
    {
        $type = $r->type;
        $id = strlen($r->user);
        $inv = DB::table('transaksi_sales')->where('sales_type', $type)->orderBy('id_transaksi_sales', 'desc')->first();

        if ($id == 1) {
            $user = '00' . $r->user;
        } else if ($id == 2) {
            $user = '0' . $r->user;
        } else if ($id == 3) {
            $user = $r->user;
        }

        if ($inv == NULL) {
            if ($type == 'CUSTOMER') {
                $invoice = 'CVS-' . date('Ym') . "-" . $user . '-1';
            } elseif ($type == 'TAKING ORDER') {
                $invoice = 'TOVS-' . date('Ym') . "-" . $user . '-1';
            } elseif ($type == 'CANVASING') {
                $invoice = 'CCVS-' . date('Ym') . "-" . $user . '-1';
            }
        } else {
            if ($type == 'CUSTOMER') {
                $cekinv = substr($inv->invoice_id, 15, 50);
                $plus = (int)$cekinv + 1;
                $invoice = 'CVS-' . date('Ym') . "-" . $user .  '-' . $plus;
            } elseif ($type == 'TAKING ORDER') {
                $cekinv = substr($inv->invoice_id, 16, 50);
                $plus = (int)$cekinv + 1;
                $invoice = 'TOVS-' . date('Ym') . "-" . $user .  '-' . $plus;
            } elseif ($type == 'CANVASING') {
                $cekinv = substr($inv->invoice_id, 16, 50);
                $plus = (int)$cekinv + 1;
                $invoice = 'CCVS-' . date('Ym') . "-" . $user .  '-' . $plus;
            }
        }
        // dd($cekinv);
        return response()->json(['inv' => $invoice, 'status' => 200]);
    }

    public function getSales(Request $r)
    {
        $data = Sales::findOrFail($r->sales_id);
        if ($data == TRUE) {
            return response()->json(['data' => $data, 'status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function getCustomer(Request $r)
    {
        $data = Customer::findOrFail($r->customer_id);
        if ($data == TRUE) {
            return response()->json(['data' => $data, 'status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function getProduk(Request $r)
    {
        $cab = $r->cabang;
        $id_stok = $r->id_stok;
        $data =  DB::table('tbl_produk')
            ->select('*')
            ->join('tbl_stok', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->where('tbl_stok.stok_id', $id_stok)
            ->where('tbl_stok.id_cabang', $cab)
            ->first();
        if ($data == TRUE) {
            return response()->json(['data' => $data, 'status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function tampilstok($cabang)
    {
        $data = DB::table('tbl_stok')
            ->join('tbl_produk', 'tbl_stok.produk_id', '=', 'tbl_produk.produk_id')
            ->join('tbl_suplier', 'tbl_stok.id_suplier', '=', 'tbl_suplier.id_suplier')
            ->where('tbl_stok.id_cabang', $cabang)
            ->get();
        // ====================
        $format = '%d %s';
        $stok = [];
        $datas = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            // untuk mencari nilai unitnya, karton, bungkus, pieces
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            $stokquantity = [];
            $stokquantity = [];
            foreach ($proses as $index => $list) {
                $banyak = sizeof($proses);

                if ($index == 0) {
                    $sisa = $jumlah % $list->default_value;
                    $hasilbagi = ($jumlah - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
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
                    $lebih[$index] = $sisa;
                    if ($sisa > 0) {
                        $stok[] = sprintf($format, $sisa,  $satuan[$index - 1]);
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
                'stok_id' => $d->stok_id,
                'produk_id' => $d->produk_id,
                'produk_nama' => $d->produk_nama,
                'quantity' => implode(" ", $stokquantity),
                'capital_price' => $d->produk_harga,
            );
        }
        return view("pages.transaksi.salestransaksi.caristoktransaksi", compact('datas'));
    }

    public function hargakusus(Request $r)
    {
        $id_produk = $r->id_stok;
        $id_customer = $r->customer;
        $data = DB::table('tbl_harga_khusus')
            ->where('id_customer', $id_customer)
            ->where('produk_id', $id_produk)
            ->select('spesial_nominal')
            ->first();
        if ($data == TRUE) {
            return response()->json(['data' => $data, 'status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function cekstok(Request $r)
    {
        $id_pro = $r->stok_id;
        $id_cab = $r->cabang;
        $data = DB::table('tbl_stok')
            ->join('tbl_produk', 'tbl_stok.produk_id', '=', 'tbl_produk.produk_id')
            ->where('tbl_stok.stok_id', $id_pro)
            ->where('tbl_stok.id_cabang', $id_cab)
            ->get();
        $format = '%d %s %s';
        $stok = [];
        $datas = [];
        $valuei = array();
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            // untuk mencari nilai unitnya, karton, bungkus, pieces
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $total = count($proses);
            if ($total == 1) {
                $valuei[] = $proses[0]->default_value;
            } elseif ($total == 2) {
                $valuei[] = $proses[0]->default_value;
                $valuei[] = $proses[1]->default_value;
            } elseif ($total == 3) {
                $valuei[] = $proses[0]->default_value;
                $valuei[] = $proses[1]->default_value;
                $valuei[] = $proses[2]->default_value * $proses[1]->default_value;
            }
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
                        $stok[] = sprintf($format, $sisa, $list->unit, $valuei[0]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit, $valuei[0]);
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
                        $stok[] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index], $valuei[0]);
                    } else {
                        $stok[] = sprintf($format, 0, $satuan[$index - 1], $valuei[0]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit, $valuei[1]);
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
                        $stok[] = sprintf($format, $sisa, $satuan[$index - 1], $valuei[1]);
                    } else {
                        $stok[] = sprintf($format, 0, $satuan[$index - 1], $valuei[1]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit, $valuei[2]);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }
            }
            $dataisi['isi'] = $stokquantity;
            $dataisi['total'] = $total;
        }
        return view('pages.transaksi.salestransaksi.satuantampil', $dataisi);
    }

    public function datatablessales(Request $r)
    {
        $id = Session()->get('id');
        $date = date('Y-m-d');
        $data = DB::table('transaksi_sales_tmps')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_tmps.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->select('tbl_produk.produk_id', 'tbl_produk.produk_brand', 'tbl_produk.produk_nama', 'tbl_produk.produk_harga', 'price', 'transaksi_sales_tmps.*', 'tbl_type_produk.nama_type_produk', 'tbl_stok.stok_id')
            ->where('transaksi_sales_tmps.id_user', $id)
            ->where('transaksi_sales_tmps.invoice_date', $date)
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
        return view('pages.transaksi.salestransaksi.table', compact('init'));
    }

    public function deleteitem(Request $r)
    {
        $delete = DB::table('transaksi_sales_tmps')->where('id_transaksi_tmp', $r->id_transksi)->delete();
        if ($delete == TRUE) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function rekaptransaksi(Request $r)
    {
        $input = new TransaksiSales();
        $input->sales_type = $r->salestype;
        $input->invoice_id = $r->invoiceid;
        $input->invoice_date = $r->invoicedate;
        $input->transaksi_tipe = $r->radiocash;
        $input->term_until = $r->term_util;
        $input->sales_id = $r->salesmanid;
        $input->customer_id = $r->customerid;
        $input->note = $r->note;
        $input->totalsales = $r->totalsales;
        $input->diskon = $r->potongan;
        $input->dp = $r->dp;
        $input->id_user = $r->id_user;
        $input->id_warehouse = $r->warehouse;
        if ($r->radiocash == 'Credit') {
            $input->status = 'UNPAID';
        } else {
            $input->status = 'PAID';
        }
        $input->save();

        $id_user = $r->id_user;
        $data = DB::table('transaksi_sales_tmps')->where('invoice_id', $r->invoiceid)->where('id_user', $id_user)->get();
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
        if ($input == TRUE) {
            return response()->json(['status' => 200, 'invoice_id' => $r->invoiceid]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function faktur($id, $type, $id_cabang)
    {
        $datas['data_cabang'] = DB::table('tbl_cabang')->where('id_cabang', $id_cabang)->first();
        $data = DB::table('transaksi_sales_details')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_details.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->select('tbl_produk.produk_id', 'tbl_produk.produk_brand', 'tbl_produk.produk_nama', 'tbl_produk.produk_harga', 'price', 'transaksi_sales_details.*', 'tbl_type_produk.nama_type_produk', 'tbl_stok.stok_id')
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
            $datas['init'][] = array(
                'stok_id' => $a->stok_id,
                'produk_id' => $a->produk_id,
                'nama_type_produk' => $a->nama_type_produk,
                'produk_brand' => $a->produk_brand,
                'produk_nama' => $a->produk_nama,
                'produk_harga' => number_format($a->price),
                'total' => number_format($a->quantity * $a->harga_satuan),
                'diskon' => number_format($a->diskon),
                'amount' => ($a->quantity * $a->harga_satuan) - $a->diskon,
                'id_transaksi_tmp' => $a->id_transaksi_detail,
                'quantity' => implode(" ", $stokquantity),
            );
        }
        if ($type == 'CUSTOMER') {
            $sales = DB::table('transaksi_sales')
                ->join('tbl_customer', 'tbl_customer.id_customer', 'transaksi_sales.customer_id')
                ->select('transaksi_sales.*', 'transaksi_sales.note', 'tbl_customer.*')
                ->where('transaksi_sales.invoice_id', $id)
                ->first();
            $datas['sales'] = array(
                'note' => $sales->note,
                'nama_sales' => '-',
                'invoice_date' => $sales->invoice_date,
                'transaksi_tipe' => $sales->transaksi_tipe,
                'dp' => $sales->dp,
                'diskon' => $sales->diskon,
                'nama_customer' => $sales->nama_customer,
                'alamat' => $sales->alamat,
                'kota' => $sales->kota,
                'telepon' => $sales->telepon,
                'term_until' => $sales->term_until
            );
        } else {
            $sales = DB::table('transaksi_sales')
                ->join('tbl_customer', 'tbl_customer.id_customer', 'transaksi_sales.customer_id')
                ->select('transaksi_sales.*', 'tbl_customer.*')
                ->where('transaksi_sales.invoice_id', $id)
                ->first();
            // dd($sales);
            $datas['sales'] = array(
                'note' => $sales->note,
                'id_sales' => $sales->sales_id,
                'invoice_date' => $sales->invoice_date,
                'transaksi_tipe' => $sales->transaksi_tipe,
                'diskon' => $sales->diskon,
                'dp' => $sales->dp,
                'nama_customer' => $sales->nama_customer,
                'alamat' => $sales->alamat,
                'kota' => $sales->kota,
                'telepon' => $sales->telepon,
                'term_until' => $sales->term_until
            );
        }
        $datas['inv'] = $id;
        return view("pages.transaksi.salestransaksi.faktur", $datas);
    }


    // android
    public function apiproduk($cabang)
    {
        $data = DB::table('tbl_stok')
            ->join('tbl_produk', 'tbl_stok.produk_id', '=', 'tbl_produk.produk_id')
            ->join('tbl_suplier', 'tbl_stok.id_suplier', '=', 'tbl_suplier.id_suplier')
            ->where('tbl_stok.id_cabang', $cabang)
            ->get();
        // ====================
        $format = '%d %s';
        $stok = [];
        $datas = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            // untuk mencari nilai unitnya, karton, bungkus, pieces
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            foreach ($proses as $index => $list) {
                $banyak = sizeof($proses);
                if ($index == 0) {
                    $sisa = $jumlah % $list->default_value;
                    $hasilbagi = ($jumlah - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
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
                    $lebih[$index] = $sisa;
                    if ($sisa > 0) {
                        $stok[] = sprintf($format, $sisa,  $satuan[$index - 1]);
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
                'stok_id' => $d->stok_id,
                'produk_id' => $d->produk_id,
                'produk_nama' => $d->produk_nama,
                'quantity' => implode(" ", $stokquantity),
                'capital_price' => $d->capital_price,
            );
        }
        return response()->json(['status' => 200, 'produk' => $datas]);
    }

    public function apistok(Request $r)
    {
        $id_pro = $r->stok_id;
        $id_cab = $r->cabang;
        $data = DB::table('tbl_stok')
            ->join('tbl_produk', 'tbl_stok.produk_id', '=', 'tbl_produk.produk_id')
            ->where('tbl_stok.stok_id', $id_pro)
            ->where('tbl_stok.id_cabang', $id_cab)
            ->get();
        $format = '%d %s %s';
        $stok = [];
        $datas = [];
        $valuei = array();
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            // untuk mencari nilai unitnya, karton, bungkus, pieces
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $total = count($proses);
            if ($total == 1) {
                $valuei[] = $proses[0]->default_value;
            } elseif ($total == 2) {
                $valuei[] = $proses[0]->default_value;
                $valuei[] = $proses[1]->default_value;
            } elseif ($total == 3) {
                $valuei[] = $proses[0]->default_value;
                $valuei[] = $proses[1]->default_value;
                $valuei[] = $proses[2]->default_value * $proses[1]->default_value;
            }
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
                        $stok[] = sprintf($format, $sisa, $list->unit, $valuei[0]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit, $valuei[0]);
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
                        $stok[] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index], $valuei[0]);
                    } else {
                        $stok[] = sprintf($format, 0, $satuan[$index - 1], $valuei[0]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit, $valuei[1]);
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
                        $stok[] = sprintf($format, $sisa, $satuan[$index - 1], $valuei[1]);
                    } else {
                        $stok[] = sprintf($format, 0, $satuan[$index - 1], $valuei[1]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit, $valuei[2]);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }
            }
            $dataisi['isi'] = $stokquantity;
            $dataisi['total'] = $total;
            return response()->json(['status' => 200, 'stokdata' => $dataisi]);
        }
    }

    public function apidatatable(Request $r)
    {
        $id = $r->id_sales;
        $date = date('Y-m-d');
        $data = DB::table('transaksi_sales_tmps')
            ->join('tbl_stok', 'tbl_stok.stok_id', 'transaksi_sales_tmps.stok_id')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->select('tbl_produk.produk_id', 'tbl_produk.produk_brand', 'tbl_produk.produk_nama', 'tbl_produk.produk_harga', 'price', 'transaksi_sales_tmps.*', 'tbl_type_produk.nama_type_produk', 'tbl_stok.stok_id')
            ->where('transaksi_sales_tmps.id_sales', $id)
            ->where('transaksi_sales_tmps.invoice_date', $date)
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
        return response()->json(['data' => $init]);
    }

    public function addkeranjangapi(Request $r)
    {
        $cek = DB::table('transaksi_sales_tmps')
            ->where('invoice_id', $r->invoiceid)
            ->where('stok_id', $r->produkid)
            ->where('id_sales', $r->id_sales)
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
            $input->id_sales = $r->id_sales;
            $input->save();
            if ($input == TRUE) {
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            }
        }
    }

    public function totalpenjualan($id_cabang, $bulan)
    {
        $tahun = date('Y');
        $data = DB::table('transaksi_sales')->join('transaksi_sales_details as d', 'd.invoice_id', '=', 'transaksi_sales.invoice_id')->join('tbl_user', 'tbl_user.id_user', '=', 'transaksi_sales.id_user')->where('tbl_user.id_cabang', $id_cabang)->whereMonth('transaksi_sales.invoice_date', $bulan)->whereYear('transaksi_sales.invoice_date', $tahun)->where('approve', 1)->select(DB::raw('SUM(totalsales-transaksi_sales.diskon) as total_sales'))->get();
        $jumlah = "Rp. " . number_format($data[0]->total_sales, 2, ',', '.');
        return response()->json(['data' => $jumlah]);
    }
}
