<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockSalDot extends Model
{
    protected $table = 'stock_sal_dots';
    protected $fillable
        = [
            'id_salon',
            'id_dotation',
            'slug_type',
            'title_dotation',
            'stock',
        ];

    public function scopeHasStock($query)
    {
        return $query->whereHas('stock', '>', 0);
    }

    public function scopeGetStock($query, $id_salon, $id_dotation)
    {
        return $query->where('id_salon', $id_salon)
            ->where('id_dotation', $id_dotation)
            ->select(['stock'])
            ->first();
    }

    public function scopeUpdateStock($query, $id_salon, $id_dotation, $stock, $title = '')
    {
        return $query->where('id_salon', $id_salon)
            ->where('id_dotation', $id_dotation)
            ->first()
            ->update([
                'stock'          => $stock,
                'title_dotation' => $title,
            ]);
    }
}
