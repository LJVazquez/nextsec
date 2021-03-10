<?php

namespace App\Http\Controllers;

use App\Models\IntelxData;
use Exception;
use GuzzleHttp\Client;

class IntelxController
{
    private $searchID;
    private $searchResults;
    private $client;
    public $message = ['status' => '', 'msg' => '', 'props' => ''];

    public function __construct()
    {
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
        try {
            do {
                $response = $this->client->request('POST', 'intelligent/search', [
                    'json' => [
                        'term' => $searchTerm
                    ]
                ]);
                $json = json_decode($response->getBody()->getContents(), true);
            } while ($json['status'] !== 0);
        } catch (Exception $e) {
            $this->message = ['status' => 'fail', 'msg' => 'Error con el dominio. Intente mas tarde'];
            return;
        }
        $this->message = ['status' => 'success', 'msg' => 'Nuevos resultados encontrados: '];
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
        // ? preview
        $response = $this->client->request('GET', 'file/preview', [
            'query' => [
                'c' =>  $file->type,
                'm' =>  $file->media,
                'f' => 0, //if si es imagen
                'sid' => $file->storageid,
                'b' => $file->bucket,
            ]
        ]);

        // // ? view
        // $response = $this->client->request('GET', 'file/view', [
        //     'query' => [
        //         'f' => 0,
        //         'storageid' => $file->storageid,
        //         'bucket' => $file->bucket, //opcional
        //     ]
        // ]);

        // // ? read
        // $response = $this->client->request('GET', 'file/read', [
        //     'query' => [
        //         'type' => 0, //0 raw binary, 1 raw con disposicion opcional
        //         'storageid' => $file->storageid,
        //         'systemid' => $file->systemid,
        //         'bucket' => $file->bucket, //opcional
        //     ]
        // ]);

        return $response->getBody()->getContents();
    }
}
