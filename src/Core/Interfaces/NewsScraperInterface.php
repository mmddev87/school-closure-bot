<?php

namespace Core\Interfaces;

interface NewsScraperInterface
{

    /** @return string[] */
    function searchForSchoolClosure(string $city): array;
}
