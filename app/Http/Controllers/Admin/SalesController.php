<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_sales' => '',
            'nama_sales' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'alamat' => 'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'telepon' => 'required|numeric',
            'target'=>'numeric',
            'id_cabang'=>'numeric',
            'username' => 'required',
            'password' => 'required',
            'password1' => 'required'
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed'

        );
    }

    public function index()
    {
        $codeinv = DB::table('tbl_sales')->orderBy('id_sales','desc')->first();
                if($codeinv == NULL){
                    $inv= "SL-00001";
                }else{
                    $cekinv = substr($codeinv->id_sales,3,10);
                    $plus = (int)$cekinv + 1;
                    $index = (int)$cekinv;
                    if($index < 9){
                        $inv = "SL-0000".$plus;
                    }
                    else if($index >= 9 && $index < 99){
                        $inv = "SL-000".$plus;
                    }else if($index >= 99 && $index < 999){
                        $inv = "SL-00".$plus;
                    }else if($index >= 999 && $index < 9999){
                        $inv = "SL-0".$plus;
                    }
                }
        return view("pages.admin.sales.index",compact('inv'));
    }

    public function datatable($id_cabang)
    {
        // untuk datatables Sistem Join Query Builder
        return datatables()->of(DB::table('tbl_sales')->where('id_cabang',$id_cabang)->get())->toJson();
    }


    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                // $data = Sales::findOrFail($id);
                $data = DB::table('tbl_sales')->where('id_sales',$id)->first();
            } else {
                $data = DB::table('tbl_sales')->get();
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
            if($request->password == $request->password1)
            {
                $pass = password_hash($request->password, PASSWORD_DEFAULT);
                $data = DB::table('tbl_sales')->insert([
                    'id_sales' => $request->id_sales,
                    'nama_sales' => $request->nama_sales,
                    'alamat' => $request->alamat,
                    'telepon' => $request->telepon,
                    'target' => $request->target,
                    'id_cabang' => $request->id_cabang,
                    'username' => $request->username,
                    'password' => $pass,
                    'password1' => $request->password1
                ]);
                return response()->json(['message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
            }else{
                return response()->json(['message' => 'Password Tidak Valid', 'status' => 200]);
            }
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input('id_sales');
        try {
            $edit = Sales::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                if($request->password == $request->password1)
                {
                    $pass = password_hash($request->password, PASSWORD_DEFAULT);
                    $edit->nama_sales = $request->input('nama_sales');
                    $edit->alamat = $request->input('alamat');
                    $edit->telepon = $request->input('telepon');
                    $edit->target = $request->input('target');
                    $edit->username = $request->input('username');
                    $edit->password = $pass;
                    $edit->password1 = $request->input('password1');
                    $edit->save();
                    return response()->json(['message' => 'Data Berhasil Di Edit', 'data' => $edit, 'status' => 200]);
                }else{
                    return response()->json(['message' => 'Password Tidak Valid', 'status' => 200]);
                }
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $data = Sales::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function getSales($id_cabang){
            $data = DB::table('tbl_sales')->where('id_cabang',$id_cabang)->get();
            return response()->json(['data' => $data, 'status' => 200]);
    }

    public function loginsales(Request $r)
    {
        $username = $r->username;
        $password = $r->password;
        $data = array();
        $cek = DB::table('tbl_sales')->where('username',$username)->first();
        if($cek->username == TRUE)
        {
            if(password_verify($password,$cek->password))
            {
                $data = array(
                    'nama_sales' => $cek->nama_sales,
                    'id_sales' => $cek->id_sales,
                    'id_cabang' => $cek->id_cabang,
                    'status' => 1
                );
                return response()->json(['data' => $data]);
            }else{
                $data = array(
                    'nama_sales' => '',
                    'id_sales' => '',
                    'id_cabang' => '',
                    'status' => 0
                );
                return response()->json(['data' => $data]);
            }
        }else{
            $data = array(
                'nama_sales' => '',
                'id_sales' => '',
                'id_cabang' => '',
                'status' => 0
            );
            return response()->json(['data' => $data]);
        }
    }
}
