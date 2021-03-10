<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\hunterData;
use App\Models\IntelxData;
use App\Models\LastPersonChecked;
use App\Http\Controllers\HunterController;
use App\Http\Controllers\IntelxController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function create()
    {
        return view('domain.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $check = Domain::where('name', $request->name)->first();
        if ($check && $check->user->id === Auth::id()) {
            return redirect('domains/create')->with('create-error', "$request->name ya se encuentra en su base de datos.");
        }

        $domain = new Domain;
        $domain->name = $request->name;
        $domain->user_id = Auth::id();
        $domain->save();
        return redirect('/');
    }

    public function show(Domain $domain)
    {
        $this->authorize('author', $domain);

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

    public function edit(Domain $domain)
    {
        return view('domain.edit', ['domain' => $domain]);
    }

    public function update(Request $request, Domain $domain)
    {
        $this->authorize('author', $domain);

        $check = Domain::where('name', $request->name)->first();
        if ($check && $check->user->id === Auth::id()) {
            return redirect("domains/$domain->id/edit")->with('update-error', "$request->name ya se encuentra en su base de datos.");
        }

        $domain->name = $request->name;
        $domain->save();
        return redirect('/')->with('domain-update', "$domain->name actualizado");
    }

    public function destroy(Domain $domain)
    {
        $this->authorize('author', $domain);

        Domain::destroy($domain->id);

        return redirect('/')->with('domain-update', "$domain->name eliminado.");
    }

    public function intelxSearch(Domain $domain)
    {
        $this->authorize('author', $domain);
        $searchTerm = htmlspecialchars($domain->name);
        $previousCount = IntelxData::where('domain_id', $domain->id)->count();

        $intelx = new IntelxController();
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
        $this->authorize('author', $domain);

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
        $this->authorize('author', $domain);

        $hunter = new HunterController();
        $hunter->personSearch($domain->name, $request, $domain->id);

        return redirect("/domains/$domain->id")->with('search-person', $hunter->message);
    }

    public function hunterSavePerson(Domain $domain)
    {
        $this->authorize('author', $domain);

        $hunter = new HunterController();
        $hunter->storePerson($domain);
        return redirect("/domains/$domain->id")->with('person-message', $hunter->message);
    }
}
