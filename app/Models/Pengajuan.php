<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Barang;

class Pengajuan extends Model
{
    protected $fillable = [
        'nomor_referensi',
        'dibuat_oleh',
        'disetujui_oleh',
        'diketahui_oleh',
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

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
