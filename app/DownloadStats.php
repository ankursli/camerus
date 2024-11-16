<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownloadStats extends Model
{
    protected $table = 'stats_download_log';
    protected $fillable
        = [
            'slug',
            'date',
            'name',
            'link',
            'count',
        ];

    public function scopeGetStat($query, $slug, $date)
    {
        return $query->where('slug', $slug)
            ->where('date', $date)
            ->first();
    }

    public function scopeUpdateStat($query, $slug, $date, $count)
    {
        return $query->where('slug', $slug)
            ->where('date', 'like', '%' . $date . '%')
            ->first()
            ->update(['count' => $count]);
    }
}
