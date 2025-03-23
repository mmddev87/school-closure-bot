<?php

namespace Presentation\Telebot\Commands;

use WeStacks\TeleBot\Handlers\CommandHandler;

class SetReminder extends CommandHandler
{
    protected static $aliases = [ '/setreminder' ];
    protected static $description = 'فعال سازی اطلاع رسانی تعطیلی';

    public function handle()
    {
        echo 'setreminder';
    }
}
