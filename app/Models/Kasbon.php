<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kasbon extends Model
{
    protected $fillable = [
        'nama',
        'divisi',
        'keterangan',
        'jumlah_kasbon',
        'pengajuan_id',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
