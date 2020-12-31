<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Stok;
use App\Models\TransaksiPurchase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class StokReportController extends Controller
{
    public function __construct()
    {
        $dataisi = [];
    }
    public function index(){
        return view('report.stok.index');
    }

    

    public function report($id_cabang,$id_warehouse=null){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        if($id_warehouse == null){
            $tmp = $this->datatable($id_cabang);
            $data = $this->dataisi;
            return view('report.stok.reportstok',compact(['data','data_cabang']));
        }else{
            $tmp = $this->datatable($id_cabang,$id_warehouse);
            $data = $this->dataisi;
            return view('report.stok.reportstok',compact(['data','data_cabang']));
        }
        
    }

    public function datatable($id_cabang,$id_warehouse=null){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder($id_cabang,$id_warehouse);
        $format = '%d %s ';
        $stok = [];
        $this->dataisi = [];
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
            $this->dataisi[] = ["id_unit"=>$id,"produk_nama"=>$d->produk_nama,"capital_price"=>$capital_price,"stok_harga"=>$d->total_harga,"jumlah"=>$d->stok_quantity,"produk_harga"=>$d->produk_harga,"stok_id"=>$d->stok_id,"nama_cabang"=>$d->nama_gudang];
        }
       
        return datatables()->of($this->dataisi)->toJson();
        
    }
    public function join_builder($id_cabang,$id_warehouse=null){
        // tempat join hanya menselect beberapa field tambah join brand
        if($id_warehouse == null){
            $data = DB::table('tbl_stok')
            ->where('tbl_stok.id_cabang',$id_cabang)
            ->join('tbl_produk','tbl_stok.produk_id','=','tbl_produk.produk_id')
            ->leftjoin('tbl_gudang as c','c.id_gudang','=','tbl_stok.id_gudang')
            ->get();
            return $data;
        }else{
            $data = DB::table('tbl_stok')
            ->where('tbl_stok.id_gudang',$id_warehouse)
            ->join('tbl_produk','tbl_stok.produk_id','=','tbl_produk.produk_id')
            ->leftjoin('tbl_gudang as c','c.id_gudang','=','tbl_stok.id_gudang')
            ->get();
            return $data;
        }
        
    }
    
    public function rekap_transaksi($id_cabang,$waktu){
        $data['bulan'] = $waktu;
        $data['tahun'] = date('Y');
        $data['data_cabang'] = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data['total_pembelian'] = $this->totalpembelian($id_cabang,$waktu);
        $data['total_penjualan'] = $this->totalpenjualan($id_cabang,$waktu);
        $data['stok_price'] = $this->stokprice($id_cabang);
        $data['profit'] = $this->profit($id_cabang,$waktu);

        return view('report.rekaptransaksi',$data);
        
    }

    public function totalpembelian($id_cabang,$bulan){
        $tahun = date('Y');
        $data = TransaksiPurchase::where('id_cabang',$id_cabang)->whereMonth('invoice_date',$bulan)->whereYear('invoice_date',$tahun)->where('status','1')->sum('total');
        $jumlah = "Rp. " . number_format($data,2,',','.');
        return $jumlah;
     }

     public function totalpenjualan($id_cabang,$bulan){
        $tahun = date('Y');
        $data = DB::table('transaksi_sales')->join('transaksi_sales_details as d','d.invoice_id','=','transaksi_sales.invoice_id')->join('tbl_user','tbl_user.id_user','=','transaksi_sales.id_user')->where('tbl_user.id_cabang',$id_cabang)->whereMonth('transaksi_sales.invoice_date',$bulan)->whereYear('transaksi_sales.invoice_date',$tahun)->where('approve',1)->select(DB::raw('SUM(totalsales-transaksi_sales.diskon) as total_sales'))->get();
        $jumlah = "Rp. " . number_format($data[0]->total_sales,2,',','.');
        return $jumlah;
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

        return "Rp. " . number_format($total,2,',','.');
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
        return "Rp. " . number_format($data_transaksi_sales[0]->total_sales - $total_modal,2,',','.');
    }
}