<?php

namespace Presentation\Telebot\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class Start extends CommandHandler
{
    protected static $aliases = [ '/start' ];
    protected static $description = 'شروع استفاده از ربات';

    public function handle()
    {
        $this->sendMessage([
            "text" => <<<EOL
                به ربات تعطیلی مدارس خوش آمدید.
                برای استفاده از ربات از دستور /check استفاده کنید.
                EOL
        ]);
    }
}
