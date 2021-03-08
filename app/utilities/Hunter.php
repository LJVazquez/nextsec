<?php

namespace App\utilities;

use App\Models\Domain;
use App\Models\HunterDomainData;
use GuzzleHttp\Client;

class Hunter
{

    private $searchOptions;
    private $searchResults;
    private $client;

    public function __construct($optionalParameters = null)
    {
        if ($optionalParameters) $this->searchOptions = $optionalParameters;

        $this->client = new Client([
            'base_uri' => 'https://api.hunter.io',
            // 'timeout'  => 2.0,
        ]);
    }

    public function domainSearch($searchTerm)
    {
        do {
            $response = $this->client->request('GET', 'v2/domain-search', [
                'query' => [
                    'domain' => $searchTerm,
                    'api_key' => 'dcc032d6b9bce6f126d6bbd70a8fc5875e065d0a'
                ]
            ]);
        } while ($response->getStatusCode() !== 200);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->searchResults = $json['data']['emails'];
    }

    public function storeResults(Domain $domain)
    {
        foreach ($this->searchResults as $email) {

            $dataCheck = HunterDomainData::where('email', $email['value'])
                ->first();

            if ($dataCheck === null || $dataCheck->domain_id !== $domain->id) {

                $sourcesArr = array_map(function ($elem) {
                    return $elem['uri'];
                }, $email['sources']);
                $sources = implode('|', $sourcesArr);

                $hunterData = new HunterDomainData();
                $hunterData->email = $email['value'];
                $hunterData->first_name = $email['first_name'];
                $hunterData->last_name = $email['last_name'];
                $hunterData->type = $email['type'];
                $hunterData->sources = $sources;
                $hunterData->domain_id = $domain->id;
                $hunterData->save();
            }
        }
    }

    // public function getResults()
    // {
    //     do {
    //         $response = $this->client->request('GET', 'b', [
    //             'query' => [
    //                 'id' => $this->searchID
    //             ]
    //         ]);

    //         $json = json_decode($response->getBody()->getContents(), true);
    //     } while ($json['status'] !== 1);

    //     $this->searchResults = $json['records'];
    // }


    // public function storeResults($owner)
    // {
    //     foreach ($this->searchResults as $searchResult) {

    //         $dataCheck = IntelxData::where('systemid', $searchResult['systemid'])
    //             ->first();

    //         if ($dataCheck === null) {
    //             $intelxData = new IntelxData();
    //             $intelxData->systemid = $searchResult['systemid'];
    //             $intelxData->storageid = $searchResult['storageid'];
    //             $intelxData->instore = $searchResult['instore'];
    //             $intelxData->type = $searchResult['type'];
    //             $intelxData->media = $searchResult['media'];
    //             $intelxData->added = $searchResult['added'];
    //             $intelxData->name = $searchResult['name'];
    //             $intelxData->bucket = $searchResult['bucket'];
    //             if ($owner->getTable() === "emails") {
    //                 $intelxData->email_id = $owner->id;
    //             } else {
    //                 $intelxData->domain_id = $owner->id;
    //             }
    //             $intelxData->save();
    //         }
    //     }
    // }
}
