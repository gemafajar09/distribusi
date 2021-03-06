<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
class ProdukReport extends Controller
{
    public function __construct()
    {
        $this->dataisi = [];
    }

    public function index(){
        return view('report.produk.index');
    }
    

    public function all_datatable($id_cabang){
        $data = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->get();
        $data1 = $this->conversi($data);
        return datatables()->of($data1)->toJson();
    }

    public function today_datatable($id_cabang){
        $data = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereRaw('Date(invoice_date) = CURDATE()')
                ->get();
        $data1 = $this->conversi($data);
        return datatables()->of($data1)->toJson();
    }

    public function month_datatable($month,$year,$id_cabang){
        $data = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereMonth('invoice_date',$month)->whereYear('invoice_date',$year)->get();
        $data1 = $this->conversi($data);
        return datatables()->of($data1)->toJson();
    }
    public function year_datatable($year,$id_cabang){
        $data = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereYear('invoice_date',$year)->get();
        $data1 = $this->conversi($data);
        return datatables()->of($data1)->toJson();
    }
    public function range_datatable($from,$to,$id_cabang){
        $data = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereBetween('invoice_date',[$from, $to])->get();
        $data1 = $this->conversi($data);
        return datatables()->of($data1)->toJson();
    }

    // public function report_all($id_cabang){
    //     $data = DB::table('transaksi_purchase_return')->where('transaksi_purchase_return.id_cabang',$id_cabang)->join('tbl_suplier','tbl_suplier.id_suplier','=','transaksi_purchase_return.id_suplier')->get();
    //     return view('report.purchasereturn.reportpurchasereturn',compact('data'));
    // }

    // public function report_today($id_cabang){
    //     $data = DB::table('transaksi_purchase_return')->where('transaksi_purchase_return.id_cabang',$id_cabang)->join('tbl_suplier','tbl_suplier.id_suplier','=','transaksi_purchase_return.id_suplier')
    //     ->select('return_id','return_date','nama_suplier',DB::raw('sum(price) as price'))->groupBy('return_id','return_date','nama_suplier')
    //     ->whereRaw('Date(return_date) = CURDATE()')->get();;
    //     return view('report.purchasereturn.reportpurchasereturn',compact('data'));
    // }
    // public function report_month($month,$year,$id_cabang){
    //     $data = DB::table('transaksi_purchase_return')->where('transaksi_purchase_return.id_cabang',$id_cabang)->join('tbl_suplier','tbl_suplier.id_suplier','=','transaksi_purchase_return.id_suplier')
    //     ->select('return_id','return_date','nama_suplier',DB::raw('sum(price) as price'))->groupBy('return_id','return_date','nama_suplier')
    //     ->whereMonth('return_date',$month)->whereYear('return_date',$year)->get();
    //     return view('report.purchasereturn.reportpurchasereturn',compact('data'));
    // }

    // public function report_year($year,$id_cabang){
    //     $data = DB::table('transaksi_purchase_return')->where('transaksi_purchase_return.id_cabang',$id_cabang)->join('tbl_suplier','tbl_suplier.id_suplier','=','transaksi_purchase_return.id_suplier')
    //     ->select('return_id','return_date','nama_suplier',DB::raw('sum(price) as price'))->groupBy('return_id','return_date','nama_suplier')
    //     ->whereYear('return_date',$year)->get();
    //     return view('report.purchasereturn.reportpurchasereturn',compact('data'));
    // }
    // public function report_range($from,$to,$id_cabang){
    //     $data = DB::table('transaksi_purchase_return')->where('transaksi_purchase_return.id_cabang',$id_cabang)->join('tbl_suplier','tbl_suplier.id_suplier','=','transaksi_purchase_return.id_suplier')
    //     ->select('return_id','return_date','nama_suplier',DB::raw('sum(price) as price'))->groupBy('return_id','return_date','nama_suplier')
    //     ->whereBetween('return_date',[$from, $to])->get();
    //     return view('report.purchasereturn.reportpurchasereturn',compact('data'));
    // }

    // // untuk detail report belum sempat ringkas
    // public function datatable_detail_report(Request $request){
    //     // untuk datatables Sistem Join Query Builder
    //     $return_id = $request->input('return_id');
    //     $id_cabang = $request->input('id_cabang');
    //     $data = $this->join_builder_detail($id_cabang,$return_id);
    //     $format = '%d %s ';
    //     $stok = [];
    //     $this->datadetail = [];
    //     foreach ($data as $d) {
    //         $id = $d->produk_id;
    //         $jumlah = $d->jumlah_return;
    //         $proses = DB::table('tbl_unit')->where('produk_id',$id)
    //         ->join('tbl_satuan','tbl_unit.maximum_unit_name','=','tbl_satuan.id_satuan')
    //         ->select('id_unit','nama_satuan as unit','default_value')
    //         ->orderBy('id_unit','ASC')
    //         ->get();
    //         $hasilbagi=0;
    //         foreach ($proses as $index => $list) {
    //             $banyak = sizeof($proses);
    //             if($index == 0 ){
    //             $sisa = $jumlah % $list->default_value;
    //             $hasilbagi = ($jumlah-$sisa)/$list->default_value;
    //             $satuan[$index] = $list->unit;
    //             $lebih[$index] = $sisa;
                
    //             if ($sisa > 0){
    //                 $stok[$index] = sprintf($format, $sisa, $list->unit);
    //             }
    //              if($banyak == $index+1){
    //                 $satuan = array();
    //                 $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
    //                 $stokquantity = array_values($stok);
    //                 $stok = array();
    //             }
    //             }else if($index == 1){
    //                 $sisa = $hasilbagi % $list->default_value;
    //                 $hasilbagi = ($hasilbagi-$sisa)/$list->default_value;
    //                 $satuan[$index] = $list->unit;
    //                 $lebih[$index] = $sisa;
                    
    //                 if($sisa > 0){
    //                     $stok[$index-1] = sprintf($format, $sisa+$lebih[$index-1], $satuan[$index-1]);
    //                 }
    //                 if($banyak == $index+1){
    //                     $satuan = array();
    //                     $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
    //                     $stokquantity = array_values($stok);
    //                     $stok = array();
    //                 }
    //             }else if($index == 2){
    //                 $sisa = $hasilbagi % $list->default_value;
    //                 $hasilbagi = ($hasilbagi-$sisa)/$list->default_value;
    //                 $satuan[$index] = $list->unit;
    //                 $lebih[$index] = $sisa;
                    
    //                 if($sisa > 0){
    //                     $stok[$index-1] = sprintf($format, $sisa,  $satuan[$index-1]);
    //                 }
    //                 if($banyak == $index+1){
    //                     $satuan = array();
    //                     $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
    //                     $stokquantity = array_values($stok);
    //                     $stok = array();
    //                 }
    //             }    
    //         }
    //         $jumlah_stok = implode(" ",$stokquantity);
    //         $d->stok_quantity = $jumlah_stok;
    //         $this->datadetail[] = ["produk_id"=>$d->produk_id,"nama_type_produk"=>$d->nama_type_produk,"produk_brand"=>$d->produk_brand,"produk_nama"=>$d->produk_nama,"total_price"=>$d->price,"stok_quantity"=>$d->stok_quantity];   
    //     }
       
    //     return response()->json(['data'=>$this->datadetail]);
    // }

    // public function join_builder_detail($cabang,$id){
    //     $status='1';
    //     $data = DB::table('transaksi_purchase_return as tmp')
    //         ->where('register',$status)
    //         ->where('tmp.id_cabang',$cabang)
    //         ->where('return_id',$id)
    //         ->join('tbl_stok as stk','stk.stok_id','=','tmp.stok_id')
    //         ->join('tbl_produk as a','a.produk_id','=','stk.produk_id')
    //         ->join('tbl_type_produk as b','b.id_type_produk','=','a.id_type_produk')
    //         ->select('id_transaksi_purchase_return','return_id','return_date','stk.produk_id as produk_id','nama_type_produk','produk_brand','produk_nama','jumlah_return','price','status')
    //         ->get();
    //         return $data;
    // }

    public function report_all_spesifik($id_cabang,$produk_id){
        $produk = explode(',',$produk_id);
        $data1 = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->whereIn('prdk.produk_id',$produk)
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->get();
        $data = $this->conversi($data1);
        return view('report.produk.reportproduk',compact('data'));
    }

    public function report_today_spesifik($id_cabang,$produk_id){
        $produk = explode(',',$produk_id);
        $data1 = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->whereIn('prdk.produk_id',$produk)
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereRaw('Date(invoice_date) = CURDATE()')
                ->get();
        $data = $this->conversi($data1);
        return view('report.produk.reportproduk',compact('data'));
    }

    public function report_month_spesifik($month,$year,$id_cabang,$produk_id){
        $produk = explode(',',$produk_id);
        $data1 = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->whereIn('prdk.produk_id',$produk)
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereMonth('invoice_date',$month)->whereYear('invoice_date',$year)->get();
        $data = $this->conversi($data1);
        return view('report.produk.reportproduk',compact('data'));
    }

    public function report_year_spesifik($year,$id_cabang,$produk_id){
        $produk = explode(',',$produk_id);
        $data1 = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->whereIn('prdk.produk_id',$produk)
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereYear('invoice_date',$year)->get();
        $data = $this->conversi($data1);
        return view('report.produk.reportproduk',compact('data'));
    }

    public function report_range_spesifik($from,$to,$id_cabang,$produk_id){
        $produk = explode(',',$produk_id);
        $data1 = DB::table('tbl_stok as stk')
                ->where('id_cabang',$id_cabang)
                ->join('transaksi_sales_details as tsd','tsd.stok_id','=','stk.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->whereIn('prdk.produk_id',$produk)
                ->select('produk_brand','prdk.produk_id as produk_id','produk_nama',DB::raw('sum(quantity) as quantity'))->groupBy('produk_id','produk_brand','produk_nama')
                ->whereBetween('invoice_date',[$from, $to])->get();
        $data = $this->conversi($data1);
        return view('report.produk.reportproduk',compact('data'));
    }

    public function conversi($data){
        $format = '%d %s ';
        $stok = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah= $d->quantity;
            $produk_nama = $d->produk_nama;
            $produk_brand = $d->produk_brand;
            // untuk mencari nilai unitnya, karton, bungkus, pieces
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();
            $hasilbagi = 0;
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
            $jumlah_terjual = implode(" ",$stokquantity);
            $this->dataisi[] = [
                "produk_id"=>$id,"quantity"=>$jumlah_terjual,"produk_nama"=>$produk_nama,"produk_brand"=>$produk_brand
            ];
        }
        return $this->dataisi;
    }
}
