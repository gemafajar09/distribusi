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
class TransaksiPurchaseTmpController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_transaksi_purchase_tmp'=>'numeric',
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
    }
    
    public function datatable($id_cabang){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder($id_cabang);
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
            $d->total = number_format($harga * $d->quantity,2,'.','');
            $this->dataisi[] = ["produk_id"=>$d->produk_id,"nama_type_produk"=>$d->nama_type_produk,"produk_brand"=>$d->produk_brand,"produk_nama"=>$d->produk_nama,"unit_satuan_price"=>number_format($d->unit_satuan_price,2,'.',''),"stok_quantity"=>$d->stok_quantity,"diskon"=>$d->diskon,"total_price"=>number_format($d->total_price,2,'.',''),"total"=>$d->total,"id_transaksi_purchase_tmp"=>$d->id_transaksi_purchase_tmp,"invoice_id"=>$d->invoice_id,"invoice_date"=>$d->invoice_date,"transaksi_tipe"=>$d->transaksi_tipe,"nama_suplier"=>$d->nama_suplier,"term_until"=>$d->term_until];   
        }
       
        return datatables()->of($this->dataisi)->toJson();
    }

    public function join_builder($id_cabang){
        $data = DB::table('transaksi_purchase_tmp as tmp')
            ->where('tmp.id_cabang',$id_cabang)
            ->join('tbl_produk as a','a.produk_id','=','tmp.produk_id')
            ->join('tbl_type_produk as b','b.id_type_produk','=','a.id_type_produk')
            ->join('tbl_suplier as c','c.id_suplier','=','tmp.id_suplier')
            ->select('id_transaksi_purchase_tmp','invoice_id','invoice_date','transaksi_tipe','tmp.produk_id as produk_id','nama_type_produk','produk_brand','produk_nama','unit_satuan_price','quantity','diskon','total_price','nama_suplier','term_until')
            ->get();
            return $data;
    }

    public function get(Request $request,$id=null)
    {
        try{
            if($id){
                $data = TransaksiPurchaseTmp::findOrFail($id);
            }else{
                $data = TransaksiPurchaseTmp::all();
            }
            return response()->json(['data'=>$data,'status'=>200]);
        }catch(ModelNotFoundException $e){
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
            return response()->json(['id' => TransaksiPurchaseTmp::create($request->all())->id_transaksi_purchase_tmp, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    } 

    public function remove(Request $request, $id){
        try{
            $data = TransaksiPurchaseTmp::findOrFail($id);
            $data->delete();
            return response()->json(['message'=>'Data Berhasil Di Hapus','status'=>200]);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }


    public function register($tot,$dis,$down,$deb,$id_cabang){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data = TransaksiPurchaseTmp::where('id_cabang',$id_cabang)->select('invoice_id','invoice_date','transaksi_tipe','term_until','id_suplier','produk_id','quantity','unit_satuan_price','diskon','total_price','id_cabang','status','id_gudang')->get()->toArray();
        $data1 = $this->datatable($id_cabang);
        $datatmp =  $this->dataisi;
        $tambahtodetail = TransaksiPurchaseDetail::insert($data);
        $d = $data[0];
        // entry to purchase table
            $id_cabang = $d['id_cabang'];
            $invoice_id = $d['invoice_id'];
            $invoice_date = $d['invoice_date'];
            $id_suplier = $d['id_suplier'];
            $transaksi_tipe = $d['transaksi_tipe'];
            $id_gudang = $d['id_gudang'];
            $tambahutama = new TransaksiPurchase;
            $tambahutama->invoice_id = $invoice_id;
            $tambahutama->invoice_date = $invoice_date;
            $tambahutama->transaksi_tipe = $transaksi_tipe;
            $tambahutama->id_suplier = $id_suplier; 
            $tambahutama->id_cabang = $id_cabang;
            $tambahutama->total = $tot;
            $tambahutama->diskon = $dis;
            $tambahutama->bayar = $down;
            $tambahutama->sisa = $deb;
            $tambahutama->id_gudang = $id_gudang;
            $tambahutama->save();    
            $calculate = [$tot,$dis,$down,$deb];
        $delete = TransaksiPurchaseTmp::where('id_cabang',$id_cabang)->delete();
        if($delete){
            return view('report.purchase_transaksi',compact(['datatmp','calculate','data_cabang']));
        }    
    }

    public function generateInvoicePurchase(Request $request,$id){
        $cabang = $id;
        $tanggal = date('ymd');
        $codeinv = DB::table('transaksi_purchase')->where('id_cabang',$cabang)->orderBy('id_transaksi_purchase','desc')->first();
        if($codeinv == NULL){
            $inv= "PO-".$tanggal.'-'.$cabang.'-1';
        }else{
            $cekinv = substr($codeinv->invoice_id,12,50);
            $plus = (int)$cekinv + 1;
            $inv= "PO-".$tanggal.'-'.$cabang.'-'.$plus;
        }
        return response()->json(['invoice'=>$inv]);
        
    }

    public function calculateTmp($id_cabang){
        $tot_price = DB::table('transaksi_purchase_tmp')->where('id_cabang',$id_cabang)->sum('total_price');
        return response()->json(['tot'=>$tot_price]);
    }


}
