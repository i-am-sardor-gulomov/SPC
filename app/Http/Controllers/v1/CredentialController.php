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
            'login'=>$request->login,
            'password'=>Crypt::encryptstring($request->password),
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

        if ($request->password_old!=Crypt::decryptString($credential->password)){
            abort(400, 'Joriy parol tasdiqlanmadi.');
        }

        $credential->update([
            'login'=>$request->login??$credential->login,
            'password'=>Crypt::encryptString($request->password)??$credential->password,
        ]);

        return response("Ma'lumotlaringiz muvaffaqqiyatli yangilandi", 200);
    }

}
