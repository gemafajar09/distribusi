<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ["id_gudang"=>1,"nama_gudang"=>"Gudang Utama","alamat_gudang"=>"Jln Sarang Gagak","telepon_gudang"=>"0751767435","id_cabang"=>1];
        DB::table('tbl_gudang')->insert($data);
    }
}
