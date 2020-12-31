<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiPurchaseTmp;
use App\Models\TransaksiPurchase;
use App\Models\TransaksiPurchaseDetail;
use App\Models\Stok;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
class TransaksiPurchaseDetailController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_transaksi_purchase_detail'=>'numeric',
            'invoice_id'=>'string',
            'invoice_date'=>'date',
            'transaksi_tipe'=>'numeric',
            'term_until'=>'date',
            'id_suplier'=>'',
            'produk_id'=>'',
            'quantity'=>'numeric',
            'unit_satuan_price'=>'numeric',
            'diskon'=>'numeric',
            'total_price'=>'numeric',
            'id_cabang'=>'numeric',
            'id_gudang'=>''
        );
        $this->dataisi = [];
        $this->datatable = [];
    }
    public function index(){
        return view('pages.transaksi.purchasetransaksi.aproval');
    }

    public function datatable($cabang,$invoice_id){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder($cabang,$invoice_id);
        $format = '%d %s ';
        $stok = [];
        $this->dataisi = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->quantity;
            $harga = $d->unit_satuan_price;
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
            $d->total = $harga * $d->quantity;
            $this->dataisi[] = ["produk_id"=>$d->produk_id,"nama_type_produk"=>$d->nama_type_produk,"produk_brand"=>$d->produk_brand,"produk_nama"=>$d->produk_nama,"unit_satuan_price"=>$d->unit_satuan_price,"stok_quantity"=>$d->stok_quantity,"diskon"=>$d->diskon,"total_price"=>$d->total_price,"total"=>$d->total,"id_transaksi_purchase_detail"=>$d->id_transaksi_purchase_detail,"invoice_id"=>$d->invoice_id,"invoice_date"=>$d->invoice_date,"transaksi_tipe"=>$d->transaksi_tipe];   
        }
       
        return response()->json(['data'=>$this->dataisi]);
    }

    public function join_builder($cabang,$id){
        $data = TransaksiPurchase::find($id);
        $inv = $data->invoice_id;
        $status='0';
        $data = DB::table('transaksi_purchase_detail as tmp')
            ->where('status',$status)
            ->where('id_cabang',$cabang)
            ->where('invoice_id',$inv)
            ->join('tbl_produk as a','a.produk_id','=','tmp.produk_id')
            ->join('tbl_type_produk as b','b.id_type_produk','=','a.id_type_produk')
            ->select('id_transaksi_purchase_detail','invoice_id','invoice_date','transaksi_tipe','tmp.produk_id as produk_id','nama_type_produk','produk_brand','produk_nama','unit_satuan_price','quantity','diskon','total_price','status')
            ->get();
            return $data;
    }
    
    public function all_data($cabang){
        $status='0';
        $data = DB::table('transaksi_purchase as t')
            ->where('status',$status)
            ->where('t.id_cabang',$cabang)
            ->join('tbl_suplier as s','s.id_suplier','=','t.id_suplier')
            ->select('invoice_id','invoice_date','transaksi_tipe','nama_suplier','total','diskon','bayar','sisa','id_transaksi_purchase')
            ->get();
        if(empty($data)){
            $data = [];
        }
        return datatables()->of($data)->toJson();
    }



    public function approvalPurchase(Request $request){
       $id = $request->input('id');
       $status = $request->input('status');
       $datapurchase = TransaksiPurchase::find($id);
       $inv = $datapurchase->invoice_id;
       $update = TransaksiPurchaseDetail::where('invoice_id',$inv)->get();
        if($status == 1){
            $datapurchase->status = '1';
            $datapurchase->save();
            $updatedetail = TransaksiPurchaseDetail::where('invoice_id',$inv)->update(array('status' => '1'));
                foreach ($update as $d) {
                    $produk_id = $d->produk_id;
                    $jumlah = $d->quantity;
                    $capital_price = $d->unit_satuan_price;
                    $id_cabang = $d->id_cabang;
                    $id_gudang = $d->id_gudang;
                    $id_suplier = $d->id_suplier;
                    $cek = Stok::where('produk_id',$produk_id)->where('capital_price',$capital_price)->where('id_cabang',$id_cabang)->where('id_gudang',$id_gudang)->first();
                    if($cek){
                        if($cek->capital_price <> $capital_price){
                            $stok = new Stok;
                            $stok->produk_id = $produk_id;
                            $stok->jumlah = $jumlah;
                            $stok->id_cabang = $id_cabang;
                            $stok->capital_price = $capital_price;
                            $stok->id_gudang = $id_gudang;
                            $stok->id_suplier = $id_suplier;
                            $stok->save();
                        }else{
                            $edit = Stok::where('produk_id',$produk_id)->where('capital_price',$capital_price)->where('id_cabang',$id_cabang)->where('id_gudang',$id_gudang)
                                ->increment('jumlah',$jumlah);
                            }
                    }else{
                        $stok = new Stok;
                        $stok->produk_id = $produk_id;
                        $stok->jumlah = $jumlah;
                        $stok->id_cabang = $id_cabang;
                        $stok->capital_price = $capital_price;
                        $stok->id_gudang = $id_gudang;
                        $stok->id_suplier = $id_suplier;
                        $stok->save();
                    }
            } 
        return response()->json(['message'=>'Data Di Approval','status'=>200]);
       }
       else{       
        $datapurchase->status = '2';
        $datapurchase->save();
        $updatedetail = TransaksiPurchaseDetail::where('invoice_id',$inv)->update(array('status' => '2'));
           return response()->json(['message'=>'Data Tidak Di Approval','status'=>402]);
       }

    }


    // edit_transaksi
    public function edit_transaksi($id_transaksi_purchase){
        $data1 = DB::table('transaksi_purchase')->where('id_transaksi_purchase',$id_transaksi_purchase)
        ->join('tbl_suplier as suplier','suplier.id_suplier','=','transaksi_purchase.id_suplier')
        ->select('invoice_id','total','diskon','bayar','sisa','invoice_date','transaksi_tipe','suplier.id_suplier as id_suplier','id_gudang','nama_suplier','id_transaksi_purchase')
        ->first();
        $id_cabang = session()->get('cabang');
        $gudang = DB::table('tbl_gudang')->where('id_cabang',$id_cabang)->get();
        return view('pages.transaksi.purchasetransaksi.edit_index',compact(['gudang','data1']));
    }

    public function edit_all_data($id_transaksi_purchase){
        $data_tmp = DB::table('transaksi_purchase')->where('id_transaksi_purchase',$id_transaksi_purchase)->first();
        $invoice_id = $data_tmp->invoice_id;
        $data = DB::table('transaksi_purchase_detail as tmp')
            ->where('invoice_id',$invoice_id)
            ->join('tbl_produk as a','a.produk_id','=','tmp.produk_id')
            ->join('tbl_type_produk as b','b.id_type_produk','=','a.id_type_produk')
            ->join('tbl_suplier as c','c.id_suplier','=','tmp.id_suplier')
            ->select('id_transaksi_purchase_detail','invoice_id','invoice_date','transaksi_tipe','tmp.produk_id as produk_id','nama_type_produk','produk_brand','produk_nama','unit_satuan_price','quantity','diskon','total_price','nama_suplier','term_until')
            ->get();
            return $data;
    }

    public function edit_datatable($id_transaksi_purchase){
        // untuk datatables Sistem Join Query Builder
        $data = $this->edit_all_data($id_transaksi_purchase);
        $format = '%d %s ';
        $stok = [];
        $this->datatable = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->quantity;
            $harga = $d->unit_satuan_price;
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
            $d->total = $harga * $d->quantity;
            $this->datatable[] = ["produk_id"=>$d->produk_id,"nama_type_produk"=>$d->nama_type_produk,"produk_brand"=>$d->produk_brand,"produk_nama"=>$d->produk_nama,"unit_satuan_price"=>$d->unit_satuan_price,"stok_quantity"=>$d->stok_quantity,"diskon"=>$d->diskon,"total_price"=>$d->total_price,"total"=>$d->total,"id_transaksi_purchase_detail"=>$d->id_transaksi_purchase_detail,"invoice_id"=>$d->invoice_id,"invoice_date"=>$d->invoice_date,"transaksi_tipe"=>$d->transaksi_tipe];   
        }
       
        return datatables()->of($this->datatable)->toJson();
    }

    public function remove(Request $request, $id){
        try{
            $data = TransaksiPurchaseDetail::findOrFail($id);
            $data->delete();
            return response()->json(['message'=>'Data Berhasil Di Hapus','status'=>200]);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }

    public function add(Request $request)
    {
        // id_brand belum final
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            return response()->json(['id' => TransaksiPurchaseDetail::create($request->all())->id_transaksi_purchase_tmp, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function calculateDetail($id_transaksi_purchase){
        $data = DB::table('transaksi_purchase')->where('id_transaksi_purchase',$id_transaksi_purchase)->select('invoice_id')->first();
        $inv = $data->invoice_id;
        $tot_price = DB::table('transaksi_purchase_detail')->where('invoice_id',$inv)->sum('total_price');
        return response()->json(['tot'=>$tot_price]);
    }

    public function register($tot,$dis,$down,$deb,$id_transaksi_purchase){
        
            $tambahutama = TransaksiPurchase::find($id_transaksi_purchase);
            $tambahutama->total = $tot;
            $tambahutama->diskon = $dis;
            $tambahutama->bayar = $down;
            $tambahutama->sisa = $deb;
            $tambahutama->save();    
              
    }

}
