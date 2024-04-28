<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajucuti extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model
    protected $table = 'ajucutis';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_user',
        'mulai_cuti',
        'selesai_cuti',
        'alasan',
        'jml_cuti',
        'status',
        'approved', 
    ];

    // Relasi ke model User untuk mendapatkan data pengguna yang mengajukan cuti
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function totalCutiDiambil()
{
    // Mengambil semua data pengajuan cuti yang telah disetujui untuk pengguna ini
    $totalCuti = $this->ajucutis()->where('approved', '!=', null)->sum(function ($query) {
        // Menghitung selisih hari antara tanggal mulai dan selesai cuti
        return $query->selesai_cuti->diffInDays($query->mulai_cuti);
    });

    return $totalCuti;
}



}
