<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
        'jml_kasbon',
        'cicilan',
        'dana_dari',
        'disetujui_oleh',
        'diketahui_oleh',
        'setujui',
        'ketahui',
    ];

    // Definisikan relasi dengan model User untuk disetujui oleh dan diketahui oleh
    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function diketahuiOleh()
    {
        return $this->belongsTo(User::class, 'diketahui_oleh');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
