<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockSalDotLog extends Model
{
    protected $table = 'stock_sal_dot_logs';
    protected $fillable
        = [
            'id_salon',
            'id_dotation',
            'slug_type',
            'title_dotation',
            'old_stock',
            'stock',
            'order_id',
        ];

    public function scopeGetStock($query, $id_salon, $id_dotation)
    {
        return $query->where('id_salon', $id_salon)
            ->where('id_dotation', $id_dotation)
            ->select(['stock'])
            ->first();
    }

    public function scopeUpdateStock($query, $id_salon, $id_dotation, $stock)
    {
        return $query->where('id_salon', $id_salon)
            ->where('id_dotation', $id_dotation)
            ->first()
            ->update(['stock' => $stock]);
    }
}
