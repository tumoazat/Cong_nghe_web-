<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    public $incrementing = false;

    protected $fillable = [
        'sale_id',
        'medicine_id',
        'quantity',
        'sale_date',
        'customer_phone',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    /**
     * Quan hệ với bảng medicines
     */
    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'medicine_id');
    }
}
