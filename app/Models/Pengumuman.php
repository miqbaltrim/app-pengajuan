<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pengumuman extends Model
{
    // Nama tabel yang terkait dengan model
    protected $table = 'pengumumans';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'image',
        'caption',
    ];

    // Relasi ke model User untuk mendapatkan data pengguna yang membuat pengumuman
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
