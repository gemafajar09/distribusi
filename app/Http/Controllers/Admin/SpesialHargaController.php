<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\SpesialHarga;
use App\Models\Customer;
use App\Models\Produk;
use Yajra\Datatables\Datatables;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class SpesialHargaController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'id_harga_khusus' => 'numeric',
            'id_customer' => 'required|numeric',
            'produk_id' => 'required|',
            'spesial_nominal'=>'required|numeric',
        );
        
    }

    public function index()
    {
        
        return view("pages.admin.spesial.index");
    }

    public function datatable()
    {
        $data = $this->join_builder();
        return datatables()->of($data)->toJson();
    }

    public function join_builder($id = null)
    {
        $data = DB::table('tbl_harga_khusus')
                ->join('tbl_customer','tbl_customer.id_customer','=','tbl_harga_khusus.id_customer')
                ->join('tbl_produk','tbl_produk.produk_id','=','tbl_harga_khusus.produk_id')
                ->select('tbl_harga_khusus.id_customer as id_customer','nama_customer','tbl_harga_khusus.produk_id as produk_id','produk_nama','produk_harga','spesial_nominal','id_harga_khusus')
                ->get();
        return $data;
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = SpesialHarga::findOrFail($id);
            } else {
                $data = SpesialHarga::all();
            }
            return response()->json(['data' => $data, 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }

    public function getCustomer($id_cabang)
    {
       
            $data = Customer::where('id_cabang',$id_cabang)->select('id_customer','nama_customer')->get();
            return response()->json(['data' => $data, 'status' => 200]);
        
    }
    public function getProduk(Request $request, $id = null)
    {
       
            // $data = Produk::all('produk_id','produk_nama');
            $data = DB::table('tbl_produk')->get();
            return response()->json(['data' => $data, 'status' => 200]);
        
    }

    public function add(Request $request)
    {
        // id_brand belum final
        $validator = Validator::make($request->all(), $this->rules);
        if ($validator->fails()) {
            return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
        } else {
            return response()->json(['id' => SpesialHarga::create($request->all())->id_harga_khusus, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input('id_harga_khusus');
        try {
            $edit = SpesialHarga::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules);
            if ($validator->fails()) {
                return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                
                $edit->id_customer = $request->input('id_customer');
                $edit->produk_id = $request->input('produk_id');
                $edit->spesial_nominal = $request->input('spesial_nominal');
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
            $data = SpesialHarga::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
