<?php

namespace App\utilities;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Email;
use App\Models\HunterData;
use App\Models\LastPersonChecked;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Hunter extends Controller
{

    private $searchOptions;
    private $searchResults;
    private $client;
    public $message = false;

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
            $dataCheck = HunterData::where('email', $email['value'])
                ->first();

            if ($dataCheck === null || $dataCheck->domain_id !== $domain->id) {

                $sourcesArr = array_map(function ($elem) {
                    return $elem['uri'];
                }, $email['sources']);

                $sources = json_encode($sourcesArr);

                $hunterData = new HunterData();
                $hunterData->email = $email['value'];
                $hunterData->first_name = $email['first_name'];
                $hunterData->last_name = $email['last_name'];
                $hunterData->verified = $email['verification']['status'];
                $hunterData->confidence = $email['confidence'];
                $hunterData->sources = $sources;
                $hunterData->domain_id = $domain->id;
                $hunterData->save();
            }
        }
    }

    public function personSearch($searchTerm, Request $request, $domainID)
    {
        do {
            $response = $this->client->request('GET', 'v2/email-finder', [
                'query' => [
                    'domain' => $searchTerm,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'api_key' => 'dcc032d6b9bce6f126d6bbd70a8fc5875e065d0a'
                ]
            ]);
        } while ($response->getStatusCode() !== 200);

        $json = json_decode($response->getBody()->getContents(), true);
        $personResults = $json['data'];

        $lastFromThisDomain = LastPersonChecked::where('domain_id', $domainID)->first();
        $person = $lastFromThisDomain ? $lastFromThisDomain : new LastPersonChecked();

        $sourcesArr = array_map(function ($elem) {
            return $elem['uri'];
        }, $personResults['sources']);
        $sources = json_encode($sourcesArr);

        $person->first_name = $personResults['first_name'];
        $person->last_name = $personResults['last_name'];
        $person->email = $personResults['email'];
        $person->verified = $personResults['verification']['status'];
        $person->confidence = $personResults['score'];
        $person->domain_id = $domainID;
        $person->sources = $sources;

        $person->save();
    }

    public function storePerson(Domain $domain)
    {
        $lastFromThisDomain = LastPersonChecked::where('domain_id', $domain->id)->first();
        $dataCheck = HunterData::where('email', $lastFromThisDomain['email'])
            ->first();

        if ($dataCheck === null || $dataCheck->domain_id !== $domain->id) {

            $hunterData = new HunterData();
            $hunterData->email = $lastFromThisDomain['email'];
            $hunterData->first_name = $lastFromThisDomain['first_name'];
            $hunterData->last_name = $lastFromThisDomain['last_name'];
            $hunterData->verified = $lastFromThisDomain['verified'];
            $hunterData->confidence = $lastFromThisDomain['confidence'];
            $hunterData->sources = $lastFromThisDomain['sources'];
            $hunterData->domain_id = $domain->id;
            $hunterData->save();
            $lastFromThisDomain->delete();
        } else {
            $this->message = 'El email ya existe en la colección de este dominio';
        }
    }

    public function destroy(HunterData $hunterData)
    {
        HunterData::destroy($hunterData->id);
        return redirect("/domains/$hunterData->domain_id")->with('hunter-delete-message', 'Resultado eliminado de la busqueda');
    }

    public function asociateEmail(HunterData $hunterData)
    {
        $dataCheck = Email::where('name', $hunterData->email)
            ->first();

        if ($dataCheck === null || $dataCheck->domain_id !== $hunterData->domain->id) {
            $email = new Email();
            $email->name = $hunterData['email'];
            $email->first_name = $hunterData['first_name'];
            $email->last_name = $hunterData['last_name'];
            $email->domain_id = $hunterData->domain_id;
            $email->user_id = Auth::id();
            $email->save();
            return redirect("/domains/$hunterData->domain_id")->with('hunter-asociate-message', 'success');
        } else {
            return redirect("/domains/$hunterData->domain_id")->with('hunter-asociate-message', 'fail');
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
