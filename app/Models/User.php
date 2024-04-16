<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'alamat',
        'telepon',
        'position',
        'photo',
        'jml_cuti',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if the user has the given role.
     *
     * @param string $role
     * @return bool
     */

    // public function hasRole($role)
    // {
    //     return $this->role === $role;
    // }

    public function hasRole($roleName)
{
    // Ambil role pengguna
    $userRole = $this->role;

    // Periksa apakah role pengguna sesuai dengan peran yang diberikan
    return $userRole === $roleName;
}

    

}
