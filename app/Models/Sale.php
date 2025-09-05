<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['cashier_id', 'sale_date', 'total_amount'];

    // ✅ Relationships
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
