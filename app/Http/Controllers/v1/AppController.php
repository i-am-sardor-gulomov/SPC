<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\App;
use App\Http\Requests\v1\StoreAppRequest;
use App\Http\Requests\v1\UpdateAppRequest;
use App\Http\Resources\v1\AppResource;
use App\Models\v1\Credential;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   $queryset = ($request->page=='admin' and $request->user()->tokenCan('admin'))?
            App::all():
            App::where(['is_active'=>true])->get();

        return AppResource::collection($queryset);
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

        return response(['data'=>(new AppResource($app)), 'message'=>'Muvaffaqqiyatli yaratildi.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\v1\App  $app
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, App $app)
    {
        if (!$request->user()->tokenCan('admin') and !$app->is_active){
            return response(['message'=>'Ushbu dastur admin tomonidan faolsizlantirilgan.']);
        }
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
        ]);

        return $app;
    }

    public function activenessUpdate(Request $request, App $app){
        $app->update(['is_active' => $request->is_active??$app->is_active]);
        return response('', 200);
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
