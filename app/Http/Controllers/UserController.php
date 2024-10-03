<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Asset;
use App\Models\UserBadges;
use App\Models\Owned;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function view($id) {
        $user = User::where('id', $id)
        ->first();

        if (!$user) {
            return abort(404);
        }

        $assets = Asset::where('creator_id', $user->id)
        ->where('banned', 0)
        ->where('under_review', 0)
        ->where('type', 'place')
        ->limit(5)
        ->get();

        $owned = Owned::where('userId', $user->id)
        ->where('wearing', 1)
        ->with('asset')
        ->get();

        $userbadges = UserBadges::where('userId', $user->id)->get();

        return view('view.user', compact('user', 'assets', 'userbadges', 'owned'));
    }

    public function home() {
        $popular = Asset::where('banned', 0)
        ->orderBy('playing', 'DESC')
        ->orderBy('visits', 'DESC')
        ->limit(4)->where('type', 'place')
        ->with('user')
        ->get();

        return view('dashboard', compact('popular'));
    }

    public function change_theme(Request $request) {
        $type = (INT) $request->type;
        $allowed = [
            0,
            1,
            2,
            3,
        ];

        if (!isset($type) || !in_array($type, $allowed)) {
            return abort(403);
        }

        Auth::user()->theme = $type;
        Auth::user()->save();

        return redirect()->back();
    }

}
