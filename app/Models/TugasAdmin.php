<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasAdmin extends Model
{
    protected $table = 'tugas_admins'; // Harus sama persis dengan nama di migration
protected $fillable = [
        'name',
        'email',
        'password',
        'tanggung_jawab', // Tambahkan ini
        'role',           // Tambahkan ini
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}