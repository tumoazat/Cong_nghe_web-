<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medicines';
    protected $primaryKey = 'medicine_id';
    public $incrementing = false;

    protected $fillable = [
        'medicine_id',
        'name',
        'brand',
        'dosage',
        'form',
        'price',
        'stock',
    ];

    /**
     * Quan hệ với bảng sales
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'medicine_id', 'medicine_id');
    }
}
