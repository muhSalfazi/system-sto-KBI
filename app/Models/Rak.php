<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    use HasFactory;

    protected $table = 'tbl_rak';
    protected $fillable = ['nama_rak', 'id_area'];

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }
}
