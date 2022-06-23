<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\Credential;
use App\Http\Requests\v1\StoreCredentialRequest;
use App\Http\Requests\v1\UpdateCredentialRequest;
use App\Models\v1\App;
use Illuminate\Support\Facades\Crypt;

class CredentialController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCredentialRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCredentialRequest $request, App $app)
    {
        $user_id = auth()->user()->id;
        if (Credential::where(['user_id'=>$user_id, 'app_id'=>$app->id])->exists()){
            abort(500, "Bu loyiha uchun ma'lumot kiritgansiz.");
        }

        $credential = Credential::create([
            'app_id'=>$app->id,
            'front_login'=>$request->front_login,
            'front_password'=>Crypt::encryptString($request->front_password),
            'back_login'=>$request->back_login,
            'back_password'=>Crypt::encryptString($request->back_password),
            'user_id'=>$user_id
        ]);

        return response("Ma'lumotlar muvaffaqqiyatli biriktirildi", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\v1\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function show(App $app)
    {
        $credential = Credential::where([
            'user_id'=>auth()->user()->id,
            'app_id'=>$app->id
        ])->first();
        if (is_null($credential)){abort(404, "Ushbu loyihaga ma'lumotlaringizni biriktirmagansiz.");}

        return $credential;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCredentialRequest  $request
     * @param  \App\Models\v1\Credential  $credential
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCredentialRequest $request, App $app)
    {
        $credential = Credential::where([
            'user_id'=>auth()->user()->id,
            'app_id'=>$app->id
        ])->first();

        if (is_null($credential)){
            abort(404, "Ushbu loyihaga ma'lumotlaringizni biriktirmagansiz.");
        }    

        if (!is_null($request->front_password) &&
            $request->front_password_old!=Crypt::decryptString($credential->front_password)){
            abort(400, 'Joriy parol tasdiqlanmadi.');
        }
        if (!is_null($request->back_password) &&
        $request->back_password_old!=Crypt::decryptString($credential->back_password)){
            abort(400, 'Joriy parol tasdiqlanmadi.');
        }

        $credential->update([
            'front_login'=>$request->front_login??$credential->front_login,
            'front_password'=>Crypt::encryptString($request->front_password)??$credential->front_password,
            'back_login'=>$request->back_login??$credential->back_login,
            'back_password'=>Crypt::encrypt($request->back_password)??$credential->back_password,
        ]);

        return response("Ma'lumotlaringiz muvaffaqqiyatli yangilandi", 200);
    }

}
