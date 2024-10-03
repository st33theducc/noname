<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use App\Models\Bodycolors;
use Illuminate\Support\Str;
use App\Models\Invites;
use App\Models\Owned;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\RenderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^[a-zA-Z0-9]+$/',], // for some reason laravel didn't make the name unique by default
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'invite_key' => ['required', 'string'],
        ]);

        $invitationKey = Invites::where('key', $request->input('invite_key'))
            ->where('used', false)
            ->first();

        if (!$invitationKey) {
            return back()->withErrors(['invite_key' => 'Invalid or already used invite key.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'authentication_ticket' => Str::uuid(),
        ]);

        $invitationKey->update(['used' => true]);

        event(new Registered($user));

        Auth::login($user);
        
        Owned::create([
            'userId' => Auth::id(),
            'itemId' => 3,
            'wearing' => 1,
        ]);

        Owned::create([
            'userId' => Auth::id(),
            'itemId' => 73,
            'wearing' => 1,
        ]);

        $defaultBodyColors = [
            'userId' => Auth::id(),
            'head' => 1,
            'torso' => 21,
            'larm' => 1,
            'rarm' => 1,
            'lleg' => 42,
            'rleg' => 42,
        ];
        

        Bodycolors::create($defaultBodyColors);

        RenderController::full(Auth::id());

        return redirect(route('app.home', absolute: false))->with('success', 'Successfully signed up. Welcome to NONAME!');
    }
}
