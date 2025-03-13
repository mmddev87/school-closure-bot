<?php

use App\Commands\Check;
use App\Commands\SetReminder;
use App\Commands\Start;
use App\RequestInputs\AskCityName;
use App\Storage\JsonStorage;
use OpenAI\Exceptions\ErrorException;
use WeStacks\TeleBot\TeleBot;

require_once 'vendor/autoload.php';

function defineConstants() {
    define('BASE_PATH', __DIR__);
}

function run_polling(TeleBot $bot)
{
    $last_offset = 0;
    while (true) {
        $updates = $bot->getUpdates([
            'offset' => $last_offset + 1
        ]);
        foreach ($updates as $update) {
            $bot->handleUpdate($update);
            $last_offset = $update->update_id;
        }

        sleep(1);
    }
}


function main()
{
    Dotenv\Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();
    
    $bot = new TeleBot([
        'token'      => getenv('BOT_TOKEN'),
        'name'       => '<telegram bot name>',
        'api_url'    => (getenv('TELEGRAM_BOT_API')
            ? getenv('TELEGRAM_BOT_API') . '{TOKEN}/{METHOD}'
            : 'https://api.telegram.org/bot{TOKEN}/{METHOD}'),
        'exceptions' => true,
        'async'      => false,
        'storage' => JsonStorage::class,
        'handlers'   => [
            Start::class,
            Check::class,
            SetReminder::class,

            AskCityName::class
        ]
    ]);
    $bot->deleteLocalCommands();
    $bot->setLocalCommands();

    defineConstants();
    
    polling:
    try {
        run_polling($bot);
    } catch (ErrorException $ex) {
        echo 'There is a problem while connecting to groq: ' . $ex->getMessage();
        goto polling;
    }
}


main();