<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertisementPosition extends Model
{
    protected $fillable = [
        'name',
        'code',
        'platform',
        'ad_type',
        'page',
        'placement',
        'description',
        'width',
        'height',
        'base_price',
        'price_type',
        'default_duration',
        'max_active_ads',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'base_price' => 'decimal:2',
        'default_duration' => 'integer',
        'max_active_ads' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function advertisementRequests()
    {
        return $this->hasMany(AdvertisementRequest::class, 'advertisement_position_id');
    }
}
