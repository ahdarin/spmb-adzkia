<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalKuesioner extends Model
{
    use HasFactory;
    protected $fillable = ['kategori', 'pertanyaan'];
}