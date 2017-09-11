<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Email\To;
use Ddeboer\Imap\Search\Text\Body;
use Ddeboer\Imap\Search\Date\After;
use Carbon\Carbon;
use Overtrue\Pinyin\Pinyin;
use App\Models\Staff;
use App\Models\StaffWeekly;

class CronImapHuan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:ImapHuan';

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
        $server = new Server('imap.huan.tv', 993, '/imap/ssl/novalidate-cert');
        $connection = $server->authenticate('chenshengwen@huan.tv', 'Tureture#123');
        $mailbox = $connection->getMailbox('INBOX');

        $lastWeek = Carbon::now()->subWeek(2);
        $search = new SearchExpression();
        $search->addCondition(new After($lastWeek))
            ->addCondition(new Body('周报'));

        $messages = $mailbox->getMessages($search);
        foreach ($messages as $message) {

            $staffObject = Staff::where('mail', $message->getFrom())->first();
            if(!$staffObject) {
                $this->error($message->getFrom()." is not found!");
                continue;
            }

            $mailDay = $message->getDate()->format("Y-m-d");
            $date = Carbon::createFromFormat("Y-m-d", $mailDay)->subDays(3);
            $staffWeeklyObject = StaffWeekly::where('staffId', $staffObject->id)
                ->where('week', $date->format('W'))->first();
            if(!$staffWeeklyObject) {
                $staffWeeklyObject = new StaffWeekly();
                $staffWeeklyObject->staffId = $staffObject->id;
                $staffWeeklyObject->week = $date->format('W');
                $staffWeeklyObject->mailDay = $mailDay;
                $staffWeeklyObject->save();
                $this->info($message->getNumber());
                $this->info($message->getFrom());
            }
        }
    }

    public function initStaff()
    {
        $pinyin = new Pinyin();
        $names = explode(',', '陈圣文,李富仓,王建朝,王杏,刘洋,李海鹏,田忠升,冯大帅,张宇,马丽娟,刘鹏,槐仁刚,张雪峰,任亮,程浩,朱峰,卞峰,必胜侃,杨倩,刘金金,姜苗,谢潘婷,聂建明,任安刚,高梵,沈斌,王佳仪,姚兰,赵传龙,王晓军,李立,郭瑾,张贵发,刘宇');
        foreach($names as $name){
            $mail = implode("", $pinyin->name($name))."@huan.tv";
            $this->info($name);
            $this->info($mail);
            $staffObj = new Staff();
            $staffObj->mail = $mail;
            $staffObj->name = $name;
            $staffObj->save();
        }

    }
}
