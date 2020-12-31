<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Gudang;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
class GudangController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_gudang' => '',
            'nama_gudang' => 'required',
            'alamat_gudang' => 'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'telepon_gudang' => 'required|regex:/(^[0-9]+$)+/',
            'id_cabang' => 'numeric',
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed',
        );
    }

    public function index()
    {
        $cabang = DB::table('tbl_cabang')
                ->select('id_cabang','nama_cabang')
                ->get();
                $codeinv = DB::table('tbl_gudang')->orderBy('id_gudang','desc')->first();
                if($codeinv == NULL){
                    $inv= "WAR-00001";
                }else{
                    $cekinv = substr($codeinv->id_gudang,6,9);
                    $plus = (int)$cekinv + 1;
                    $index = (int)$cekinv;
                    if($index < 9){
                        $inv = "WAR-0000".$plus;
                    }
                    else if($index >= 9){
                        $inv = "WAR-000".$plus;
                    }else if($index >= 99){
                        $inv = "WAR-00".$plus;
                    }
                }
        return view("pages.admin.gudang.index",compact(['cabang','inv']));
    }

    public function datatable()
    {
        $data = DB::table('tbl_gudang')
                ->join('tbl_cabang','tbl_cabang.id_cabang','=','tbl_gudang.id_cabang')
                ->select('*')
                ->get();
        return datatables()->of($data)->toJson();
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = gudang::findOrFail($id);
            } else {
                $data = gudang::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function add(Request $request)
    {
        // id_brand belum final
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            $data = Gudang::insert($request->all());
            return response()->json(['id' =>$data, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input('id_gudang');
        try {
            $edit = Gudang::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                
                $edit->nama_gudang = $request->input('nama_gudang');
                $edit->alamat_gudang = $request->input('alamat_gudang');
                $edit->telepon_gudang = $request->input('telepon_gudang');
                $edit->id_cabang = $request->input('id_cabang');
                $edit->save();
                return response()->json(['message' => 'Data Berhasil Di Edit', 'data' => $edit, 'status' => 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Gudang::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function gudangcabang($id_cabang){
        $data = DB::table('tbl_gudang')->where('id_cabang',$id_cabang)->get();
        return response()->json(['data'=>$data]);
    }

}
