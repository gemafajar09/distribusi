<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPurchaseReturn;
use App\Models\Stok;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
class TransaksiPurchaseReturnController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_transaksi_purchase_return'=>'numeric',
            'return_id'=>'',
            'return_date'=>'date',
            'id_suplier'=>'',
            'note_return'=>'numeric',
            'jumlah_return'=>'numeric',
            'price'=>'numeric',
            'id_cabang'=>'numeric',
            'register'=>'numeric',
            'status'=>'numeric'
        );

        $this->dataisi = [];
        $this->datatable = [];
    }

    public function index(){
        return view('pages.transaksi.purchasetransaksi.purchase_return');
    }

    public function data($id){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder($id);
        $format = '%d,%s';
        $stok = [];
        $this->dataisi = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            $harga = $d->produk_harga;
            $stok_id = $d->stok_id;
            $proses = DB::table('tbl_unit')->where('produk_id',$id)
            ->join('tbl_satuan','tbl_unit.maximum_unit_name','=','tbl_satuan.id_satuan')
            ->select('id_unit','nama_satuan as unit','default_value')
            ->orderBy('id_unit','ASC')
            ->get();
            $hasilbagi=0;
            foreach ($proses as $index => $list) {
                $banyak = sizeof($proses);
                if($index == 0 ){
                $sisa = $jumlah % $list->default_value;
                $hasilbagi = ($jumlah-$sisa)/$list->default_value;
                $satuan[$index] = $list->unit;
                $lebih[$index] = $sisa;
                
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
            
            $this->dataisi[] = ["stok"=>$stokquantity,"stok_id"=>$stok_id,"satuan_price"=>$harga,"produk_id"=>$id];
        }
       
        // return datatables()->of($this->dataisi)->toJson();
    }

    public function join_builder($id){
        $data = DB::table('tbl_stok as tmp')
            ->where('stok_id',$id)
            ->join('tbl_produk as prdk','prdk.produk_id','=','tmp.produk_id')
            ->select('jumlah','prdk.produk_id as produk_id','capital_price','stok_id','produk_harga')
            ->get();
            return $data;
    }

    public function getStok($id){
        $run = $this->data($id);
        $data = $this->dataisi;
        return $data;
    }

    public function join_data_return($id_cabang){
        $data = DB::table('transaksi_purchase_return as t')
                ->where('register','0')
                ->where('status','0')
                ->where('t.id_cabang',$id_cabang)
                ->join('tbl_stok as s','s.stok_id','=','t.stok_id')
                ->join('tbl_produk as p','p.produk_id','=','s.produk_id')
                ->join('tbl_suplier as sp','sp.id_suplier','=','t.id_suplier')
                ->select('id_transaksi_purchase_return','jumlah_return','produk_nama','capital_price','nama_suplier','note_return','s.produk_id as produk_id','return_date','return_id','s.stok_id as stok_id','produk_harga')
                ->get();
            return $data;
    }

    public function datatable($id_cabang){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_data_return($id_cabang);
        $format = '%d %s ';
        $stok = [];
        $this->datatable= [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah_return;
            $harga = $d->produk_harga;
            $proses = DB::table('tbl_unit')->where('produk_id',$id)
            ->join('tbl_satuan','tbl_unit.maximum_unit_name','=','tbl_satuan.id_satuan')
            ->select('id_unit','nama_satuan as unit','default_value')
            ->orderBy('id_unit','ASC')
            ->get();
            $hasilbagi=0;
            foreach ($proses as $index => $list) {
                $banyak = sizeof($proses);
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
            $d->total = $harga * $d->jumlah_return;
            // check note_return
            if($d->note_return == 1){
                $note = "Broken Product";
            }else if($d->note_return == 2){
                $note = "Expired Product";
            }else if($d->note_return == 3){
                $note = "Mismatch Order Product";
            }else if($d->note_return == 4){
                $note = "Unsold Product";
            }
            $this->datatable[] = ["produk_id"=>$d->produk_id,"produk_nama"=>$d->produk_nama,"stok_quantity"=>$d->stok_quantity,"price"=>$d->total,"id_transaksi_purchase_return"=>$d->id_transaksi_purchase_return,"return_date"=>$d->return_date,"stok_id"=>$d->stok_id,"nama_suplier"=>$d->nama_suplier,"price"=>number_format($d->total,2,'.',''),"note_return"=>$note,"return_id"=>$d->return_id,"jumlah_return"=>$d->jumlah_return];   
        }
       
        return datatables()->of($this->datatable)->toJson();
    }

    public function add(Request $request)
    {
        // id_brand belum final
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            return response()->json(['id' => TransaksiPurchaseReturn::create($request->all())->id_transaksi_purchase_tmp, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    } 

    public function register($id_cabang){
        $data1 = $this->datatable($id_cabang);
        $datatmp =  $this->datatable;
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $calculate = DB::table('transaksi_purchase_return')->where('id_cabang',$id_cabang)->where('register','0')->sum('price');
        $update = DB::table('transaksi_purchase_return')->where('id_cabang',$id_cabang)->update(array('register' => '1'));
        foreach ($datatmp as $d) {
            $stok_id = $d['stok_id'];
            $jumlah = $d['jumlah_return'];
            $data = Stok::find($stok_id);
            $data->decrement('jumlah',$jumlah);
            $data->save();
        }
        return view('report.purchase_transaksi_return',compact(['datatmp','calculate','data_cabang']));
    }

    public function remove(Request $request, $id){
        try{
            $data = TransaksiPurchaseReturn::findOrFail($id);
            $data->delete();
            return response()->json(['message'=>'Data Berhasil Di Hapus','status'=>200]);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }

    public function generateInvoicePurchaseReturn(Request $request,$id){
        $cabang = $id;
        $tanggal = date('ymd');
        $codeinv = DB::table('transaksi_purchase_return')->where('id_cabang',$cabang)->where('register','1')->orderBy('id_transaksi_purchase_return','desc')->first();
        if($codeinv == NULL){
            $inv= "RTP-".$tanggal.'-'.$cabang.'-1';
        }else{
            $cekinv = substr($codeinv->return_id,13,50);
            $plus = (int)$cekinv + 1;
            $inv= "RTP-".$tanggal.'-'.$cabang.'-'.$plus;
        }
        return response()->json(['invoice'=>$inv]);
    }
}
