<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateDokumen extends Model
{
    use HasFactory;

    protected $table = 'template_dokumen';
    protected $primaryKey = 'id_template';
    public $timestamps = true;

    protected $fillable = [
        'nama_template',
        'jenis_template',
        'file',
    ];
}
