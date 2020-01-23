<?php

namespace App\Http\Controllers;

use App\User;
use App\OauthAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
Use Carbon\Carbon;

class UsersController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users|email',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'birthday' => 'required|date',
            // 'website' => 'required',
            'bio' => 'required',
        ]);
        
        $input = $request->all();
        $input['first_name'][0] = strtoupper($input['first_name'][0]);
        $input['last_name'][0] = strtoupper($input['last_name'][0]);
        $input['password'] = bcrypt($input['password']);
        $input['birthday'] = Carbon::parse($input['birthday'])->toDateString();
        $user = User::create($input);

        return response()->json($user);
        // return response()->json($input);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = Auth::user();
            $success['token'] =  $user->createToken('login')->accessToken;
            return response()->json([
                'success' => $success,
                'user' => $user
            ]);
            // return response()->json($user);
        }
        else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();
        if(isset($input['first_name']))
            $input['first_name'][0] = strtoupper($input['first_name'][0]);
        if(isset($input['last_name']))
            $input['last_name'][0] = strtoupper($input['last_name'][0]);
        if(isset($input['birthday']))
            $input['birthday'] = Carbon::parse($input['birthday'])->toDateString();
        $user->update($input);
        return response()->json($user);
    }

    public function secret(Request $request)
    {
        # code...
        $user = Auth::user();
        return response()->json([
            "user" => $user,
            "token" => $user->token()
        ]);
    }
    
    public function password_change(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            "old_password" => "required",
            "new_password" => "required",
            "password_confirmation" => "required|same:new_password",
        ]);

        $credentials = [
            "email" => $user->email,
            "password" => $request->old_password,
        ];

        if (Hash::check($request->old_password, $user->password)) {
            // Authentication passed...
            $user->password = Hash::make($request->new_password);
            $user->save();
            $user->AauthAcessToken()->delete();
            return response()->json([$user, 'sukses']);
        }

        return response()->json(
            [
                'user' => $user,
                'request' => $request->all(),
                'check' => Hash::check($request->old_password, $user->password)
            ]);
    }

    public function logout()
    {
        # code...
        $token = OauthAccessToken::find(Auth::user()->token()->id);
        if($token)
            $token->delete();
        return response()->json([
            "message" => "sukses logout"
        ]);
    }
}
