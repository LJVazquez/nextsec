<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Domain;
use App\Models\IntelxData;
use Illuminate\Http\Request;
use App\utilities\Intelx;

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
        $email->name = $request->name;
        $email->domain_id = $request->domain;
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
            'domain' => $email->domain,
            'user' => $email->domain->user,
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

    public function search(Email $email)
    {
        $searchTerm = htmlspecialchars($email->name . '@' . $email->domain->name);
        $previousCount = IntelxData::where('email_id', $email->id)->count();


        $intelx = new Intelx();
        $intelx->makeRequest($searchTerm);
        $intelx->getResults();
        $intelx->storeResults($email);

        // $intel->getFile();

        $newCount = IntelxData::where('email_id', $email->id)->count();
        $totalCount = $newCount - $previousCount;

        return redirect("/emails/$email->id")->with('count', $totalCount);
    }

    public function getFile(IntelxData $file)
    {
        // $this->authorize('getFile', $file);

        $intelx = new Intelx();
        return $intelx->getFile($file);
    }
}
