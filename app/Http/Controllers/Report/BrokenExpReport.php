<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Models\BrokenExpMovement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BrokenExpReport extends Controller
{
    public function index()
    {
        return view('report.broken_exp.index');
    }

    public function tablereport(Request $request, $search = null)
    {
        $data = $this->join_builder($search);
        $dataisi = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            $jumlah_broken = $d->jumlah_broken;
            $capital_price = $d->capital_price;
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();

            $stokquantity = $this->convert($proses, $jumlah_broken);
            $jumlah_stok = implode(" ", $stokquantity);
            $dataisi[] = ["produk_nama" => $d->produk_nama, "capital_price" => $capital_price, "jumlah" => $jumlah_stok, "produk_id" => $d->produk_id, "stok_id" => $d->stok_id , "jumlah_broken" => $jumlah_broken];
        }
        // dd($data);
        return view('report.broken_exp.tables', compact('dataisi'));
    }

    public function join_builder($search = null)
    {
        $data = DB::table('tbl_broken_exp')
            // ->leftJoin('tbl_broken_exp_details', 'tbl_broken_exp.inv_broken_exp', 'tbl_broken_exp_details.inv_broken_exp')
            // ->leftJoin('tbl_produk', 'tbl_produk.produk_id', 'tbl_broken_exp_details.produk_id')
            ->join('tbl_stok', 'tbl_broken_exp.stok_id' , 'tbl_stok.stok_id')
            ->join('tbl_produk', 'tbl_stok.produk_id', 'tbl_produk.produk_id')
            ->join('tbl_type_produk', 'tbl_type_produk.id_type_produk', 'tbl_produk.id_type_produk');
        if (!empty($search)) {
            $data = $data->where('tbl_produk.produk_nama', 'like', '%' . $search . '%');
        }
        $data = $data->select('*')->get();
        // dd($data);
        return $data;
    }

    public function convert($proses, $jumlah)
    {
        $stokquantity = [];
        $hasilbagi = 0;
        $format = '%d %s ';
        $stok = [];
        foreach ($proses as $index => $list) {
            $banyak =  sizeof($proses);
            if ($index == 0) {
                $sisa = $jumlah % $list->default_value;
                $hasilbagi = ($jumlah - $sisa) / $list->default_value;
                $satuan[$index] = $list->unit;
                $lebih[$index] = $sisa;

                if ($sisa > 0) {
                    $stok[$index] = sprintf($format, $sisa, $list->unit);
                }
                if ($banyak == $index + 1) {
                    $satuan = array();
                    $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    $stokquantity = array_values($stok);
                    $stok = array();
                }
            } else if ($index == 1) {
                $sisa = $hasilbagi % $list->default_value;
                $hasilbagi = ($hasilbagi - $sisa) / $list->default_value;
                $satuan[$index] = $list->unit;
                $lebih[$index] = $sisa;

                if ($sisa > 0) {
                    $stok[$index - 1] = sprintf($format, $sisa + $lebih[$index - 1], $satuan[$index - 1]);
                }
                if ($banyak == $index + 1) {
                    $satuan = array();
                    $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    $stokquantity = array_values($stok);
                    $stok = array();
                }
            } else if ($index == 2) {
                $sisa = $hasilbagi % $list->default_value;
                $hasilbagi = ($hasilbagi - $sisa) / $list->default_value;
                $satuan[$index] = $list->unit;
                $lebih[$index] = $sisa;

                if ($sisa > 0) {
                    $stok[$index - 1] = sprintf($format, $sisa,  $satuan[$index - 1]);
                }
                if ($banyak == $index + 1) {
                    $satuan = array();
                    $stok[$index] = sprintf($format, $hasilbagi, $list->unit);
                    $stokquantity = array_values($stok);
                    $stok = array();
                }
            }
        }

        return $stokquantity;
    }

    public function reportprint(Request $r, $search = null)
    {
        $data = $this->join_builder($search);
        $dataisi = [];
        foreach ($data as $d) {
            $id = $d->produk_id;
            $jumlah = $d->jumlah;
            $jumlah_broken = $d->jumlah_broken;
            $capital_price = $d->capital_price;
            $proses = DB::table('tbl_unit')->where('produk_id', $id)
                ->join('tbl_satuan', 'tbl_unit.maximum_unit_name', '=', 'tbl_satuan.id_satuan')
                ->select('id_unit', 'nama_satuan as unit', 'default_value')
                ->orderBy('id_unit', 'ASC')
                ->get();

            $stokquantity = $this->convert($proses, $jumlah);
            $jumlah_stok = implode(" ", $stokquantity);
            $dataisi[] = ["produk_nama" => $d->produk_nama, "capital_price" => $capital_price, "jumlah" => $jumlah_stok, "produk_id" => $d->produk_id, "stok_id" => $d->stok_id , "jumlah_broken" => $d->jumlah_broken];
        }
        return view('report.broken_exp.printbrokenview', compact('dataisi'));
    }
}
