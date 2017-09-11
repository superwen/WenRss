<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdDailyChart extends Model
{
    protected $table = 'ad_daily_chart';
    public $timestamps = false;
    protected $fillable = [
        'date',
        'device_type',
        'huan_operate_time',
        'materiel_format',
        'materiel_type',
        'materiel_name',
        'requests',
        'pv',
        'uv',
        'boot_frequency',
        'filling_rate',
        'display_rate',
        'activated_devices',
        'daily_actvie_devices',
        'daily_request_devices',
        'daily_add_devices',
        'daily_boot_rate',
    ];
}
