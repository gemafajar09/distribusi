<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use App\Returnsalestmp;
use App\Returnsales;
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
            $cekinv = substr($inv->id_returnsales, 15, 50);
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
        $format = '%d %s |';
        $init = [];
        $stok = [];
        foreach ($data as $a) {
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
                if($index == 0 ){
                    $sisa = $jumlah % $list->default_value;
                    $hasilbagi = ($jumlah-$sisa)/$list->default_value;
                    $satuan[$index] = $list->unit;
                    $value[$index] = $list->default_value;
                    $lebih[$index] = $sisa;
                    if ($sisa > 0){
                        $stok[] = sprintf($format, $sisa, $list->unit);
                    }
                    if($banyak == $index+1){
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $satuan[$index-1]);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }else if($index == 1){
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi-$sisa)/$list->default_value;
                    $satuan[$index] = $list->unit;
                    $value[$index] = $list->default_value;
                    $lebih[$index] = $sisa;
                    if($sisa > 0){
                        $stok[] = sprintf($format, $sisa+$lebih[$index-1], $satuan[$index-1]);
                    }else{
                        $stok[] = sprintf($format, 0, $satuan[$index-1]);
                    }
                    if($banyak == $index+1){
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }else if($index == 2){
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi-$sisa)/$list->default_value;
                    $satuan[$index] = $list->unit;
                    $value[$index] = $list->default_value;
                    $lebih[$index] = $sisa;
                    if($sisa > 0){
                        $stok[] = sprintf($format, $sisa, $satuan[$index-1]);
                    }else{
                        $stok[] = sprintf($format, 0, $satuan[$index-1]);
                    }
                    if($banyak == $index+1){
                        $satuan = array();
                        $stok[] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }    
            } 
            $init[] = array(
                'stok_id' => $a->produk_id,
                'produk_nama' => $a->produk_nama,
                'produk_harga' => $a->price,
                'total' => $a->quantity * $a->harga_satuan,
                'amount' => ($a->quantity * $a->harga_satuan) - $a->diskon,
                'id_tmpreturn' => $a->id_tmpreturn,
                'diskon' => $a->diskon,
                'note' => $a->note,
                'quantity' => implode(" ", $stokquantity)
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

    public function rekaptransaksir(Request $r)
    {
        $input = new Returnsales();
        $input->id_returnsales	 = $r->invoiceid;
        $input->return_date = $r->invoicedate;
        $input->compensation = $r->compensation;
        $input->date_term = $r->term_util;
        $input->id_sales_inv = $r->idsalesinv;
        $input->totalbiaya = $r->totalsales;
        $input->id_user = $r->id_user;
        $input->save();

        $id_user = $r->id_user;
        $data = DB::table('returnsalestmps')->where('id_returnsales', $r->invoiceid)->where('id_user', $id_user)->get();
        foreach ($data as $a) {
            DB::table('returnsalesdetail')->insert([
                'id_returnsales' => $a->id_returnsales,
                'return_date' => $a->return_date,
                'id_stok' => $a->id_stok,
                'price' => $a->price,
                'quantity' => $a->quantity,
                'diskon' => $a->diskon,
                'id_user' => $a->id_user,
                'note' => $a->note,
                'harga_satuan' => $a->harga_satuan
            ]);
            // update stok
            $lihat = DB::table('tbl_stok')->where('stok_id',$a->id_stok)->first();
            $stok = $lihat->jumlah + $a->quantity;
            DB::table('tbl_stok')->where('stok_id',$a->id_stok)->update(['jumlah' => $stok]);
            // hapus
            DB::table('returnsalestmps')->where('id_tmpreturn', $a->id_tmpreturn)->delete();
        }
        if ($input == TRUE) {
            return response()->json(['status' => 200, 'invoice_id' => $r->invoiceid]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

}