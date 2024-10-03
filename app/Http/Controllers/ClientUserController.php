<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class ClientUserController extends Controller
{
    /*
    * Get user's info from authentication ticket.
    * @param {string}} 1 ROBLOSecurity
    */
    public static function getUserFromSecurity($robloSecurity) {
        if (!$robloSecurity) {
            return 'Warning: no roblosecurity';
        }

        $user = User::where('authentication_ticket', $robloSecurity)->first();

        if (!$user) {
            return 'No user';
        }

        return $user;
    }
}
