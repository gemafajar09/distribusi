<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Cost;
use App\Models\Sales;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class CostController extends Controller
{
    public function __construct()
    {
        $this->rules = array(
            'tanggal' => 'required|date',
            'cost_nama' => 'required|regex:/(^[A-Za-z0-9 ]+$)+/',
            'nominal' => 'required|numeric'
        );
        $this->messages = array(
            'regex' => 'The Symbol Are Not Allowed'

        );
    }

    public function index()
    {
        $id_session = strlen(session()->get('id'));
        $inv = DB::table('tbl_cost')->orderby('cost_id', 'desc')->first();
        $user = str_pad($id_session, 3, "0", STR_PAD_LEFT);
        if ($inv == NULL) {
            $invoice = 'OPR-' . date('ymd') . "-" . $user . '-1';
        } else {
            $cekinv = substr($inv->inv_cost, 15, 50);
            $plus = (int)$cekinv + 1;
            $invoice = 'OPR-' . date('ymd') . "-" . $user . '-' . $plus;
        }
        $id_cabang = session()->get('cabang');

        $data['id_cabang'] = $id_cabang;
        $data['invoice'] = $invoice;
        $data['datasales'] = DB::table('tbl_sales')->get();

        return view("pages.admin.cost.index", $data);
    }

    public function datatable($id_cabang)
    {
        // untuk datatables Sistem Join Query Builder
        $data = $this->join_builder($id_cabang);
        return datatables()->of($data)->toJson();
    }

    public function join_builder($id_cabang)
    {
        $data = DB::table('tbl_cost')
            ->where('tbl_cost.id_cabang', $id_cabang)
            ->leftJoin('tbl_sales', 'tbl_cost.id_sales', '=', 'tbl_sales.id_sales')
            ->select('*')
            ->orderby('cost_id', 'asc')
            ->get();
        return $data;
    }

    public function get(Request $request, $id = null)
    {
        try {
            if ($id) {
                $data = Cost::findOrFail($id);
            } else {
                $data = Cost::all();
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
            return response()->json(['id' => Cost::create($request->all())->cost_id, 'message' => 'Data Berhasil Ditambahkan', 'status' => 200]);
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input('cost_id');
        try {
            $edit = Cost::findOrFail($id);
            $validator = Validator::make($request->all(), $this->rules, $this->messages);
            if ($validator->fails()) {
                return response()->json(['messageForm' => $validator->errors(), 'status' => 422, 'message' => 'Data Tidak Valid']);
            } else {
                $edit->id_sales = $request->input('id_sales');
                $edit->tanggal = $request->input('tanggal');
                $edit->cost_nama = $request->input('cost_nama');
                $edit->nominal = $request->input('nominal');
                $edit->note = $request->input('note');
                $edit->type = $request->input('type');
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
            $data = Cost::findOrFail($id);
            $data->delete();
            $id_session = strlen(session()->get('id'));
            $inv = DB::table('tbl_cost')->orderby('cost_id', 'desc')->first();
            $user = (str_pad($id_session, 3, "0", STR_PAD_LEFT));
            if ($inv == NULL) {
                $invoice = 'OPR-' . date('ymd') . "-" . $user . '-1';
            } else {
                $cekinv = substr($inv->inv_cost, 15, 50);
                $plus = (int)$cekinv + 1;
                $invoice = 'OPR-' . date('ymd') . "-" . $user . '-' . $plus;
            }
            return response()->json(['message' => 'Data Berhasil Di Hapus', 'status' => 200, 'kode_terakhir' => $invoice]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Data Tidak Ditemukan', 'status' => 404]);
        }
    }
}
