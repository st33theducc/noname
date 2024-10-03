<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public static function Sign($script)
    {
    $PrivateKey = resource_path('roblox\key.pem');
    $signature = "";
    openssl_sign($script, $signature, file_get_contents($PrivateKey), OPENSSL_ALGO_SHA1);

    return base64_encode($signature);
    }
}
