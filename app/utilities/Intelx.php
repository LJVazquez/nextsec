<?php

namespace App\utilities;

use App\Models\IntelxData;
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

        $this->searchResults = $json['records'];
    }

    public function storeResults($owner)
    {
        // foreach ($this->searchResults as $searchResult) {
        //     $intelxData = IntelxData::firstOrCreate(
        //         ['systemid' => '49ce6ecf-d6bd-4489-8058-65bd8c874306'],
        //         [
        //             'systemid' => $searchResult['systemid'],
        //             'storageid' => $searchResult['storageid'],
        //             'instore' => $searchResult['instore'],
        //             'type' => $searchResult['type'],
        //             'media' => $searchResult['media'],
        //             'added' => $searchResult['added'],
        //             'name' => $searchResult['name'],
        //             'bucket' => $searchResult['bucket']
        //         ]
        //     );
        //     if ($owner->getTable() === "emails") {
        //         $intelxData->email_id = $owner->id;
        //     } else {
        //         $intelxData->domain_id = $owner->id;
        //     }
        //     $intelxData->save();
        // }

        foreach ($this->searchResults as $searchResult) {

            $dataCheck = IntelxData::where('systemid', $searchResult['systemid'])
                ->first();

            if ($dataCheck === null) {
                $intelxData = new IntelxData();
                $intelxData->systemid = $searchResult['systemid'];
                $intelxData->storageid = $searchResult['storageid'];
                $intelxData->instore = $searchResult['instore'];
                $intelxData->type = $searchResult['type'];
                $intelxData->media = $searchResult['media'];
                $intelxData->added = $searchResult['added'];
                $intelxData->name = $searchResult['name'];
                $intelxData->bucket = $searchResult['bucket'];
                if ($owner->getTable() === "emails") {
                    $intelxData->email_id = $owner->id;
                } else {
                    $intelxData->domain_id = $owner->id;
                }
                $intelxData->save();
            }
        }
    }

    public function getFile(IntelxData $file)
    {

        //? preview
        // $response = $this->client->request('GET', 'file/preview', [
        //     'query' => [
        //         'c' => 1,
        //         'm' => 1,
        //         'f' => 0, //if si es imagen
        //         'sid' => '83da36815decd67807b2e811251bcf83efb3cbfcc304312e7753af28f1b1c9239dd798666b6edd5e94068d1b4dbfde907bd04a03f88ce943c1f515bf1e74621b',
        //         'b' => 'pastes',
        //     ]
        // ]);

        // // ? view
        // $response = $this->client->request('GET', 'file/view', [
        //     'query' => [
        //         'f' => 0,
        //         'storageid' => $file->storageid,
        //         'bucket' => $file->bucket, //opcional
        //     ]
        // ]);

        // ? read
        $response = $this->client->request('GET', 'file/read', [
            'query' => [
                'type' => 0, //0 raw binary, 1 raw con disposicion opcional
                'storageid' => $file->storageid,
                'systemid' => $file->systemid,
                'bucket' => $file->bucket, //opcional
            ]
        ]);

        return $response->getBody()->getContents();
    }
}
