<?php

namespace Tests\Feature;

use App\Console\Commands\Specification\BaseSpecificationCommand;
use App\ImportExportHelpers\MessageImportExportHelper;
use Illuminate\Support\Facades\Artisan;
use OpenDialogAi\ResponseEngine\MessageTemplate;

/**
 * Class ImportExportSpecificationTest
 * @package Tests\Feature
 * @group SpecificationTests
 */
class ImportExportSpecificationTest extends BaseSpecificationTest
{
    public function testImportSpecification()
    {
        $this->assertDatabaseMissing('conversations', ['name' => 'no_match_conversation']);
        $this->assertDatabaseMissing('outgoing_intents', ['name' => 'intent.core.NoMatchResponse']);
        $this->assertDatabaseMissing('message_templates', ['name' => 'Did not understand']);

        Artisan::call(
            'specification:import',
            [
                '--yes' => true
            ]
        );

        $this->assertDatabaseHas('conversations', ['name' => 'no_match_conversation']);
        $this->assertDatabaseHas('outgoing_intents', ['name' => 'intent.core.NoMatchResponse']);
        $this->assertDatabaseHas('message_templates', ['name' => 'Did not understand']);
    }

    public function testExportSpecification()
    {
        $this->assertDatabaseMissing('conversations', ['name' => 'no_match_conversation']);
        $this->assertDatabaseMissing('outgoing_intents', ['name' => 'intent.core.NoMatchResponse']);
        $this->assertDatabaseMissing('message_templates', ['name' => 'Did not understand']);

        Artisan::call(
            'specification:import',
            [
                '--yes' => true
            ]
        );

        $this->assertDatabaseHas('conversations', ['name' => 'no_match_conversation']);
        $this->assertDatabaseHas('outgoing_intents', ['name' => 'intent.core.NoMatchResponse']);
        $this->assertDatabaseHas('message_templates', ['name' => 'Did not understand']);

        /** @var MessageTemplate $messageTemplate */
        $messageTemplate = MessageTemplate::where('name', 'Did not understand')->first();
        $markup = "<message><empty-message></empty-message></message>";
        $messageTemplate->message_markup = $markup;
        $messageTemplate->save();

        Artisan::call(
            'specification:export',
            [
                '--yes' => true
            ]
        );

        $messageFileName = MessageImportExportHelper::addMessageFileExtension($messageTemplate->name);
        $filename = MessageImportExportHelper::getMessagePath($messageFileName);
        $message = $this->disk->get($filename);
        $this->assertStringContainsString($markup, $message);
    }
}
