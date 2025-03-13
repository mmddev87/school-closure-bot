<?php

namespace App\Commands;

use App\RequestInputs\AskCityName;
use WeStacks\TeleBot\Handlers\CommandHandler;

class Check extends CommandHandler
{
    protected static $aliases = ['/check'];
    protected static $description = 'بررسی تعطیلی مدارس';

    public function handle()
    {
        AskCityName::requestInput($this->bot, $this->update->user()->id);

        return $this->sendMessage([
            'text' => 'لطفا نام شهر مورد نظر را وارد کنید.'
        ]);
    }


}
