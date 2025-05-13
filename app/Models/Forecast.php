<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;

    protected $table = 'tbl_forecast';

    protected $fillable = [
        'id_inventory',
        'hari_kerja',
        'min',
        'max',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'id_inventory');
    }
}
