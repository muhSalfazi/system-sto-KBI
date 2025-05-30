<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $table = 'tbl_part';
    protected $fillable = [
        'id',
        'Inv_id',
        'Part_name',
        'Part_number',
        'id_category',
        'id_customer',
        'id_pkg',
        'id_plan',
        'id_area',
    ];

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
        return $this->belongsTo(Plant::class, 'id_plan');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }


    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'id_part');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }
    public function forecasts()
    {
        return $this->hasMany(Forecast::class, 'id_part');
    }

    public function forecast()
    {
        return $this->hasMany(Forecast::class, 'id_part');
    }

}
