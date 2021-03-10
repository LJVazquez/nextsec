<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\hunterData;
use App\Models\IntelxData;
use App\Models\LastPersonChecked;
use App\Http\Controllers\HunterController;
use App\utilities\Intelx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('domain.index', ['domains' => Domain::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('domain.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $domain = new Domain;
        $domain->name = $request->name;
        $domain->user_id = Auth::id();
        $domain->save();
        return redirect('/domains');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        $intelxData = $domain->intelx->sortBy('updated_at');
        $hunterData = $domain->hunterDomains->sortBy('updated_at');
        $person = LastPersonChecked::where('domain_id', $domain->id)->first();

        return view('domain.show', [
            'domain' => $domain,
            'emails' => $domain->emails,
            'user' => $domain->user,
            'intelxdata' => $intelxData,
            'hunterData' => $hunterData,
            'person' => $person
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        $this->authorize('delete', $domain);

        Domain::destroy($domain->id);

        return redirect('/domains')->with('message', 'Dominio eliminado');
    }

    public function intelxSearch(Domain $domain)
    {
        $searchTerm = htmlspecialchars($domain->name);
        $previousCount = IntelxData::where('domain_id', $domain->id)->count();

        $intelx = new Intelx();
        $intelx->makeRequest($searchTerm);
        $intelx->getResults();
        $intelx->storeResults($domain);
        $msg = $intelx->message;

        $newCount = IntelxData::where('domain_id', $domain->id)->count();
        $totalCount = $newCount - $previousCount;

        $msg['props'] = strval($totalCount);

        return redirect("/domains/$domain->id")->with('intelx-search-msg', $msg);
    }

    public function hunterDomainSearch(Domain $domain)
    {

        $previousCount = hunterData::where('domain_id', $domain->id)->count();

        $hunter = new HunterController();
        $hunter->domainSearch($domain->name);
        $hunter->storeResults($domain);

        $newCount = hunterData::where('domain_id', $domain->id)->count();

        $totalCount = $newCount - $previousCount;

        if ($totalCount <= 0) {
            return redirect("/domains/$domain->id")->with('domain-count', "Sin resultados nuevos.");;
        } else {
            return redirect("/domains/$domain->id")->with('domain-count', "$totalCount resultados nuevos.");
        }
    }

    public function hunterPersonSearch(Domain $domain, Request $request)
    {
        $hunter = new HunterController();
        $hunter->personSearch($domain->name, $request, $domain->id);

        return redirect("/domains/$domain->id")->with('search-person', $hunter->message);
    }

    public function hunterSavePerson(Domain $domain)
    {
        $hunter = new HunterController();
        $hunter->storePerson($domain);
        return redirect("/domains/$domain->id")->with('person-message', $hunter->message);
    }
}
