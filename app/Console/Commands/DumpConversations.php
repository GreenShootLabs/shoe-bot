<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DumpConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversations:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will dump the content of active conversations and messages';

    public function handle()
    {
        $activeConversations = parse_ini_file(__DIR__ . '/active_conversations.ini');
        foreach ($activeConversations['conversations'] as $activeConversation) {
            $fileName =  __DIR__ . "../../../../resources/conversations/$activeConversation";

            Artisan::call('conversation:export', [
                'conversation name' => $activeConversation,
                '-f' => $fileName
            ]);
        }
    }
}
