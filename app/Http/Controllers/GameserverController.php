<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Roblox\Grid\Rcc\RCCServiceSoap;
use App\Roblox\Grid\Rcc\Job;
use App\Roblox\Grid\Rcc\ScriptExecution;
use App\Roblox\Grid\Rcc\Status;
use App\Roblox\Grid\Rcc\LuaType;
use App\Roblox\Grid\Rcc\LuaValue;

use App\Models\ServerJobs;
use App\Models\GameTickets;
use App\Models\RccInstances;
use App\Models\ServerPlayers;
use App\Models\User;
use App\Models\Asset;

class GameserverController extends Controller
{
    public static function startGameserver($placeId) {
        $generatedPort = rand(53640, 9999);
        $jobId = Str::uuid();
        $existingJob = ServerJobs::where('placeId', $placeId)->first();

        $assetTiedtojob = Asset::where('id', $placeId)->first();

        // for rcc
        $gameserverLua = file_get_contents(resource_path('/roblox/gameserver.lua'));

        $newLua = str_replace('{{startFunc}}', "start(" . $placeId . ", 'http://www.noname.xyz', " . $generatedPort . ", " . $placeId . ")", $gameserverLua);

        if ($existingJob && $assetTiedtojob->playing < $assetTiedtojob->max_players) {
            return ['status' => $existingJob->status, 'jobid' => $existingJob->jobId, 'port' => $existingJob->port];
        } else {
            $job = [
                'jobId' => $jobId,
                'status' => '1',
                'placeId' => $placeId,
                'port' => $generatedPort,
            ];

            $createJob = ServerJobs::create($job);

            $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 64989);
            
            $gameJob = new Job($jobId,70);

            $script = new ScriptExecution($jobId ."-Script", $newLua);
            $jobload = $RCCServiceSoap->OpenJobEx($gameJob, $script);

            return ['status' => '1', 'jobid' => $jobId, 'port' => $generatedPort];
        }
    }


    public function renewGameserver($jobId) {
        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 64989);
        $RCCServiceSoap->RenewLease($jobId, 120);
    }

    public function completeGameserver($jobId) {
        ServerJobs::where('jobId', $jobId)->update(['status' => 2]);
    }

    public function deleteJobGameserver($jobId) {
        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 64989);
        $RCCServiceSoap->RenewLease($jobId, 1);
        ServerJobs::where('jobId', $jobId)->delete();
    }

    // Below are user-agent protected endpoints.

    public function registerRcc(Request $request) {
        
        if ($request->header('User-Agent') !== 'Roblox/WinInet') {
        RccInstances::create([
            'id' => Str::uuid(),
        ]);

        $data = [
            'success' => true,
            'message' => 'RCC registered successfully',
        ];
        return response()->json($data);
        } else {
            $data = [
                'success' => false,
                'message' => 'No access for you :)',
            ];
            return response()->json($data);
        }
    }

    public function removeRcc($uuid, Request $request) {
        
        if ($request->header('User-Agent') !== 'Roblox/WinInet') {

            if (!isset($uuid)) {
                $data = [
                    'success' => false,
                    'message' => 'Nope.',
                ];
                return response()->json($data);
            }

            $instance = RccInstances::where('id', $uuid)->first();

            if (!$instance) {
                $data = [
                    'success' => false,
                    'message' => 'Instance doesn\'t exist',
                ];
    
                return response()->json($data);
            }

            $instance->delete();

            $data = [
                'success' => true,
                'message' => 'RCC unregistered successfully',
            ];

            return response()->json($data);
        } else {
            $data = [
                'success' => false,
                'message' => 'No access for you :)',
            ];

            return response()->json($data);
        }
    }


    public function AddToServer(Request $request) {

        $placeId = (INT)$request->placeId;
        $accessKey = $request->accessKey;
        $userId = (INT)$request->userId;

        if (!isset($placeId) || !isset($accessKey) || !isset($userId)) {
            return abort(404);
        }

        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";
    
        if (!$accessKey == $ak) {
            return abort(401);
        }

        \Log::info('Place ID: ' . $placeId);
        \Log::info('Access Key: ' . $accessKey);
        \Log::info('User ID: ' . $userId);
    
        $game = ServerJobs::where('placeId', $placeId)->first();
        $asset = Asset::where('id', $placeId)->first();
        
        $user = User::where('id', $userId)->first();  
    
        $data = [
            'userId' => $user->id,  
            'placeId' => $placeId,
            'jobId' => $game->jobId,
        ];
    
        ServerPlayers::create($data);

        $asset->playing++;
        $asset->save();
    
        return true; 
    }

    public function RemoveFromServer(Request $request) {

        $placeId = (INT)$request->placeId;
        $accessKey = $request->accessKey;
        $userId = (INT)$request->userId;

        if (!isset($placeId) || !isset($accessKey) || !isset($userId)) {
            return abort(404);
        }

        $ak = "u1pZJEnTXzVoMezo1MLE7NMoS14i9ltn";
    
        if (!$accessKey == $ak) {
            return abort(401);
        }

        \Log::info('Place ID: ' . $placeId);
        \Log::info('Access Key: ' . $accessKey);
        \Log::info('User ID: ' . $userId);
    
        $game = ServerJobs::where('placeId', $placeId)->first();
        $asset = Asset::where('id', $placeId)->first();

        $user = User::where('id', $userId)->first();  

        $serverPlayersEntry = ServerPlayers::where('userId', $userId)->where('placeId', $placeId)->first();

        if (!$game || !$user || !$serverPlayersEntry) {
            return abort(404);
        }
        
        $asset->visits++;
        $asset->playing--;
        $asset->save();

        $serverPlayersEntry->delete();

        return true; // the rcc can read this

    }

}
