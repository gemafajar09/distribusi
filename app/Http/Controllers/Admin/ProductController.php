<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'produk_id'=>'required',
            'id_type_produk'=>'required|numeric',
            'produk_brand'=>'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'produk_nama'=>'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'produk_harga'=>'required|numeric',
            'stok'=>'required|numeric',
            'harga_modal'=>'required|numeric',
            
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed'
                   
        );
    }

    public function datatable(){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder();
        return datatables()->of($data)->toJson();
        
       
    }
    
    public function join_builder($id=null){
        // tempat join hanya menselect beberapa field tambahkan master brand
        $data = DB::table('tbl_produk')
                ->join('tbl_type_produk','tbl_type_produk.id_type_produk','=','tbl_produk.id_type_produk')
                ->get();
        return $data;
    }

    public function index(){
        $tanggal = date('ym');
        $codeinv = DB::table('tbl_produk')->orderBy('produk_id','desc')->first();
                if($codeinv == NULL){
                    $inv= "P".$tanggal."00001";
                }else{
                    $cekinv = substr($codeinv->produk_id,5,10);
                    $plus = (int)$cekinv + 1;
                    $index = (int)$cekinv;
                    if($index < 9){
                        $inv = "P".$tanggal."0000".$plus;
                    }
                    else if($index >= 9 && $index < 99){
                        $inv = "P".$tanggal."000".$plus;
                    }else if($index >= 99 && $index < 999){
                        $inv = "P".$tanggal."00".$plus;
                    }else if($index >= 999 && $index < 9999){
                        $inv = "P".$tanggal."0".$plus;
                    }
                    else if($index >= 9999){
                        $inv = "P".$tanggal."".$plus;
                    }
                }
        return view('pages.admin.produk.index',compact('inv'));
    }

    public function get(Request $request,$id=null)
    {
        try{
            if($id){
                $data = Produk::findOrFail($id);
            }else{
                $data = Produk::all();
            }
            return response()->json(['data'=>$data,'status'=>200]);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }

    public function add(Request $request){
        // buat kode produk
        
        // id_brand belum final
        $validator = Validator::make($request->all(),$this->rules,$this->messages);
        if($validator->fails()){
            return response()->json(['messageForm'=>$validator->errors(),'status'=>422,'message'=>'Data Tidak Valid']);
        }else{
            $data = Produk::insert($request->all());
            return response()->json(['message'=>'Data Berhasil Ditambahkan','status'=>200]);
        }
    }


    public function edit(Request $request){
        $id = $request->input('produk_id');
        try{
            $edit = Produk::findOrFail($id);
            $validator = Validator::make($request->all(),$this->rules,$this->messages);
            if($validator->fails()){
                return response()->json(['messageForm'=>$validator->errors(),'status'=>422,'message'=>'Data Tidak Valid']);
            }else{
                $edit->id_type_produk = $request->input('id_type_produk');
                $edit->produk_brand = $request->input('produk_brand');
                $edit->produk_nama = $request->input('produk_nama');
                $edit->produk_harga = $request->input('produk_harga');
                $edit->stok = $request->input('stok');
                $edit->harga_modal = $request->input('harga_modal');
                
                $edit->save();
                return response()->json(['message'=>'Data Berhasil Di Edit','data'=>$edit,'status'=>200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }

    }

    public function remove(Request $request, $id){
        try{
            $data = Produk::findOrFail($id);
            $data->delete();
            return response()->json(['message'=>'Data Berhasil Di Hapus','status'=>200]);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }



    

    
}
