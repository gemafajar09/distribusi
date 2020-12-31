<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Stok;
use App\Models\BrokenExpMovement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class BrokenExpMovementController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_broken_exp' => '',
            'inv_broken_exp' => '',
            'id_gudang_dari' => 'required|',
            'id_gudang_tujuan' => 'required|',
            'stok_id' => 'required|numeric',
            'jumlah_broken' => 'required|numeric',
            'movement_date' => 'required|date',
            'note' => 'required|regex:/(^[A-Za-z0-9 -.,]+$)+/',
            'id_cabang' => 'required|numeric',
            'status_broken'=>''
        );
        $this->dataisi = [];
    }

    public function index()
    {
        $cabang = session()->get('cabang');
        $id = strlen(session()->get('id'));
        $inv = DB::table('tbl_broken_exp')->orderBy('id_broken_exp', 'desc')->first();
        if ($id == 1) {
            $user = '00' . session()->get('id');
        } else if ($id == 2) {
            $user = '0' . session()->get('id');
        } else if ($id == 3) {
            $user = session()->get('id');
        }
        if ($inv == NULL) {
            $invoice = 'MBS-' . date('Ym') . "-" . $user . '-1';
        } else {
            $cekinv = substr($inv->inv_broken_exp, 15, 50);
            $plus = (int)$cekinv + 1;
            $invoice = 'MBS-' . date('Ym') . "-" . $user . '-' . $plus;
        }
        $data['invoice'] = $invoice;
        return view("pages.transaksi.brokenexpmovement.index", $data);
    }


    public function ambildatastok(Request $r, $id = null)
    {
        // $cab = $r->cabang;
        // $id = $r->produk_id;
        $data =  DB::table('tbl_stok')
            ->select('*')
            ->join('tbl_produk', 'tbl_produk.produk_id', 'tbl_stok.produk_id')
            ->join('tbl_type_produk', 'tbl_produk.id_type_produk', 'tbl_type_produk.id_type_produk')
            ->where('tbl_stok.stok_id', $id)
            // ->where('tbl_stok.id_cabang', $cab)
            ->get();
        // dd($data);
        if ($data == TRUE) {
            return response()->json(['data' => $data, 'status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function cekdatastok(Request $r)
    {
        $id = $r->produk_id;
        $data = DB::table('tbl_unit')
            ->where('tbl_unit.produk_id', $id)
            ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
            ->select('tbl_unit.id_unit', 'tbl_satuan.nama_satuan as unit', 'tbl_unit.default_value')
            ->orderBy('tbl_unit.id_unit', 'ASC')
            ->get();
        if ($data == TRUE) {
            return response()->json(['data' => $data, 'status' => 200]);
        } else {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function datatablesbroken($id_cabang)
    {
        
        $data = DB::table('tbl_broken_exp as br')
                ->where('br.id_cabang',$id_cabang) 
                ->where('br.status_broken','0')
                ->join('tbl_stok as stk','stk.stok_id','=','br.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->join('tbl_type_produk as type','type.id_type_produk','=','prdk.id_type_produk')
                ->select('id_broken_exp','jumlah_broken','br.stok_id as stok_id','nama_type_produk','produk_brand','produk_nama','prdk.produk_id as produk_id','capital_price','id_gudang_dari','id_gudang_tujuan','id_suplier')
                ->get();
        
        $data = $this->conversi($data);
        return datatables()->of($this->dataisi)->toJson();
    }

    public function conversi($data){
        $format = '%d %s ';
        $stok = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah_broken;
            $harga = $d->capital_price;
            $stok_id = $d->stok_id;
            $produk_nama = $d->produk_nama;
            $produk_brand = $d->produk_brand;
            $nama_type_produk = $d->nama_type_produk;
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
            $d->total = $harga * $d->jumlah_broken;
            $this->dataisi[] = [
                "stok_id" => $stok_id,
                "nama_type" => $nama_type_produk,
                "produk_brand" => $produk_brand,
                "produk_nama" => $produk_nama,
                "jumlah_broken"=>$d->stok_quantity,
                "total"=>$d->total,
                "id_broken_exp"=>$d->id_broken_exp,
                "produk_id"=>$d->produk_id,
                "id_gudang_dari"=>$d->id_gudang_dari,
                "id_gudang_tujuan"=>$d->id_gudang_tujuan,
                "quantity"=>$d->jumlah_broken,
                "capital_price"=>$d->capital_price,
                "id_suplier"=>$d->id_suplier
            ];
        }
        return $this->dataisi;
    }

    public function add(Request $request){
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            return response()->json(['id' => BrokenExpMovement::create($request->all())->id_broken_exp, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = BrokenExpMovement::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function register($id_cabang){
        $data_cabang = DB::table('tbl_cabang')->where('id_cabang',$id_cabang)->first();
        $data = DB::table('tbl_broken_exp as br')
                ->where('br.id_cabang',$id_cabang)
                ->where('status_broken','0')
                ->join('tbl_stok as stk','stk.stok_id','=','br.stok_id')
                ->join('tbl_produk as prdk','prdk.produk_id','=','stk.produk_id')
                ->join('tbl_type_produk as type','type.id_type_produk','=','prdk.id_type_produk')
                ->select('id_broken_exp','jumlah_broken','br.stok_id as stok_id','nama_type_produk','produk_brand','produk_nama','prdk.produk_id as produk_id','capital_price','id_gudang_dari','id_gudang_tujuan','id_suplier')
                ->get();
        $update = DB::table('tbl_broken_exp')->where('id_cabang',$id_cabang)->update(array('status_broken' => '1'));
        $dataconversi = $this->conversi($data);
        foreach ($dataconversi as $d) {
            $stok_id = $d['stok_id'];
            $jumlah = $d['quantity'];
            $id_gudang_tujuan = $d['id_gudang_tujuan'];
            $cek = Stok::where('produk_id',$d['produk_id'])->where('id_gudang',$id_gudang_tujuan)->first();
                if(empty($cek)){
                    $new = new Stok;
                    $new->id_suplier = $d['id_suplier'];
                    $new->produk_id =  $d['produk_id'];
                    $new->jumlah =  $jumlah;
                    $new->capital_price = $d['capital_price'];
                    $new->id_cabang = $id_cabang;
                    $new->id_gudang = $id_gudang_tujuan;
                    $new->save();
                }else{
                    $stok = Stok::where('produk_id',$d['produk_id'])->where('id_gudang',$id_gudang_tujuan)->increment('jumlah', $jumlah);
                }
            $data = Stok::find($stok_id);
            $data->decrement('jumlah',$jumlah);
            $data->save();
                

        }
        return view('report.brokenexp',compact(['dataconversi','data_cabang']));
    }
}
