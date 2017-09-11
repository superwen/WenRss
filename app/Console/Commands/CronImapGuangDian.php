<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Search\Text\Body;
use Ddeboer\Imap\Search\Date\After;
use Carbon\Carbon;

class CronImapGuangDian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:ImapGuangDian';

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

        $lastWeek = Carbon::now()->subDays(1);
        $search = new SearchExpression();
        $search->addCondition(new After($lastWeek))
            ->addCondition(new Body('chongqing guangdian epg pv uv'));

        $messages = $mailbox->getMessages($search);
        foreach ($messages as $message) {
            $lines = explode("\n", $message->getContent());
            foreach($lines as $line) {
                $tds = explode(" ", str_replace("  ", " ", trim($line)));
                if($tds && count($tds) > 1) {
                    $this->info($tds[0] . "------" . $tds[2]);
                }
            }
            exit;
        }
    }
}
