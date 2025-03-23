<?php

namespace Core\Interfaces;

interface AIClosureValidatorInterface
{
    function validate(string $targetCity, string $newsContent): bool;
    
    /** 
     * @param string $targetCity
     * @param string[] $bunchOfNewsContent */
    public function validateAll(string $targetCity, array $bunchOfNewsContent, bool $validateOnce = false): bool;
}
