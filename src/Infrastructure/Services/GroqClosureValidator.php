<?php

namespace Infrastructure\Services;

use Core\Interfaces\AIClosureValidatorInterface;
use LucianoTonet\GroqPHP\Groq;
use Morilog\Jalali\Jalalian;

class GroqClosureValidator implements AIClosureValidatorInterface
{
    private $groq;
    private string $systemPrompt;
    private string $languageModel;

    public function __construct(string $apiKey, string $languageModel = 'qwen-2.5-32b')
    {
        $this->groq = new Groq($apiKey, ['baseUrl' => 'https://api.groq.com/openai/v1']);

        $this->languageModel = $languageModel;
        $this->setSystemPrompt();
    }

    private function setSystemPrompt()
    {
        $date = Jalalian::forge('today')->format('%A, %d %B %y');

        $this->systemPrompt = <<<EOL
            In the input text, you are given a json file. In the parent object, the city key is the city or province in question and the content key is the content of the news.
            You need to check whether the city given to you in city has been declared closed in the news text or not.
            Also, the date of the news in which the school in the city in question was declared closed for tomorrow, i.e. $date
            Make sure that the same city is closed and do not consider if other cities are declared closed.
            Your answer should only be true or false and do not add prepositions.
            EOL;
    }

    public function validate(string $targetCity, string $newsContent): bool
    {
        $response = $this->groq->chat()->completions()->create([
            'model' => $this->languageModel,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $this->systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => json_encode([
                        'city' => $targetCity,
                        'content' => $newsContent
                    ])
                ]
            ],
            'temperature' => 0.6
        ]);

        return $response['choices'][0]['message']['content'] == 'true';
    }

    /** 
     * @param string $targetCity
     * @param string[] $bunchOfNewsContent */
    public function validateAll(string $targetCity, array $bunchOfNewsContent, bool $validateOnce = false): bool
    {
        if ($validateOnce) {
            return $this->validate($targetCity, implode('-----------', $bunchOfNewsContent));
        }

        foreach ($bunchOfNewsContent as $newsContent) {
            if ($this->validate($targetCity, $newsContent)) {
                return true;
            }
        }

        return false;
    }
}
