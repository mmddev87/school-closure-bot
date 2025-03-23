<?php

namespace Presentation\TeleBot\RequestInputs;

use Core\Interfaces\{AIClosureValidatorInterface, NewsScraperInterface, StorageInterface};
use Core\UseCases\CheckSchoolClosureUseCase;
use WeStacks\TeleBot\Handlers\RequestInputHandler;

class AskCityName extends RequestInputHandler
{
    public function handle()
    {
        $city = $this->update->message()->text;
        $this->acceptInput();

        $closed = $this->checkClosure($city);

        return $this->sendMessage([
            'text' => "مدارس شهر $city فردا تعطیل " . (($closed) ? "است" : "نیست")
        ]);
    }

    private function checkClosure($city)
    {
        return (new CheckSchoolClosureUseCase(
            aiValidator: container()->get(AIClosureValidatorInterface::class),
            newsScraper: container()->get(NewsScraperInterface::class),
            closureStorage: container()->get(StorageInterface::class)
        ))->execute($city);
    }
}
