<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // jika perlu query builder tanpa eloquent

class Kelas extends Model
{
    protected $table = 'kelas'; // nama tabel

    protected $primaryKey = 'id'; // primary key tabel

    protected $keyType = 'int'; // tipe primary key

    protected $fillable = [ // kolom yang boleh dimanipulasi
        'nama_kelas',
        'status',
    ];

    public $timestamps = true; // menjalankan timestamps untuk pengaturan waktu tabel setiap create dan update

    public static function getJmlKelas()
    {
        return DB::table('kelas')->count(); // dengan query builder
    }
}
