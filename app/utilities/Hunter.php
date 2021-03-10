<?php

namespace App\utilities;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Email;
use App\Models\HunterData;
use App\Models\LastPersonChecked;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

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
        try {
            do {
                $response = $this->client->request('GET', 'v2/domain-search', [
                    'query' => [
                        'domain' => $searchTerm,
                        'api_key' => 'dcc032d6b9bce6f126d6bbd70a8fc5875e065d0a'
                    ]
                ]);
            } while ($response->getStatusCode() !== 200);
        } catch (Exception $e) {
            $this->message = ['fail', 'Se encontraron problemas con el dominio o los datos ingresados'];
        }
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

        try {
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
        } catch (Exception $e) {
            $this->message = ['fail', 'Se encontraron problemas con el dominio o los datos ingresados'];
            return;
        }

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
        $this->message = ['success', 'Persona encontrada.'];
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
}
