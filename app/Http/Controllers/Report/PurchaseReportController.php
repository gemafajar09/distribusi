<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransaksiPurchase;
use Illuminate\Support\Facades\DB;
class PurchaseReportController extends Controller
{
    public function __construct()
    {
        $this->datadetail = [];
    }
    public function index(){
        return view('report.purchase.index');
    }
    public function conversi($data){
        $dataisi = [];
        foreach ($data as $d) {
            if($d->transaksi_tipe == 0){
                $tipe = "CASH";
            }else{
                $tipe = "CREDIT";
            }
            if($d->sisa == 0){
                $status = "Lunas";
            }else{
                $status = "Belum Lunas";
            }
            $dataisi[] = ["invoice_id"=>$d->invoice_id,"invoice_date"=>$d->invoice_date,"transaksi_tipe"=>$tipe,"total"=>$d->total,"diskon"=>$d->diskon,"bayar"=>$d->bayar,"sisa"=>$d->sisa,"status"=>$status,"id_transaksi_purchase"=>$d->id_transaksi_purchase]; 
        }
        return $dataisi;
    }

    public function all_datatable($status,$id_cabang){
        $data = TransaksiPurchase::where('status',$status)->where('id_cabang',$id_cabang)->get();
        $dataisi = $this->conversi($data);
        return datatables()->of($dataisi)->toJson();
    }

    public function today_datatable($status,$id_cabang){
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereRaw('Date(invoice_date) = CURDATE()')->get();;
        $dataisi = $this->conversi($data);
        return datatables()->of($dataisi)->toJson();
    }

    public function month_datatable($month,$year,$status,$id_cabang){
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereMonth('invoice_date',$month)->whereYear('invoice_date',$year)->get();
        $dataisi = $this->conversi($data);
        return datatables()->of($dataisi)->toJson();
    }
    public function year_datatable($year,$status,$id_cabang){
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereYear('invoice_date',$year)->get();
        $dataisi = $this->conversi($data);
        return datatables()->of($dataisi)->toJson();
    }
    public function range_datatable($from,$to,$status,$id_cabang){
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereBetween('invoice_date',[$from, $to])->get();
        $dataisi = $this->conversi($data);
        return datatables()->of($dataisi)->toJson();
    }

    public function report_all($status,$id_cabang){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data = TransaksiPurchase::where('status',$status)->where('id_cabang',$id_cabang)->get();
        $dataisi = $this->conversi($data);
        return view('report.purchase.reportpurchase',compact(['dataisi','data_cabang']));
    }

    public function report_today($status,$id_cabang){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereRaw('Date(invoice_date) = CURDATE()')->get();;
        $dataisi = $this->conversi($data);
        return view('report.purchase.reportpurchase',compact(['dataisi','data_cabang']));
    }
    public function report_month($month,$year,$status,$id_cabang){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereMonth('invoice_date',$month)->whereYear('invoice_date',$year)->get();
        $dataisi = $this->conversi($data);
        return view('report.purchase.reportpurchase',compact(['dataisi','data_cabang']));
    }

    public function report_year($year,$status,$id_cabang){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereYear('invoice_date',$year)->get();
        $dataisi = $this->conversi($data);
        return view('report.purchase.reportpurchase',compact(['dataisi','data_cabang']));
    }
    public function report_range($from,$to,$status,$id_cabang){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data = DB::table('transaksi_purchase')->where('status',$status)->where('id_cabang',$id_cabang)->select(DB::raw('*'))
        ->whereBetween('invoice_date',[$from, $to])->get();
        $dataisi = $this->conversi($data);
        return view('report.purchase.reportpurchase',compact(['dataisi','data_cabang']));
    }

    // untuk detail report belum sempat ringkas
    public function datatable_detail_report($cabang,$invoice_id){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder_detail($cabang,$invoice_id);
        $format = '%d %s ';
        $stok = [];
        $this->datadetail = [];
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
            $this->datadetail[] = ["produk_id"=>$d->produk_id,"nama_type_produk"=>$d->nama_type_produk,"produk_brand"=>$d->produk_brand,"produk_nama"=>$d->produk_nama,"unit_satuan_price"=>$d->unit_satuan_price,"stok_quantity"=>$d->stok_quantity,"diskon"=>$d->diskon,"total_price"=>$d->total_price,"total"=>$d->total,"id_transaksi_purchase_detail"=>$d->id_transaksi_purchase_detail,"invoice_id"=>$d->invoice_id,"invoice_date"=>$d->invoice_date,"transaksi_tipe"=>$d->transaksi_tipe];   
        }
       
        return response()->json(['data'=>$this->datadetail]);
    }

    public function join_builder_detail($cabang,$id){
        $data = TransaksiPurchase::find($id);
        $inv = $data->invoice_id;
        $data = DB::table('transaksi_purchase_detail as tmp')
            ->where('id_cabang',$cabang)
            ->where('invoice_id',$inv)
            ->join('tbl_produk as a','a.produk_id','=','tmp.produk_id')
            ->join('tbl_type_produk as b','b.id_type_produk','=','a.id_type_produk')
            ->select('id_transaksi_purchase_detail','invoice_id','invoice_date','transaksi_tipe','tmp.produk_id as produk_id','nama_type_produk','produk_brand','produk_nama','unit_satuan_price','quantity','diskon','total_price','status')
            ->get();
            return $data;
    }

    // edit transaksi
    public function edit_today_datatable($id_cabang){
        $data = DB::table('transaksi_purchase')->where('id_cabang',$id_cabang)->where('status','0')->select(DB::raw('*'))
        ->whereRaw('Date(invoice_date) = CURDATE()')->get();;
        $dataisi = $this->conversi($data);
        return datatables()->of($dataisi)->toJson();
    }
    
    // untuk detail report belum sempat ringkas
    public function datatable_edit_detail_report($cabang,$invoice_id){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder_edit_detail($cabang,$invoice_id);
        $format = '%d %s ';
        $stok = [];
        $this->datadetail = [];
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
            $this->datadetail[] = ["produk_id"=>$d->produk_id,"nama_type_produk"=>$d->nama_type_produk,"produk_brand"=>$d->produk_brand,"produk_nama"=>$d->produk_nama,"unit_satuan_price"=>$d->unit_satuan_price,"stok_quantity"=>$d->stok_quantity,"diskon"=>$d->diskon,"total_price"=>$d->total_price,"total"=>$d->total,"id_transaksi_purchase_detail"=>$d->id_transaksi_purchase_detail,"invoice_id"=>$d->invoice_id,"invoice_date"=>$d->invoice_date,"transaksi_tipe"=>$d->transaksi_tipe];   
        }
       
        return response()->json(['data'=>$this->datadetail]);
    }

    public function join_builder_edit_detail($cabang,$id){
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

    public function report_edit_today($id_transaksi_purchase){
        $data1 = DB::table('transaksi_purchase')->where('id_transaksi_purchase',$id_transaksi_purchase)->select('invoice_id','total','diskon','bayar','sisa','id_cabang')
        ->first();
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$data1->id_cabang)->first();
        $inv_id = $data1->invoice_id;
        $calculate = [$data1->total,$data1->diskon,$data1->bayar,$data1->sisa];
        $data = DB::table('transaksi_purchase_detail as tmp')
            ->where('invoice_id',$inv_id)
            ->join('tbl_produk as a','a.produk_id','=','tmp.produk_id')
            ->join('tbl_type_produk as b','b.id_type_produk','=','a.id_type_produk')
            ->join('tbl_suplier as c','c.id_suplier','=','tmp.id_suplier')
            ->select('id_transaksi_purchase_detail','invoice_id','invoice_date','transaksi_tipe','tmp.produk_id as produk_id','nama_type_produk','produk_brand','produk_nama','unit_satuan_price','quantity','diskon','total_price','nama_suplier','term_until')
            ->get();
        $datatmp = $this->data_print_edit($data);
        return view('report.purchase_transaksi',compact(['datatmp','calculate','data_cabang']));
    }

    public function data_print_edit($data){
        // untuk datatables Sistem Join Query Builder
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
            $this->dataisi[] = ["produk_id"=>$d->produk_id,"nama_type_produk"=>$d->nama_type_produk,"produk_brand"=>$d->produk_brand,"produk_nama"=>$d->produk_nama,"unit_satuan_price"=>number_format($d->unit_satuan_price,2,'.',''),"stok_quantity"=>$d->stok_quantity,"diskon"=>$d->diskon,"total_price"=>number_format($d->total_price,2,'.',''),"total"=>$d->total,"id_transaksi_purchase_detail"=>$d->id_transaksi_purchase_detail,"invoice_id"=>$d->invoice_id,"invoice_date"=>$d->invoice_date,"transaksi_tipe"=>$d->transaksi_tipe,"nama_suplier"=>$d->nama_suplier,"term_until"=>$d->term_until];   
        }
       
        return $this->dataisi;
    }
    
    
}
