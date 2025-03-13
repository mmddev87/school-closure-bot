<?php

namespace App\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class Start extends CommandHandler
{
    protected static $aliases = [ '/start' ];
    protected static $description = 'شروع استفاده از ربات';

    public function handle()
    {
        echo 'start';
    }
}
