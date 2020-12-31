<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_unit'=>'numeric',
            'produk_id'=>'required',
            'maximum_unit_name'=>'required|numeric',
            'minimum_unit_name'=>'required|numeric',
            'default_value'=>'required|numeric',
            'note'=>'regex:/(^[A-Za-z0-9 .,]+$)+/'
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed'         
        );
    }

    public function index()
    {
        return view("pages.admin.unit.index");
    }

    public function datatable(){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder();
        return datatables()->of($data)->toJson();
    }

    public function join_builder($id=null){
        if ($id == null){
        $data = DB::table('tbl_unit as t')
            ->join('tbl_satuan as a','t.maximum_unit_name','=','a.id_satuan')
            ->join('tbl_satuan as b','t.minimum_unit_name','=','b.id_satuan')
            ->join('tbl_produk as c','t.produk_id','=','c.produk_id')
            
            ->select('id_unit','produk_nama','a.nama_satuan as maximum_unit_name','b.nama_satuan as minimum_unit_name','default_value','c.produk_id as produk_id')
            ->get();
        return $data;
        }else{
            $data = DB::table('tbl_unit as t')
            ->where('t.produk_id',$id)
            ->join('tbl_satuan as a','t.maximum_unit_name','=','a.id_satuan')
            ->join('tbl_satuan as b','t.minimum_unit_name','=','b.id_satuan')
            ->join('tbl_produk as c','t.produk_id','=','c.produk_id')
            
            ->select('id_unit','produk_nama','a.nama_satuan as maximum_unit_name','b.nama_satuan as minimum_unit_name','default_value','c.produk_id as produk_id')
            ->orderBy('id_unit', 'desc')
            ->get();
        return $data;
        }
    }

    // bantuan untuk unit opname
    public function get_unit_opname($id,$cabang){
                $data = DB::table('tbl_unit as t')
                ->join('tbl_satuan as a','t.maximum_unit_name','=','a.id_satuan')
                ->join('tbl_satuan as b','t.minimum_unit_name','=','b.id_satuan')
                ->join('tbl_produk as c','t.produk_id','=','c.produk_id')
                ->leftjoin('tbl_stok as d','d.produk_id','=','c.produk_id')
                ->where('d.stok_id',$id)
                ->where('id_cabang',$cabang)
                ->select('id_unit','produk_nama','a.nama_satuan as maximum_unit_name','b.nama_satuan as minimum_unit_name','default_value','d.stok_id as stok_id','d.produk_id as produk_id')
                ->orderBy('id_unit', 'desc')
                ->get();
                return response()->json(['data'=>$data]); 
    }

    public function get(Request $request,$id=null)
    {
        try{
            if($id){
                $data = Unit::findOrFail($id);
            }else{
                $data = Unit::all();
            }
            return response()->json(['data'=>$data,'status'=>200]);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }

    public function add(Request $request){
        // id_brand belum final
        $validator = Validator::make($request->all(),$this->rules,$this->messages);
        if($validator->fails()){
            return response()->json(['messageForm'=>$validator->errors(),'status'=>422,'message'=>'Data Tidak Valid']);
        }else{
            return response()->json(['id'=>Unit::create($request->all())->id_unit,'message'=>'Data Berhasil Ditambahkan','status'=>200]);
        }
    }

    public function edit(Request $request){
        $id = $request->input('id_unit');
        try{
            $edit = Unit::findOrFail($id);
            $validator = Validator::make($request->all(),$this->rules,$this->messages);
            if($validator->fails()){
                return response()->json(['messageForm'=>$validator->errors(),'status'=>422,'message'=>'Data Tidak Valid']);
            }else{
                $edit->produk_id = $request->input('produk_id');
                $edit->maximum_unit_name = $request->input('maximum_unit_name');
                $edit->minimum_unit_name = $request->input('minimum_unit_name');
                $edit->default_value = $request->input('default_value');
                $edit->note = $request->input('note');
                $edit->save();
                return response()->json(['message'=>'Data Berhasil Di Edit','data'=>$edit,'status'=>200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }

    }

    public function remove(Request $request, $id){
        try{
            $data = Unit::findOrFail($id);
            $data->delete();
            return response()->json(['message'=>'Data Berhasil Di Hapus','status'=>200]);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }

    public function getUnit($id){
        try{
            $data = $this->join_builder($id);
            return response()->json(['data'=>$data,'status'=>200]);
        }catch(ModelNotFoundException $e){
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }

}
