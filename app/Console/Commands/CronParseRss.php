<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rss;
use App\Models\RssEntry;
use Mockery\CountValidator\Exception;
use Vinelab\Rss\Rss as VinelabRss;

class CronParseRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:parseRss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rssObjs = Rss::where('crontabState', '>=', 0)->get();
        $rss = new VinelabRss();
        $now = time();
        foreach($rssObjs as $rssObj) {
            $this->info($rssObj->xmlUrl);
            try {
                $feed = $rss->feed($rssObj->xmlUrl);
                $count = $feed->articlesCount();
                if ($count > 0) {
                    $articles = $feed->articles();
                    foreach ($articles as $article) {
                        $title = trim(strval($article->title));
                        $published = strtotime(trim($article->pubDate));
                        $link = trim(strval($article->link));
                        $entry = RssEntry::where("link", $link)->count();
                        if ($entry == 0) {
                            $this->info($title);
                            $this->info($link);
                            RssEntry::create([
                                'rssID' => $rssObj->id,
                                'ownerID' => '',
                                'title' => $title,
                                'summary' => '',
                                'published' => $published,
                                'link' => $link,
                                'tag' => $rssObj->tag,
                                'crontabTime' => $now,
                                'read' => 0,
                            ]);
                        } else {
                            $this->error($title);
                            $this->error($link);
                        }
                    }
                } else {
                    $rssObj->crontabState = -1;
                    $rssObj->save();
                    \Log::error($rssObj->id . ":" . $rssObj->xmlUrl . " parse error");
                }
            } catch(\Exception $e) {
                $rssObj->crontabState = -1;
                $rssObj->save();
                \Log::error($rssObj->id . ":" . $rssObj->xmlUrl . " exception");
            }
        }
    }
}
