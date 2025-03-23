<?php

use Core\Interfaces\{AIClosureValidatorInterface, NewsScraperInterface, StorageInterface};
use Infrastructure\Services\{ClosureStorage, GroqClosureValidator, MashreghNewsScraper};
use Pimple\{
    Container,
    Psr11\Container as Psr11Container
};


$pimpleContainer = new Container();

$pimpleContainer[AIClosureValidatorInterface::class] =
    fn($c) => new GroqClosureValidator(getenv('GROQ_API_KEY'));
    
$pimpleContainer[NewsScraperInterface::class] =
    fn($c) => new MashreghNewsScraper();
    
$pimpleContainer[StorageInterface::class] =
    fn($c) => new ClosureStorage(BASE_PATH . '/storage');



return new Psr11Container($pimpleContainer);
