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
            'id_gudang' => 'numeric',
            'nama_gudang' => 'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'alamat_gudang' => 'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'telepon_gudang' => 'required|regex:/(^[0-9]+$)+/',
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed',
        );
    }

    public function index()
    {
        return view("pages.admin.gudang.index");
    }

    public function datatable()
    {
        return datatables()->of(Gudang::all())->toJson();
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
            return response()->json(['id' => Gudang::create($request->all())->id_satuan, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
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

}