<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Returnsalestmp;
use App\Models\Sales;
use App\Models\Customer;
use DB;
use Session;

class ReturnsalesController extends Controller
{
    public function index()
    {
        $cabang = Session()->get('cabang');
        $id = strlen(Session()->get('id'));
        $inv = DB::table('returnsales')->orderBy('id_returnsales', 'desc')->first();
        
        if ($id == 1) {
            $user = '00' . Session()->get('id');
        } else if ($id == 2) {
            $user = '0' . Session()->get('id');
        } else if ($id == 3) {
            $user = Session()->get('id');
        }

        if ($inv == NULL) {
            $invoice = 'RTS-' . date('Ym') . "-" . $user . '-1';
        } else {
            $cekinv = substr($inv->invoice_id, 15, 50);
            $plus = (int)$cekinv + 1;
            $invoice = 'RTS-' . date('Ym') . "-" . $user .  '-' . $plus;
        }
        $data['inv'] = $invoice;
        $data['stockid'] = DB::table('tbl_produk')
            ->join('tbl_stok', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->where('tbl_stok.id_cabang', $cabang)
            ->select('*')
            ->get();
        return view('pages.transaksi.salestransaksi.returntransaksi',$data);
    }

    public function showreturdetail($nama,$serch,$view,$type)
    {
        // $data = [];
        if($view == 'ALL')
        {
            if($type == 'all')
            {
                if($serch == 'ALL')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_sales','tbl_sales.id_sales','transaksi_sales.sales_id')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.invoice_id', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.invoice_date', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.transaksi_tipe', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.term_until', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.sales_id', 'like', '%'.$nama.'%')
                    ->orWhere('tbl_sales.nama_sales', 'like', '%'.$nama.'%')
                    ->orWhere('tbl_customer.nama_customer', 'like', '%'.$nama.'%')->get();
                }
                elseif($serch == 'INVOICE ID')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.invoice_id', 'like', '%'.$nama.'%')->get();
                }
                elseif($serch == 'INVOICE TYPE')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.transaksi_tipe', 'like', '%'.$nama.'%')->get();
                }
                elseif($serch == 'SALESMAN')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_sales','tbl_sales.id_sales','transaksi_sales.sales_id')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('tbl_sales.nama_sales', 'like', '%'.$nama.'%')->get(); 
                }
                elseif($serch == 'CUSTOMER')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('tbl_customer.nama_customer', 'like', '%'.$nama.'%')->get();  
                }
            }
            else
            {
                if($serch == 'ALL')
                {
                    // 
                }
                elseif($serch == 'INVOICE ID')
                {
                    // 
                }
                elseif($serch == 'INVOICE TYPE')
                {
                    // 
                }
                elseif($serch == 'SALESMAN')
                {
                    // 
                }
                elseif($serch == 'CUSTOMER')
                {
                    // 
                }
            }
        }
        else
        {
            if($type == 'all')
            {
                if($serch == 'ALL')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.sales_type',$view)
                    ->where('transaksi_sales.invoice_id', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.invoice_date', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.transaksi_tipe', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.term_until', 'like', '%'.$nama.'%')
                    ->orWhere('transaksi_sales.sales_id', 'like', '%'.$nama.'%')
                    ->orWhere('tbl_customer.nama_customer', 'like', '%'.$nama.'%')->get();
                }
                elseif($serch == 'INVOICE ID')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.sales_type',$view)
                    ->where('transaksi_sales.invoice_id', 'like', '%'.$nama.'%')->get();
                }
                elseif($serch == 'INVOICE TYPE')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.sales_type',$view)
                    ->where('transaksi_sales.transaksi_tipe', 'like', '%'.$nama.'%')->get();
                }
                elseif($serch == 'SALESMAN')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_sales','tbl_sales.id_sales','transaksi_sales.sales_id')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.sales_type',$view)
                    ->where('tbl_sales.nama_sales', 'like', '%'.$nama.'%')->get(); 
                }
                elseif($serch == 'CUSTOMER')
                {
                    $data['detail'] = DB::table('transaksi_sales')
                    ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
                    ->where('transaksi_sales.sales_type',$view)
                    ->where('tbl_customer.nama_customer', 'like', '%'.$nama.'%')->get();  
                }
            }
            else
            {
                if($serch == 'ALL')
                {
                    // 
                }
                elseif($serch == 'INVOICE ID')
                {
                    // 
                }
                elseif($serch == 'INVOICE TYPE')
                {
                    // 
                }
                elseif($serch == 'SALESMAN')
                {
                    // 
                }
                elseif($serch == 'CUSTOMER')
                {
                    // 
                }
            }
        }
        $data['inv'] = 123;
        return view('pages.transaksi.salestransaksi.tabelreturndetail',$data);
    }

    public function ambil(Request $r)
    {
        $id = $r->id_transaksi;
        $data = DB::table('transaksi_sales')
        ->join('tbl_customer','tbl_customer.id_customer','transaksi_sales.customer_id')
        ->join('tbl_sales','tbl_sales.id_sales','transaksi_sales.sales_id')
        ->where('id_transaksi_sales',$id)->first();
        echo json_encode($data);
    }

    public function tmpdata()
    {
        $id = Session()->get('id');
        $date = date('Y-m-d');
        $data = DB::table('returnsalestmps')
        ->join('tbl_stok', 'tbl_stok.stok_id', 'returnsalestmps.id_stok')
        ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
        ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
        ->select('tbl_produk.produk_id', 'tbl_produk.produk_brand', 'tbl_produk.produk_nama', 'tbl_produk.produk_harga', 'price', 'returnsalestmps.*', 'tbl_type_produk.nama_type_produk', 'tbl_stok.stok_id')
        ->where('returnsalestmps.id_user', $id)
        ->where('returnsalestmps.return_date', $date)
        ->get();
        // dd($data);
        $init = [];
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
            $init[] = array(
                'stok_id' => $a->stok_id,
                'produk_nama' => $a->produk_nama,
                'produk_harga' => $a->price,
                'total' => $a->quantity * $a->harga_satuan,
                'amount' => ($a->quantity * $a->harga_satuan),
                'id_tmpreturn' => $a->id_tmpreturn,
                'note' => $a->note,
                'quantity' => implode(" ", $stok)
            );
        }
        return view('pages.transaksi.salestransaksi.tabelreturntmp',compact('init'));
    }

    public function deleteitemr(Request $r)
    {
        $delete = DB::table('returnsalestmps')->where('id_tmpreturn', $r->id_transksi)->delete();
        if ($delete == TRUE) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function addkeranjangr(Request $r)
    {
        $cek = DB::table('returnsalestmps')
            ->where('id_returnsales', $r->invoiceid)
            ->where('id_stok', $r->produkid)
            ->where('id_user', $r->id_user)
            ->first();

        if ($cek == TRUE) {
            $total = $r->quantity + $cek->quantity;
            $input = DB::table('returnsalestmps')->where('id_detailreturn', $cek->id_detailreturn )->update(['quantity' => $total]);
            if ($input == TRUE) {
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            }
        } else {
            $input = new Returnsalestmp();
            $input->id_returnsales = $r->invoiceid;
            $input->return_date = date('Y-m-d');
            $input->id_stok = $r->id_stok;
            $input->price = $r->prices;
            $input->quantity = $r->quantity;
            $input->diskon = $r->disc;
            $input->id_user = $r->id_user;
            $input->note = $r->note;
            $input->harga_satuan = $r->hargasatuan;
            $input->save();
            if ($input == TRUE) {
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            } else {
                return response()->json(['status' => 422, 'message' => 'Data Tidak Valid']);
            }
        }
    }

}