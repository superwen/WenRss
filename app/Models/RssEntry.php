<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rss;

class RssEntry extends Model
{
    protected $table = 'rss_entry';
    public $timestamps = false;
    protected $fillable = ['rssID', 'ownerID','title', 'summary', 'published', 'link', 'tag', 'crontabTime', 'read'];

    public function rss()
    {
        return $this->belongsTo(Rss::class, 'rssID','id');
    }

}
