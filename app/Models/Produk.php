<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{

    public $timestamps = false;
    // define table
    protected $table = 'tbl_produk';
    // define primary key
    protected $primaryKey = 'produk_id';
    // define fillable
    protected $fillable = [
        'id_type_produk', 'produk_brand', 'produk_nama', 'produk_harga', 'stok', 'harga_modal',
    ];

    public function type()
    {
        return $this->hasOne("App\Models\Type", "id_type_produk", "id_type_produk");
    }

    public function unit()
    {
        return $this->hasMany("App\Models\Unit", "produk_id", "produk_id");
    }

    public function satuan()
    {
        return $this->hasMany("App\Models\Satuan", "id_satuan", "id_satuan");
    }
}
