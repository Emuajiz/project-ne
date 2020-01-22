<?php

namespace App\Http\Controllers;

use App\User;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EventsController extends Controller
{
    //
    public function index(Request $request)
    {
        $offset = ($request->offset ? $request->offset : 0);
        $limit = ($request->limit ? $request->limit : 10);
        return response()->json(Event::with('user')->offset($offset)->limit($limit)->get());
        // return response()->json($request->all());
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => 'required',
            "start" => 'required|date_format:Y-m-d H:i:s',
            "end" => 'required|date_format:Y-m-d H:i:s',
            "category" => 'required',
            "location" => 'required',
            "phone" => 'required',
            "email" => 'required',
            "website" => 'required',
            "description" => 'required',
            "poster" => 'required',
            "video" => 'required',
        ]);
        $input = $request->all();
        $user = Auth::user();
        // $user = User::find(111);
        if($user) {
            $event = $user->events()->create($input);
            return response()->json($input);
        }
        else {
            return response()->json([
                "message" => "Unauthenticated."
            ], 401);
        }
        // return response()->json($user);
    }

    public function show(Event $event)
    {
        return response()->json([
            "event" => $event,
            "user" => User::find($event->user_id)->first()
            ]);
    }

}
