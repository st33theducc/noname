<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Asset;
use App\Models\ServerPlayers;
use App\Models\ServerJobs;

class GamesController extends Controller
{
    /*
        Peepee
    */

    public function index() {
        return view('places');
    }

    public function getGames() {
        $games = Asset::where('type', 'place')
        ->where('banned', 0)
        ->where('under_review', 0)
        ->with('user')
        ->orderBy('playing', 'DESC')
        ->orderBy('visits', 'DESC')
        ->paginate(25);

        return response()->json($games);
    }

    public function show($id) {

        $game = Asset::where('type', 'place')
            ->where('banned', 0)
            ->where('under_review', 0)
            ->with('user')
            ->where('id', $id)
            ->first();
    
        $servers = ServerJobs::where('placeId', $id)->get();
    
        $serversWithPlayers = [];
    
        foreach ($servers as $server) {
    
            $serverPlayers = ServerPlayers::where('jobId', $server->jobId)->get();
    
            $serversWithPlayers[] = [
                'server' => $server,
                'serverPlayers' => $serverPlayers, 
            ];
        }
    
        if (!$game) {
            return abort(404);
        }
    
        return view('view.place', compact('game', 'serversWithPlayers'));
    }

    public function search(Request $request)
    {
    $search = $request->input('search');
    $games = Asset::where('type', 'place')
    ->where('name', 'like', "%$search%")
    ->where('banned', 0)
    ->where('under_review', 0)
    ->with('user')
    ->orderBy('playing', 'DESC')
    ->paginate(8);

    return view('places', compact('games', 'search'));

    }

    public function getGearEnabled($id) {
        if (!isset($id)) {
            return abort(403);
        }

        $game = Asset::where('id', $id)
        ->where('type', 'place')
        ->first();

        if (!$game) {
            return abort(404);
        }

        return response($game->gears_enabled)->header('Content-Type', 'text/plain');
    }
}
