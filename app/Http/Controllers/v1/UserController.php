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
    public function index()
    {
        $user_id = auth()->user()->id;
        return User::where('id', '!=', $user_id)->get();
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
            'fullname'=>$request->fullname,
            'phone'=>$request->phone,
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

        $admin_check_password = $request->super_password;

        if ($admin_check_password and !Hash::check($admin_check_password, $admin->password)){
            return response("Admin paroli xato!", 422);
        }

        $user->update([
            'fullname'=>$request->fullname??$user->fullname,
            'phone'=>$request->phone??$user->phone,
            'username' => $request->username??$user->username,
            'password' => ($request->password)?Hash::make($request->password):$user->password,
            'status' => $request->status??$user->status
        ]);

        return $user;
    }

    public function profileUpdate(UpdateUserRequest $request){
        $user = auth()->user();
        $user_check_password = $request->super_password;

        if ($user_check_password and !Hash::check($user_check_password, $user->password)){
            return response("Joriy parol tasdiqlanmadi!", 422);
        }

        $user->update([
            'fullname'=>$request->fullname??$user->fullname,
            'phone'=>$request->phone??$user->phone,
            'username' => $request->username??$user->username,
            'password' => ($request->password)?Hash::make($request->password):$user->password,
        ]);

        return $user;
    }

    public function activenessUpdate(Request $request, User $user){
        $user->update(['is_active' => $request->is_active??$user->is_active]);
        return response('', 200);
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

        $admin_check_password = $request->validate(['super_password'=>'required|min:6'])['super_password'];

        if (!Hash::check($admin_check_password, $admin->password)){
            return response("Admin paroli xato!", 422);
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
            return response("Username yoki parol xato", 227);
        }

        $user = Auth::getprovider()->retrieveByCredentials($login);
        if (!$user->is_active){
            return response("Ushbu foydalanuvchi admin tomonidan faolsizlantirilgan.", 227);
        }
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
