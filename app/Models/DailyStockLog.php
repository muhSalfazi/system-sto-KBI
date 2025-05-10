<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DailyStockLog extends Model
{
    use HasFactory;
    protected $table = 'tbl_daily_stock_logs';
    protected $fillable = [
        'id_inventory',
        'prepared_by',
        'Total_qty',
        'created_at',
        'updated_at',
    ];
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'id_inventory');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

}
