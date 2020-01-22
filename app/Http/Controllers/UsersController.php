<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $request->all();
        return response()->json($user);
    }

    public function secret(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
