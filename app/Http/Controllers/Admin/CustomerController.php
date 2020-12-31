<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_customer'=>'numeric',
            'nama_customer'=>'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'nama_perusahaan'=>'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'credit_plafond'=>'numeric',
            'alamat'=>'required|regex:/(^[A-Za-z0-9 .,]+$)+/',
            'negara'=>'regex:/(^[A-Za-z0-9 ]+$)+/',
            'kota'=>'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'telepon'=>'numeric',
            'kartu_kredit'=>'numeric',
            'fax'=>'numeric',
            'id_sales'=>'required',
            'note'=>'regex:/(^[A-Za-z0-9 .,]+$)+/',
            'id_cabang'=>'numeric'
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed'
                   
        );
    }

    public function index()
    {
        return view("pages.admin.customer.index");
    }

    public function datatable($id_cabang){
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder($id_cabang);
        return datatables()->of($data)->toJson();
        
    }

    public function join_builder($id_cabang){
        // tempat join hanya menselect beberapa field
        $data = DB::table('tbl_customer')
                ->where('tbl_customer.id_cabang',$id_cabang)
                ->join('tbl_sales','tbl_sales.id_sales','=','tbl_customer.id_sales')
                ->select('id_customer','nama_customer','nama_perusahaan','credit_plafond','tbl_customer.alamat as alamat','negara','kota','tbl_customer.telepon','kartu_kredit','fax','tbl_customer.id_sales as id_sales','nama_sales','note')
                ->get();
        return $data;
    }

    public function get(Request $request,$id=null)
    {
        try{
            if($id){
                $data = Customer::findOrFail($id);
            }else{
                $data = Customer::all();
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
            return response()->json(['id'=>Customer::create($request->all())->id_customer,'message'=>'Data Berhasil Ditambahkan','status'=>200]);
        }
    }

    public function edit(Request $request){
        $id = $request->input('id_customer');
        try{
            $edit = Customer::findOrFail($id);
            $validator = Validator::make($request->all(),$this->rules,$this->messages);
            if($validator->fails()){
                return response()->json(['messageForm'=>$validator->errors(),'status'=>422,'message'=>'Data Tidak Valid']);
            }else{
                $edit->nama_customer = $request->input('nama_customer');
                $edit->nama_perusahaan = $request->input('nama_perusahaan');
                $edit->credit_plafond = $request->input('credit_plafond');
                $edit->alamat = $request->input('alamat');
                $edit->negara = $request->input('negara');
                $edit->kota = $request->input('kota');
                $edit->telepon = $request->input('telepon');
                $edit->kartu_kredit = $request->input('kartu_kredit');
                $edit->fax = $request->input('fax');
                $edit->id_sales = $request->input('id_sales');
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
            $data = Customer::findOrFail($id);
            $data->delete();
            return response()->json(['message'=>'Data Berhasil Di Hapus','status'=>200]);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message'=>'Data Tidak Ditemukan','status'=>404]);
        }
    }
}
