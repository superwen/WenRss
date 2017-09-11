<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Email\FromAddress;
use Ddeboer\Imap\Search\Text\Body;
use Ddeboer\Imap\Search\Date\After;
use Carbon\Carbon;
use Storage;
use voku\helper\HtmlDomParser;
use App\Models\AdDailyChart;
use Datetime;

class CronImapAdChart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:imapAdChart';

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
        //superuser@tvhuan.com
        $server = new Server('imap.huan.tv', 993, '/imap/ssl/novalidate-cert');
        $connection = $server->authenticate('chenshengwen@huan.tv', 'Tureture#123');
        $mailbox = $connection->getMailbox('INBOX');

        $lastWeek = Carbon::now()->subDay(4);
        $search = new SearchExpression();
        $search->addCondition(new After($lastWeek))
            ->addCondition(new Body('广告系统终端报表'));

        $messages = $mailbox->getMessages($search);
        foreach ($messages as $message) {

            $this->info($message->getSubject());
            $dom = HtmlDomParser::str_get_html($message->getBodyText());
            $day =  DateTime::createFromFormat("Y年m月d日", str_replace(['广告系统终端报表(' ,')'], '',$message->getSubject()));
            $trs = $dom->find('tbody tr');
            foreach($trs as $tr) {
                $tds = $tr->find('td');
                $device_type = str_replace('总计', 'total', trim($tds[0]->plaintext));
                $chart = [
                    'date' => $day->format('Y-m-d'),
                    'device_type' => $device_type,
                    'huan_operate_time' => $tds[1]->plaintext ? DateTime::createFromFormat("Y-m-d H:i:s", $tds[1]->plaintext) : null,
                    'materiel_format' => $tds[2]->plaintext,
                    'materiel_type' => $tds[3]->plaintext,
                    'materiel_name' => $tds[4]->plaintext,
                    'requests' => $tds[5]->plaintext,
                    'pv' => $tds[6]->plaintext,
                    'uv' => $tds[7]->plaintext,
                    'boot_frequency' => $tds[8]->plaintext,
                    'filling_rate' => floatval(str_replace("%", "", $tds[9]->plaintext)),
                    'display_rate' => floatval(str_replace("%", "", $tds[10]->plaintext)),
                    'activated_devices' => $tds[11]->plaintext,
                    'daily_actvie_devices' => $tds[12]->plaintext,
                    'daily_request_devices' => $tds[13]->plaintext,
                    'daily_add_devices' => $tds[14]->plaintext,
                    'daily_boot_rate' => floatval(str_replace("%", "", $tds[15]->plaintext)),
                ];
                $chartObj = AdDailyChart::where('date' , $day->format('Y-m-d'))
                    ->where('device_type' , $device_type)
                    ->first();
                if(!$chartObj) {
                    AdDailyChart::create($chart);
                }
            }
        }
    }
}
