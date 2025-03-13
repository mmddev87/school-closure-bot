<?php

namespace App\RequestInputs;

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;
use Morilog\Jalali\Jalalian;
use OpenAI;
use WeStacks\TeleBot\Handlers\RequestInputHandler;

class AskCityName extends RequestInputHandler
{
    public function handle()
    {
        $cityInput = $this->update->message()->text;
        $this->acceptInput();

        $closures = $this->storage($this->bot)->get('closures') ?? [];

        if (isset($closures[jdate()->addDay()->toDateString()][$cityInput])) {
            $closed = $closures[jdate()->addDay()->toDateString()][$cityInput] == 'true' ? true : false;
        } else {

            $searchQuery = urlencode("تعطیلی مدارس $cityInput");

            $httpClient = new Client;
            $response = $httpClient->get("https://www.mashreghnews.ir/search?q=$searchQuery&dr=custom&df=1403%2F11%2F23&dt=1403%2F11%2F23&sort=date&pageSize=20", [
                'headers' => [
                    'User-Agent' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n',
                ]
            ]);

            $htmlString = (string) $response->getBody();
            libxml_use_internal_errors(true);

            $doc = new DOMDocument;
            $doc->loadHTML($htmlString);

            $xpath = new DOMXPath($doc);
            $descriptions = $xpath->evaluate('.//div[contains(concat(" ",normalize-space(@class)," ")," items ")]/ul/li[contains(concat(" ",normalize-space(@class)," ")," news ")]/div[contains(concat(" ",normalize-space(@class)," ")," desc ")]');

            $closed = false;

            foreach ($descriptions as $description) {
                $news = mb_convert_encoding($description->textContent, 'ISO-8859-1', 'UTF-8') . PHP_EOL;
                $closed = $closed || $this->closed($cityInput, $news);
            }

            $closures[jdate()->addDay()->toDateString()][$cityInput] = $closed;
            $this->storage($this->bot)->set('closures', $closures);
        }

        return $this->sendMessage([
            'text' => ($closed) ? "شهر $cityInput فردا تعطیل است." : "شهر $cityInput فردا تعطیل نیست."
        ]);
    }

    function closed($city, $news)
    {
        $yourApiKey = getenv('GROQ_API_KEY');
        $client = OpenAI::factory()
            ->withApiKey($yourApiKey)
            ->withBaseUri('https://api.groq.com/openai/v1') // default: api.openai.com/v1
            ->withHttpClient(new \GuzzleHttp\Client([])) // default: HTTP client found using PSR-18 HTTP Client Discovery
            ->make();

        $date = Jalalian::forge('today')->format('%A, %d %B %y');

        $result = $client->chat()->create([
            'model' => 'qwen-2.5-32b',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "In the input text, you are given a json file. In the parent object, the city key is the city or province in question and the content key is the content of the news.
You need to check whether the city given to you in city has been declared closed in the news text or not.
Also, the date of the news in which the school in the city in question was declared closed for tomorrow, i.e. $date
Make sure that the same city is closed and do not consider if other cities are declared closed.
Your answer should only be true or false and do not add prepositions."
                ],
                [
                    'role' => 'user',
                    'content' => json_encode([
                        'city' => $city,
                        'content' => $news
                    ])
                ]
            ]
        ]);

        return $result->choices[0]->message->content === 'true' ? true : false;
    }
}
