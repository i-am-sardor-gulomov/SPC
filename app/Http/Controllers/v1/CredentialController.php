<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\Credential;
use App\Http\Requests\v1\StoreCredentialRequest;
use App\Http\Requests\v1\UpdateCredentialRequest;
use App\Models\v1\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

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
            return response("Bu loyiha uchun allaqachon ma'lumotlaringizni biriktirgansiz.", 422);
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
        if (is_null($credential)){return response("Ushbu loyihaga ma'lumotlaringizni biriktirmagansiz.", 404);}

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
        $user = auth()->user();
        $credential = Credential::where([
            'user_id'=>$user->id,
            'app_id'=>$app->id
        ])->first();

        if (is_null($credential)){
            return response("Ushbu loyihaga ma'lumotlaringizni biriktirmagansiz.", 404);
        }    

        if (!Hash::check($request->super_password, $user->password)){
            return response('Parol tasdiqlanmadi.', 400);
        }

        $credential->update([
            'login'=>$request->login??$credential->login,
            'password'=>Crypt::encryptString($request->password)??$credential->password,
        ]);

        return response("Ma'lumotlaringiz muvaffaqqiyatli yangilandi", 200);
    }

}
