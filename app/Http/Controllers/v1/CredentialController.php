<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\Credential;
use App\Http\Requests\StoreCredentialRequest;
use App\Http\Requests\UpdateCredentialRequest;

class CredentialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCredentialRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCredentialRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\v1\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function show(Credential $credential)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCredentialRequest  $request
     * @param  \App\Models\v1\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCredentialRequest $request, Credential $credential)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\v1\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credential $credential)
    {
        //
    }
}
