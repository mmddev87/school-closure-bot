<?php

namespace Presentation\Web\Controllers;

use Core\Interfaces\{AIClosureValidatorInterface, NewsScraperInterface, StorageInterface};
use Core\UseCases\CheckSchoolClosureUseCase;

class CheckClosureController
{
    public function __invoke($city = null)
    {
        if ($city) {
            return [
                'ok' => true,
                'result' => (new CheckSchoolClosureUseCase(
                    aiValidator: container()->get(AIClosureValidatorInterface::class),
                    newsScraper: container()->get(NewsScraperInterface::class),
                    closureStorage: container()->get(StorageInterface::class)
                ))->execute($city)
            ];
        } else {
            return [
                'ok' => false
            ];
        }
    }
}
