<?php

namespace Infrastructure\Services;

use Core\Interfaces\NewsScraperInterface;
use DOMDocument;
use DOMXPath;

class MashreghNewsScraper implements NewsScraperInterface
{
    public function __construct(
        private string $userAgent = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n'
    ) {}

    public function searchForSchoolClosure(string $city): array
    {
        $response = $this->sendRequest($city);
        
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($response);

        $xpath = new DOMXPath($doc);
        $descriptions = $xpath->evaluate('.//div[contains(concat(" ",normalize-space(@class)," ")," items ")]/ul/li[contains(concat(" ",normalize-space(@class)," ")," news ")]/div[contains(concat(" ",normalize-space(@class)," ")," desc ")]');

        $results = [];
        foreach ($descriptions as $description) {
            $results[] = $description->textContent;
        }

        return $results;
    }

    private function sendRequest(string $city): string {
        $searchQuery = urlencode("تعطیلی مدارس $city");
        $httpClient = new \GuzzleHttp\Client;

        return $httpClient->get("https://www.mashreghnews.ir/search?q=$searchQuery&dr=today&df=&dt=&sort=date&pageSize=20", [
            'headers' => [
                'User-Agent' => $this->userAgent,
            ]
        ])->getBody()->getContents();
    }
}
