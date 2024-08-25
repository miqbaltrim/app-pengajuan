<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Izin extends Model
{
    protected $fillable = [
        'mulai_izin',
        'selesai_izin',
        'alasan',
        'disetujui_oleh',
        'diketahui_oleh',
        'setujui',
        'ketahui',
        'dibuat_oleh',
    ];

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
}



