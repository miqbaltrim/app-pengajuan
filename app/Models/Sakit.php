<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Sakit extends Model
{
    use HasFactory;

    protected $fillable = [
        'mulai_sakit',
        'selesai_sakit',
        'alasan',
        'disetujui_oleh',
        'diketahui_oleh',
        'dibuat_oleh',
        'setujui',
        'ketahui',
        'surat_dokter',
    ];

    // Relasi dengan model User
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function diketahuiOleh()
    {
        return $this->belongsTo(User::class, 'diketahui_oleh');
    }

    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
