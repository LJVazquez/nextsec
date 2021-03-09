<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Domain;
use App\Models\IntelxData;
use Illuminate\Http\Request;
use App\utilities\Intelx;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('email.index', ['emails' => Email::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('email.create', ['domains' => Domain::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = new Email;
        if ($request->use_user) {
            $email->first_name = Auth::user()->name;
            $email->last_name = Auth::user()->last_name;
        } else {
            $email->first_name = $request->first_name;
            $email->last_name = $request->last_name;
        }
        if ($request->domain !== 'none') $email->domain_id = $request->domain;
        $email->name = $request->name;
        $email->user_id = Auth::id();
        $email->save();

        return redirect('/emails');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function show(Email $email)
    {
        $intelxdata = IntelxData::where('email_id', $email->id)
            ->orderBy('updated_at')
            ->get();

        return view('email.show', [
            'email' => $email,
            'intelxdata' => $intelxdata
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function edit(Email $email)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Email $email)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Email  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Email $email)
    {
        $this->authorize('delete', $email);

        Email::destroy($email->id);

        return redirect('/emails')->with('message', 'Email eliminado');
    }

    public function intelxSearch(Email $email)
    {
        $searchTerm = htmlspecialchars($email->name);
        $previousCount = IntelxData::where('email_id', $email->id)->count();

        $intelx = new Intelx();
        $intelx->makeRequest($searchTerm);
        $intelx->getResults();
        $intelx->storeResults($email);

        $newCount = IntelxData::where('email_id', $email->id)->count();
        $totalCount = $newCount - $previousCount;

        if ($totalCount <= 0) {
            return redirect("/emails/$email->id")->with('count', 'Sin resultados nuevos');
        } else {
            return redirect("/emails/$email->id")->with('count', "$totalCount resultados nuevos.");
        }
    }
}
