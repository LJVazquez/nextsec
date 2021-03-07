<?php

namespace App\utilities;

use GuzzleHttp\Client;

class Intelx
{

    private $searchOptions;
    private $searchID;
    private $searchResults;
    private $client;

    public function __construct($optionalParameters = null)
    {
        if ($optionalParameters) $this->searchOptions = $optionalParameters;

        $this->client = new Client([
            'base_uri' => 'https://2.intelx.io',
            // 'timeout'  => 2.0,
            'headers' => [
                'x-key' => '286e33d3-a161-4ceb-8160-7d759bbd78bc'
            ]
        ]);
    }

    public function makeRequest($searchTerm)
    {
        do {
            $response = $this->client->request('POST', 'intelligent/search', [
                'json' => [
                    'term' => $searchTerm
                ]
            ]);
            $json = json_decode($response->getBody()->getContents(), true);
        } while ($json['status'] !== 0);

        $this->searchID = $json['id'];
    }

    public function getResults()
    {
        do {
            $response = $this->client->request('GET', 'intelligent/search/result', [
                'query' => [
                    'id' => $this->searchID
                ]
            ]);

            $json = json_decode($response->getBody()->getContents(), true);
        } while ($json['status'] !== 1);

        $this->searchResults = $json;
        dd($this->searchResults);
    }
}
