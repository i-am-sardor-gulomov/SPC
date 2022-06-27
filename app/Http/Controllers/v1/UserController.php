<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\v1\StoreUserRequest;
use App\Http\Requests\v1\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan('admin')){
        return User::all();
        }
        abort(403, "Bu so'rov faqat adminlar uchun");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\v1\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $password_hashed = Hash::make($request['password']);
        $user = User::create([
            'username'=>$request->username,
            'password'=>$password_hashed,
            'status'=>$request->status??'developer'
        ]);

        return response($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\v1\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    public function profileShow(){
        $user = auth()->user();
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\v1\UpdateUserRequest  $request
     * @param  \App\Models\v1\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $admin = auth()->user();

        $admin_test_password = $request->validate(['super_password'=>'required|min:6'])['super_password'];

        if (!Hash::check($admin_test_password, $admin->password)){
            abort(227, "Admin paroli xato!");
        }

        $user->update([
            'username' => $request->username??$user->status,
            'password' => $request->password??$user->status,
            'status' => $request->status??$user->status
        ]);

        return $user;
    }

    public function profileUpdate(UpdateUserRequest $request){
        $user = auth()->user();
        //add some logic
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\v1\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        $admin = auth()->user();

        $admin_test_password = $request->validate(['admin_password'=>'required|min:6'])['admin_password'];

        if (!Hash::check($admin_test_password, $admin->password)){
            abort(227, "Admin paroli xato!");
        }

        $username = $user->username;
        $user->delete();

        return response(['username'=>$username], 204);
    }

    public function login(Request $request){
        $login = $request->validate([
            'username'=>'required|exists:users,username',
            'password'=>'required|min:6'
        ]);

        if (!Auth::attempt($login)){
            abort(227, "Login yoki parol xato");
        }

        $user = Auth::getprovider()->retrieveByCredentials($login);
        $accessToken = $user->createToken('AccessToken', [$user->status])->accessToken;

        return response()->json(['user'=>$user, 'token'=>$accessToken]); 
    }

    public function logout(Request $request)
    {
        $tokenID = $request->user()->token()->id;
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($tokenID);
        return response('', 204);
    }

    
}
