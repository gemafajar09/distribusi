<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sales extends Model
{
    public $timestamps = false;
    // define table
    protected $table = 'tbl_sales';
    // define primary key
    protected $primaryKey = 'id_sales';
    // define fillable
    protected $fillable = [
        'nama_sales', 'alamat','telepon','target','id_cabang','username','password','password1'
    ];

    public static function getAll()
    {
        return self::all();
    }

    protected $attributes = [
        'target'=>0
    ];
}
