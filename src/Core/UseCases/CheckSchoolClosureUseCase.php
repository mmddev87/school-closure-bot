<?php

namespace Core\UseCases;

use Core\Interfaces\{
    NewsScraperInterface,
    AIClosureValidatorInterface,
    StorageInterface
};

class CheckSchoolClosureUseCase
{
    public function __construct(
        private AIClosureValidatorInterface $aiValidator,
        private NewsScraperInterface $newsScraper,
        private StorageInterface $closureStorage,
    ) {}

    public function execute(string $city): bool
    {
        $cached = $this->closureStorage->get($city);
        if ($cached !== null)
            return $cached;


        if ($newsSearchResult = $this->newsScraper->searchForSchoolClosure($city)) 
            $closed = $this->aiValidator->validateAll($city, $newsSearchResult, validateOnce: true);
        else
            $closed = false;


        $this->closureStorage->set($city, $closed);
        return $closed;
    }
}
