<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Stok;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
 
class StokController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'stok_id' => 'numeric',
            'produk_id' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'id_cabang' => 'required|numeric',
        );
    }
    
    public function index(){
        $id_cabang = session()->get('cabang');
        $gudang = DB::table('tbl_gudang')->where('id_cabang',$id_cabang)->get();
        return view('pages.admin.stok.index',compact('gudang'));
    }

    public function datatable($id_cabang){

        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder($id_cabang);
        $format = '%d %s  ';
        $stok = [];
        $dataisi = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            $capital_price = $d->capital_price;
            $harga = $d->capital_price;
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
            $stokquantity = [];
            foreach ($proses as $index => $list) {
                $banyak =  sizeof($proses);
                if ($index == 0) {
                    $sisa = $jumlah % $list->default_value;
                    $hasilbagi = ($jumlah - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
                    $lebih[$index] = $sisa;
                    $harga = $harga / $list->default_value;
                    if ($sisa > 0) {
                        $stok[$index] = sprintf($format, $sisa, $list->unit);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                } else if ($index == 1) {
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
                    $lebih[$index] = $sisa;
                    $harga = $harga / $list->default_value;
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                } else if ($index == 2) {
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi - $sisa) / $list->default_value;
                    $satuan[$index] = $list->unit;
                    $lebih[$index] = $sisa;
                    $harga = $harga / $list->default_value;
                    if ($sisa > 0) {
                        $stok[$index - 1] = sprintf($format, $sisa,  $satuan[$index - 1]);
                    }
                    if ($banyak == $index + 1) {
                        $satuan = array();
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }
            }
            $jumlah_stok = implode(" ", $stokquantity);
            $d->stok_quantity = $jumlah_stok;
            $d->total_harga = $harga * $d->jumlah;

            $dataisi[] = ["id_unit"=>$id,"produk_nama"=>$d->produk_nama,"capital_price"=>"Rp " . number_format($capital_price,2,',','.'),"stok_harga"=>"Rp " . number_format($d->total_harga,2,',','.'),"jumlah"=>$d->stok_quantity];

        }

        return datatables()->of($dataisi)->toJson();
    }

    public function join_builder($id_cabang){
        // tempat join hanya menselect beberapa field tambah join brand
        $data = DB::table('tbl_stok')
                ->where('id_cabang',$id_cabang)
                ->join('tbl_produk','tbl_stok.produk_id','=','tbl_produk.produk_id')
                ->get();
        return $data;
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Stok::findOrFail($id);
            } else {
                $data = Stok::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function add(Request $request)
    {
        // id_brand belum final
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            return response()->json(['id' => Stok::create($request->all())->stok_id, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input('stok_id');
        try {
            $edit = Stok::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                $edit->produk_id = $request->input('produk_id');
                $edit->jumlah = $request->input('jumlah');
                $edit->id_cabang = $request->input('id_cabang');
                $edit->save();
                return response()->json(['message' => 'Data Berhasil Di Edit', 'data' => $edit, 'status' => 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Stok::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function datatablegudang($id_gudang){
        // untuk datatables Sistem Join Query Builder
        $data = DB::table('tbl_stok')
                ->where('id_gudang',$id_gudang)
                ->join('tbl_produk','tbl_stok.produk_id','=','tbl_produk.produk_id')
                ->get();
        $format = '%d %s  ';
        $stok = [];
        $dataisi = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            $capital_price = $d->capital_price;
            $harga = $d->capital_price;
            $proses = DB::table('tbl_unit')->where('produk_id',$id)
                        ->join('tbl_satuan','tbl_unit.maximum_unit_name','=','tbl_satuan.id_satuan')
                        ->select('id_unit','nama_satuan as unit','default_value')
                        ->orderBy('id_unit','ASC')
                        ->get();
            $hasilbagi=0;
            $stokquantity=[];
            foreach ($proses as $index => $list) {
                $banyak =  sizeof($proses);
                if($index == 0 ){
                $sisa = $jumlah % $list->default_value;
                $hasilbagi = ($jumlah-$sisa)/$list->default_value;
                $satuan[$index] = $list->unit;
                $lebih[$index] = $sisa;
                $harga = $harga / $list->default_value;
                if ($sisa > 0){
                    $stok[$index] = sprintf($format, $sisa, $list->unit);
                }
                 if($banyak == $index+1){
                    $satuan = array();
                    $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    $stokquantity = array_values($stok);
                    $stok = array();
                }
                }else if($index == 1){
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi-$sisa)/$list->default_value;
                    $satuan[$index] = $list->unit;
                    $lebih[$index] = $sisa;
                    $harga = $harga / $list->default_value;
                    if($sisa > 0){
                        $stok[$index-1] = sprintf($format, $sisa+$lebih[$index-1], $satuan[$index-1]);
                    }
                    if($banyak == $index+1){
                        $satuan = array();
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }else if($index == 2){
                    $sisa = $hasilbagi % $list->default_value;
                    $hasilbagi = ($hasilbagi-$sisa)/$list->default_value;
                    $satuan[$index] = $list->unit;
                    $lebih[$index] = $sisa;
                    $harga = $harga / $list->default_value;
                    if($sisa > 0){
                        $stok[$index-1] = sprintf($format, $sisa,  $satuan[$index-1]);
                    }
                    if($banyak == $index+1){
                        $satuan = array();
                        $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                        $stokquantity = array_values($stok);
                        $stok = array();
                    }
                }    
            }
            $jumlah_stok = implode(" ",$stokquantity);
            $d->stok_quantity = $jumlah_stok;
            $d->total_harga = $harga * $d->jumlah;
            $dataisi[] = ["id_unit"=>$id,"produk_nama"=>$d->produk_nama,"capital_price"=>"Rp " . number_format($capital_price,2,',','.'),"stok_harga"=>"Rp " . number_format($d->total_harga,2,',','.'),"jumlah"=>$d->stok_quantity,'stok_id'=>$d->stok_id];
        }
       
        return datatables()->of($dataisi)->toJson();
        
    }
    
    public function stokCleaning($id_cabang){
        $jumlah_stok = 0;
        $data = DB::table('tbl_stok')->where('id_cabang',$id_cabang)->where('jumlah',$jumlah_stok)->delete();
        return response()->json(['status'=>200]);
    }
    
    public function stokprice($id_cabang){
        $id_gudang = DB::table('tbl_gudang')->where('id_cabang',$id_cabang)->first();
        $data = DB::table('tbl_stok')->join('tbl_produk as prdk','prdk.produk_id','=','tbl_stok.produk_id')->where('id_gudang',$id_gudang->id_gudang)->get();
        $total = 0;
        foreach ($data as $d) {
            $id = $d->produk_id;
            $harga = $d->capital_price;
            $proses = DB::table('tbl_unit')->where('produk_id',$id)
                        ->join('tbl_satuan','tbl_unit.maximum_unit_name','=','tbl_satuan.id_satuan')
                        ->select('id_unit','nama_satuan as unit','default_value')
                        ->orderBy('id_unit','ASC')
                        ->get();
            foreach ($proses as $index => $list) {
                if($index == 0 ){
                    $harga = $harga / $list->default_value;
                }else if($index == 1){
                    $harga = $harga / $list->default_value;
                }else if($index == 2){
                    $harga = $harga / $list->default_value;  
                }    
            }
            $total += $harga * $d->jumlah;
        }

        return response()->json(['data'=>"Rp. " . number_format($total,2,',','.')]);
    }
    
    public function profit($id_cabang,$bulan){
        $tahun = date('Y');
        $data_transaksi_sales = DB::table('transaksi_sales')
                                ->join('transaksi_sales_details as d','d.invoice_id','=','transaksi_sales.invoice_id')
                                ->join('tbl_user','tbl_user.id_user','=','transaksi_sales.id_user')
                                ->where('tbl_user.id_cabang',$id_cabang)
                                ->whereMonth('transaksi_sales.invoice_date',$bulan)
                                ->whereYear('transaksi_sales.invoice_date',$tahun)
                                ->where('approve',1)
                                ->select(DB::raw('SUM(totalsales-transaksi_sales.diskon) as total_sales'))->get();
                                
        
        
        $data = DB::table('transaksi_sales_details')->whereMonth('transaksi_sales_details.invoice_date',$bulan)->whereYear('transaksi_sales_details.invoice_date',$tahun)
                ->join('tbl_stok','tbl_stok.stok_id','=','transaksi_sales_details.stok_id')
                ->join('tbl_user','tbl_user.id_user','=','transaksi_sales_details.id_user')
                ->join('transaksi_sales','transaksi_sales.invoice_id','=','transaksi_sales_details.invoice_id')
                ->where('approve',1)
                ->where('tbl_user.id_cabang',$id_cabang)
                ->select('quantity','transaksi_sales_details.price as capital_price','tbl_stok.produk_id as produk_id')->get();
                
        $total_modal = 0;
        
        foreach ($data as $d) {
            $id = $d->produk_id;
            $harga_modal = $d->capital_price;
            
            $proses = DB::table('tbl_unit')->where('produk_id',$id)
                        ->join('tbl_satuan','tbl_unit.maximum_unit_name','=','tbl_satuan.id_satuan')
                        ->select('id_unit','nama_satuan as unit','default_value')
                        ->orderBy('id_unit','ASC')
                        ->get();
            foreach ($proses as $index => $list) {
                if($index == 0 ){
                    $harga_modal = $harga_modal / $list->default_value;
                    
                }else if($index == 1){
                    $harga_modal = $harga_modal / $list->default_value;
                    
                }else if($index == 2){
                    $harga_modal = $harga_modal / $list->default_value;
                     
                }    
            }
            $total_modal += $harga_modal * $d->quantity;
        }
        return response()->json(['data'=>"Rp. " . number_format($data_transaksi_sales[0]->total_sales - $total_modal,2,',','.')]);
    }
}
