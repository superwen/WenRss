<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Hanson\Vbot\Foundation\Vbot;
use Hanson\Vbot\Message\Text;
use Illuminate\Support\Collection;

class WorkVbotWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:VbotWorker';

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
        $config = [
            'path'     => storage_path('vbot'),
            'session'   => '880c90',
        ];
        try {
            $vbot = new Vbot($config);
        } catch (\ErrorException $e) {
            $this->info($e->getMessage());
            exit;
        }
        $vbot->messageHandler->setHandler(function(Collection $message){
            //Text::send($message['from']['UserName'], 'hi');
        });
        $observer = $vbot->observer;
        $observer->setReLoginSuccessObserver(function(){
            \Log::info('ReLoginSuccess');
        });
        $observer->setExitObserver(function(){
            \Log::info('Exit');
        });
        $vbot->server->serve();
    }
}
