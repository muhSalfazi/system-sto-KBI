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
        'id_box_complete',
        'id_box_uncomplete',
        'prepared_by',
        'Total_qty',
        'status',
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
    public function boxComplete()
    {
        return $this->belongsTo(BoxComplete::class, 'id_box_complete');
    }
    public function boxUncomplete()
    {
        return $this->belongsTo(BoxUncomplete::class, 'id_box_uncomplete');
    }

}
