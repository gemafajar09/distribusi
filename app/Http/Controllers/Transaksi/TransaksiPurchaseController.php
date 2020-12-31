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
class TransaksiPurchaseController extends Controller
{
   public function index(){
      $id_cabang = session()->get('cabang');
      $gudang = DB::table('tbl_gudang')->where('id_cabang',$id_cabang)->get();
    return view('pages.transaksi.purchasetransaksi.index',compact('gudang'));
   }

   // edit faktur
   public function view_edit_purchase(){
      return view('pages.transaksi.purchasetransaksi.edit');
  }
  
  public function totalpembelian($id_cabang,$bulan){
     $tahun = date('Y');
     $data = TransaksiPurchase::where('id_cabang',$id_cabang)->whereMonth('invoice_date',$bulan)->whereYear('invoice_date',$tahun)->where('status','1')->sum('total');
     $jumlah = "Rp. " . number_format($data,2,',','.');
     return response()->json(['data'=>$jumlah]);
  }
   
}
