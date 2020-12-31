<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
 
class CabangController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_cabang' => 'numeric',
            'nama_cabang' => 'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'alamat' => 'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'kode_cabang' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'telepon' => 'required|numeric',
            'email' => 'required|email'
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed'

        );
    }

    public function index()
    {
        return view("pages.admin.cabang.index");
    }

    public function datatable()
    {
        // untuk datatables Sistem Join Query Builder
        return datatables()->of(Cabang::all())->toJson();
    }

    public function join_builder($id = null)
    {
        // tempat join hanya menselect beberapa field
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Cabang::findOrFail($id);
            } else {
                $data = Cabang::all();
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
            return response()->json(['id' => Cabang::create($request->all())->id_cabang, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input('id_cabang');
        try {
            $edit = Cabang::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                $edit->nama_cabang = $request->input('nama_cabang');
                $edit->alamat = $request->input('alamat');
                $edit->kode_cabang = $request->input('kode_cabang');
                $edit->telepon = $request->input('telepon');
                $edit->email = $request->input('email');
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
            $data = Cabang::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
