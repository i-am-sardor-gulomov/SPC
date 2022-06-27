<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\App;
use App\Http\Requests\v1\StoreAppRequest;
use App\Http\Requests\v1\UpdateAppRequest;
use App\Http\Resources\v1\AppResource;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AppResource::collection(App::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAppRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppRequest $request)
    {
        $app = App::create($request->all());

        return response($app, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\v1\App  $app
     * @return \Illuminate\Http\Response
     */
    public function show(App $app)
    {
        return new AppResource($app);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAppRequest  $request
     * @param  \App\Models\v1\App  $app
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAppRequest $request, App $app)
    {
        $app->update([
            'name' => $request->name??$app->name,
            'description' => $request->description??$app->description,
            'url' => $request->url??$app->url,
            'icon' => $request->icon??$app->icon,
            'IP' => $request->IP??$app->IP,
            'port' => $request->port??$app->port,
            'grant_type' => $request->grant_type??$app->grant_type,
            'client_id' => $request->client_id??$app->client_id,
            'client_secret' => $request->client_secret??$app->client_secret,
        ]);

        return $app;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\v1\App  $app
     * @return \Illuminate\Http\Response
     */
    public function destroy(App $app)
    {
        $name = $app->name;
        $app->delete();

        return response(['name'=>$name], 204);
    }
}
