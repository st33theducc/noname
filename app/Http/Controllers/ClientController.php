<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Http\Controllers\SignatureController;
use App\Http\Controllers\ClientUserController;
use App\Http\Controllers\GameserverController;

use App\Models\Asset;
use App\Models\GameTickets;
use App\Models\User;

class ClientController extends Controller
{
    function asset(Request $request) {
        ob_start();
    
        $id = $request->query('id');
        $version = $request->query('version');
        $rcc = $request->query('rcc') ?? false;
    
        if (!$id) {
            abort(404);
        }
    
        $asset = Asset::find($id);
        $file = storage_path("/app/public/asset/" . $id);
    
        if (!$asset || $asset->banned || $asset->under_review) {
    
            if (!file_exists($file)) {
                $url = "https://assetdelivery.roblox.com/v1/asset/?id=" . $id;
                $url .= isset($version) ? "&version=" . $version : '';
                return redirect($url);
            }
    
            $content = file_get_contents($file);
            $finalContent = ($rcc === "true") ? str_replace('Hat', 'Accessory', $content) : $content;
    
            return response($finalContent)
                ->header('Content-Type', 'text/plain')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
        }
    
        if ($asset->type === "place") {
            $accessKey = $request->AccessKey;
    
            if ($accessKey !== "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn") {
                abort(403, "Access key is required to access place assets.");
            }
    
            $placePath = storage_path("/app/public/places/" . $id);
            if (file_exists($placePath)) {
                return response(file_get_contents($placePath))
                    ->header('Content-Type', 'text/plain')
                    ->header('Cache-Control', 'no-cache')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '-1')
                    ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
            }
    
            return response('Place does not exist', 404)
                ->header('Content-Type', 'text/plain');
        }
    
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $finalContent = ($rcc === "true") ? str_replace('Hat', 'Accessory', $content) : $content;
    
            return response($finalContent)
                ->header('Content-Type', 'text/plain')
                ->header('Cache-Control', 'no-cache')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '-1')
                ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');
        }
    
        $url = "https://assetdelivery.roblox.com/v1/asset/?id=" . $id;
        $url .= isset($version) ? "&version=" . $version : '';
        return redirect($url);
    
        ob_end_flush();
    }

    function visit_2016() {

        $VisitScript = file_get_contents(resource_path('/roblox/visit.lua'));

        $NewVisit = str_replace('{{time}}', time(), $VisitScript);

        $sig = SignatureController::sign("\r\n" . $NewVisit);
        $final = "--rbxsig%" . $sig . "%\r\n" . $NewVisit;
        return response($final)->header('Content-Type', 'text/plain');        
    }

    function getCurrentUser(Request $request) {
        $cookie = $request->cookie('ROBLOSECURITY');

        if (!$cookie) {
            return '-1';
        }

        $usr = ClientUserController::getUserFromSecurity($cookie);

        return $usr->id;

    }

    function LuaWebServiceHandleSocial(Request $request) {

        $Method = $request->query('method');
        $PlayerId = $request->query('playerid');
        $GroupId = $request->query('groupid');

        $adminUserIds = [
            0,
            1,
            2,
            8,
            23,
            24,
        ];

        if (isset($Method) and isset($PlayerId) and isset($GroupId)) {

            if ($Method == "GetGroupRank") {
                if (in_array($PlayerId, $adminUserIds) and $GroupId == 2 or $GroupId == 1200769) {
                    $response = '<Value Type="integer">255</Value>';
                } else {
                    $response =  '<Value Type="integer">0</Value>';
                }
            }

            if ($Method == "IsInGroup") {
                if (in_array($PlayerId, $adminUserIds) and $GroupId == 1200769) {
                    $response = '<Value Type="boolean">true</Value>';
                } else {
                    $response = '<Value Type="boolean">false</Value>';
                }
            }

            if ($Method == "AreFriends") {
                $response = '<Value Type="boolean">true</Value>';
            }

        } else {
            $response = 'Invalid request.';
        }

        return response($response)->header('Content-Type', 'application/xml');

    }

    function join_16(Request $request) {
        $token = $request->token;

        $ticket = GameTickets::where('token', $token)->first();

        if (!$ticket) {
            dd($ticket);
        }

        $user = User::where('id', $ticket->userId)->where('banned', 0)->first();

        if (!$user) {
            return abort(404);
        }

        $game = Asset::where('id', $ticket->placeId)->first();
        $asset = Asset::where('id', $ticket->placeId)->with('user')->first();

        if (!$game || $asset) {
            
        }

        // start serverrrr
        $server = Gameservercontroller::startGameserver($ticket->placeId); // hope to God this starts in time

        $ticket->port = $server['port'];
        $ticket->save();

        $CharacterAppearance = "";
        $joinscript = [
            "ClientPort" => 0,
            "MachineAddress" => "26.47.103.136",
            "ServerPort" => $server['port'],
            "PingUrl" => "",
            "PingInterval" => 20,
            "UserName" => $user->name,
            "GameChatType" => "AllUsers",
            "SeleniumTestMode" => false,
            "UserId" => $user->id,
            "SuperSafeChat" => false,
            "CharacterAppearance" => "http://www.noname.xyz/char/" . $user->id . "?t=" . time(),
            "ClientTicket" => "",
            "GameId" => $ticket->placeId,
            "PlaceId" => $ticket->placeId,
            "MeasurementUrl" => "", 
            "WaitingForCharacterGuid" => "26eb3e21-aa80-475b-a777-b43c3ea5f7d2",
            "BaseUrl" => "http://www.noname.xyz/",
            "ChatStyle" => "ClassicAndBubble",
            "VendorId" => "0",
            "ScreenShotInfo" => "",
            "VideoInfo" => "",
            "CreatorId" => $asset->creator_id,
            "CreatorTypeEnum" => "User",
            "MembershipType" => "None",
            "AccountAge" => "365",
            "CookieStoreFirstTimePlayKey" => "rbx_evt_ftp",
            "CookieStoreFiveMinutePlayKey" => "rbx_evt_fmp",
            "CookieStoreEnabled" => true,
            "IsRobloxPlace" => true,
            "GenerateTeleportJoin" => false,
            "IsUnknownOrUnder13" => false,
            "SessionId" => "39412c34-2f9b-436f-b19d-b8db90c2e186|00000000-0000-0000-0000-000000000000|0|" . \Request::ip() . "|8|2021-03-03T17:04:47+01:00|0|null|null",
            "DataCenterId" => 0,
            "UniverseId" => 210851291,
            "BrowserTrackerId" => 0,
            "UsePortraitMode" => false,
            "FollowUserId" => 0,
            "characterAppearanceId" => 1
        ];
        $data = json_encode($joinscript, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
        $signature = SignatureController::Sign("\r\n" . $data);
        $final = "--rbxsig%". $signature . "%\r\n" . $data;

        return response($final)->header('Content-Type', 'text/plain');
    }

    function placelauncher(Request $request) {
        $token = $request->token;

        $ticket = GameTickets::where('token', $token)->first();

        if (!$ticket) {
            return abort(403);
        }

        $user = User::where('id', $ticket->userId)->where('banned', 0)->first();

        if (!$user) {
            //return abort(404);
        }

        $game = Asset::where('id', $ticket->placeId)->first();
        $asset = Asset::where('id', $ticket->placeId)->with('user')->first();

        if (!$game || $asset) {
            
        }

        if ($asset->playing >= $asset->max_players) {
            $status = 6;
        } else {

        }
        // start serverrrr
        $server = Gameservercontroller::startGameserver($ticket->placeId);

        $status = $server["status"];

        $jobid = $server["jobid"];

        $args = "&jobid=" . $jobid;
        
        $placelauncherRaw = [
            "jobId"=> $jobid,
            "status"=> $status,
            "joinScriptUrl"=> "http://noname.xyz/game/join.ashx?token=" . $token,
            "authenticationUrl"=> "http://noname.xyz/",
            'authenticationTicket' => 'sex',
            'message' => null,
        ];

        $data = json_encode($placelauncherRaw, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);

        //echo($data);
        return response($data)->header('Content-Type', 'text/plain');
    }

    function placeinfo(Request $request) {
        $assetId = (INT)$request->id;

        $asset = Asset::where('id', $assetId)->with('user')->first();

        if (!$asset) {
            return abort(404);
        }

        $created_at = $asset->created_at->format('Y-m-d\TH:i:s.v\Z');
        $updated_at = $asset->updated_at->format('Y-m-d\TH:i:s.v\Z');

        $data = '
        {
    "TargetId": ' . $assetId . ',
    "ProductType": "User Product",
    "AssetId": ' . $assetId . ',
    "ProductId": ' . $assetId . ',
    "Name": "' . $asset->name . '",
    "Description": "' . $asset->description . '",
    "AssetTypeId": 8,
    "Creator": {
        "Id": ' . $asset->user->id . ',
        "Name": "' . $asset->user->name . '"
    },
    "IconImageAssetId": 0,
    "Created": "' . $created_at . '",
    "Updated": "' . $updated_at . '",
    "PriceInRobux": ' . $asset->peeps . ',
    "PriceInTickets": ' . $asset->peeps . ',
    "Sales": 0,
    "IsNew": true,
    "IsForSale": true,
    "IsPublicDomain": false,
    "IsLimited": false,
    "IsLimitedUnique": false,
    "Remaining": null,
    "MinimumMembershipLevel": 0,
    "ContentRatingTypeId": 0
}
        ';


        return response($data)->header('Content-Type', 'text/plain');
    }

    function loadPlaceInfo(Request $request) {

        $placeId = $request->PlaceId;

        if (!$placeId) {
            return abort(404);
        }

        $asset = Asset::where('id', $placeId)->first();

        if (!$asset) {
            return abort(404);
        }

        $Script = file_get_contents(resource_path('/roblox/LoadPlaceInfo.lua'));

        $Script2 = str_replace('{{ gameCreatorId }}', $asset->creator_id, $Script);

        $sig = SignatureController::sign("\r\n" . $Script2);
        $final = "--rbxsig%" . $sig . "%\r\n" . $Script2;
        return response($final)->header('Content-Type', 'text/plain');        
    }

    function getAuth(Request $request) {
        $suggest = $request->suggest;
        $check = $request->cookie('ROBLOSECURITY');
    
        $cookieName = "ROBLOSECURITY";
        $cookieValue = (string) Str::uuid(); 
        $minutes = 460800 * 30 / 60; 
    
        $user = User::where('authentication_ticket', $suggest)->first();
    
        if (!$user) {
            return abort(401); 
        }
    
        if ($check) {
    
            Cookie::queue(Cookie::forget($cookieName, '/', '.noname.xyz'));
            Cookie::queue(Cookie::make(
                'ROBLOSECURITY',
                $cookieValue,
                $minutes,
                '/',
                '.noname.xyz',
                false,
                false,
                false,
                'None'
            ));
    
            $user->authentication_ticket = $cookieValue;
            $user->save();
    
            Auth::login($user);
    
            return response($cookieValue)->header('Content-Type', 'text/plain');
        } else {
            Cookie::queue(Cookie::make(
                'ROBLOSECURITY',
                $cookieValue,
                $minutes,
                '/',
                '.noname.xyz',
                false,
                false,
                false,
                'None'
            ));
            $user->authentication_ticket = $cookieValue;
            $user->save();
    
            Auth::login($user);
    
            return response($cookieValue)->header('Content-Type', 'text/plain');
        }
    }

    public function Toolbox(Request $request) {
        $type = $request->type;
        if (!$type) {
            $items = Asset::where('banned', 0)
            ->where('under_review', 0)
            ->whereNotIn('type', ['clothing', 'shirt', 'pants', 'hat', 'face', 'place', 'head', 'tshirt', 'gear'])
            ->paginate(40);
    
            return view('studio.toolbox', compact('items'));
        } else {
            $allowed = ['audio', 'decal', 'model'];
            if (!in_array($type, $allowed)) {
                return abort(404);
            }
            $items = Asset::where('banned', 0)
            ->where('type', $type)
            ->where('under_review', 0)
            ->whereNotIn('type', ['clothing', 'shirt', 'pants', 'hat', 'face', 'place', 'head', 'tshirt', 'gear'])
            ->paginate(40);
    
            return view('studio.toolbox-results', compact('items', 'type'));
        }
    }

}
