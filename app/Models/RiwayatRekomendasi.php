<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatRekomendasi extends Model
{
    use HasFactory;
    protected $guarded = ['id']; // Membolehkan semua kolom diisi (mass assignment)
}