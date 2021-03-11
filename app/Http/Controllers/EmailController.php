<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Domain;
use App\Models\IntelxData;
use Illuminate\Http\Request;
use App\Http\Controllers\IntelxController;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function create()
    {
        $domains = Domain::where('user_id', Auth::id())->get();
        return view('email.create', ['domains' => $domains]);
    }

    public function store(Request $request)
    {
        $check = Email::where('name', $request->name)->first();
        if ($check && $check->user->id === Auth::id()) {
            return redirect('emails/create')->with('create-error', "$request->name ya se encuentra en su base de datos.");
        }

        $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'name' => 'required|email',
            'domain' => 'nullable|integer'
        ]);

        $email = new Email;
        if ($request->use_user) {
            $email->first_name = Auth::user()->name;
            $email->last_name = Auth::user()->last_name;
        } else {
            $email->first_name = $request->first_name;
            $email->last_name = $request->last_name;
        }
        $email->domain_id = $request->domain;
        $email->name = $request->name;
        $email->user_id = Auth::id();
        $email->save();

        return redirect('/')->with('email-update', "Se ha creado $request->name.");
    }

    public function show(Email $email)
    {
        $this->authorize('author', $email);

        $intelxdata = IntelxData::where('email_id', $email->id)
            ->orderBy('updated_at')
            ->get();

        return view('email.show', [
            'email' => $email,
            'intelxdata' => $intelxdata
        ]);
    }

    public function edit(Email $email)
    {
        $domains = Domain::where('user_id', Auth::id())->get();
        return view('email.edit', ['email' => $email, 'domains' => $domains]);
    }

    public function update(Request $request, Email $email)
    {
        $this->authorize('author', $email);

        $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'domain' => 'nullable|integer'
        ]);

        $email->first_name = $request->first_name;
        $email->last_name = $request->last_name;
        $email->domain_id = $request->domain;
        $email->save();
        return redirect('/')->with('email-update', "se ha actualizado $email->name.");
    }

    public function destroy(Email $email)
    {
        $this->authorize('author', $email);

        Email::destroy($email->id);

        return redirect('/')->with('email-update', "se ha eliminado $email->name.");
    }

    public function intelxSearch(Email $email)
    {
        $this->authorize('author', $email);

        $searchTerm = htmlspecialchars($email->name);
        $previousCount = IntelxData::where('email_id', $email->id)->count();

        $intelx = new IntelxController();
        $intelx->makeRequest($searchTerm);
        $intelx->getResults();
        $intelx->storeResults($email);
        $msg = $intelx->message;

        $newCount = IntelxData::where('email_id', $email->id)->count();
        $totalCount = $newCount - $previousCount;
        $msg['props'] = strval($totalCount);

        return redirect("/emails/$email->id")->with('intelx-search-msg', $msg);
    }
}
