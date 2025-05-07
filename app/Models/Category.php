<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'tbl_category';
    protected $fillable = [
        'id',
        'name',
    ];

    public function Inventorys()
    {
        return $this->hasMany(Inventory::class, 'id_category');
    }
}
