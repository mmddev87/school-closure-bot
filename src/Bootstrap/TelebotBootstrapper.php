<?php

namespace Bootstrap;

use Presentation\Telebot\{
    Commands\Check,
    Commands\SetReminder,
    Commands\Start,
    RequestInputs\AskCityName
};
use WeStacks\TeleBot\TeleBot;

class TelebotBootstrapper
{
    public readonly TeleBot $bot;

    public function __construct() {
        $this->bot = new TeleBot([
            'token'      => getenv('BOT_TOKEN'),
            'name'       => '<telegram bot name>',
            'api_url'    => (getenv('TELEGRAM_BOT_API')
                ? getenv('TELEGRAM_BOT_API') . '{TOKEN}/{METHOD}'
                : 'https://api.telegram.org/bot{TOKEN}/{METHOD}'),
            'exceptions' => true,
            'async'      => false,
            // 'storage' => JsonStorage::class,
            'handlers'   => [
                Start::class,
                Check::class,
                SetReminder::class,
    
                AskCityName::class
            ]
        ]);
    }

    public function runPolling(int $timeout = 1): void
    {
        $last_offset = 0;
        while (true) {
            $updates = $this->bot->getUpdates([
                'offset' => $last_offset + 1
            ]);
            foreach ($updates as $update) {
                $this->bot->handleUpdate($update);
                $last_offset = $update->update_id;
            }

            sleep($timeout);
        }
    }

    public function resetLocalCommands(): self {
        $this->bot->deleteLocalCommands();
        $this->bot->setLocalCommands();
        
        return $this;
    }
}
