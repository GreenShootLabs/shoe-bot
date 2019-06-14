<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use OpenDialogAi\ConversationBuilder\Conversation;
use OpenDialogAi\Core\Graph\DGraph\DGraphClient;

class SetUpConversations extends Command
{
    protected $signature = 'conversations:setup';

    protected $description = 'Sets up some example conversations for the opendialog project';

    private static $conversations = [
        'resources/conversations/no_match_conversation',
        'resources/conversations/welcome',
        'resources/conversations/close_conversation',
        'resources/conversations/get_distance',
        'resources/conversations/get_experience',
        'resources/conversations/get_frequency',
        'resources/conversations/get_remaining_details',
        'resources/conversations/get_surface',
        'resources/conversations/get_updates',
        'resources/conversations/pronation_help',
        'resources/conversations/shoe_discovery_conversation',
        'resources/conversations/store_foot_type',
    ];

    public function handle()
    {
        if (!$this->confirm('This will clear your local dgraph and all conversations. ' .
            'Are you sure you want to continue?')) {
            $this->info("OK, not running");
            exit;
        }

        $client = app()->make(DGraphClient::class);

        $this->info('Dropping Schema');
        $client->dropSchema();

        $this->info('Init Schema');
        $client->initSchema();

        $this->info('Setting all existing conversations to unpublished');
        Conversation::all()->each(function (Conversation $conversation) {
            $conversation->status = 'validated';
            $conversation->save();
        });

        foreach (self::$conversations as $conversation) {
            $this->importConversation($conversation);
        }

        $this->info('Imports finished');
    }

    protected function importConversation($fileName): void
    {
        $conversationName = array_last(explode('/', $fileName));
        $this->info(sprintf('Importing conversation %s', $conversationName));
        Artisan::call(
            'conversation:import',
            [
                'filename' => $fileName,
                '--publish' => true,
                '--yes' => true
            ]
        );
    }
}
