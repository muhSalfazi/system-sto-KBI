<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $table = 'tbl_part';
    protected $fillable = ['inv_id', 'part_name', 'part_number', 'id_customer', 'id_pkg', 'id_plant', 'id_area', 'id_rak'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function package()
    {
        return $this->hasOne(Package::class, 'id_part');
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class, 'id_plant');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    public function rak()
    {
        return $this->belongsTo(Rak::class, 'id_rak');
    }
}
