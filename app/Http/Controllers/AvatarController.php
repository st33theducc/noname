<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Owned;
use App\Models\Bodycolors;
use App\Models\Asset;
use App\Models\User;

class AvatarController extends Controller
{
    function charapp($id, Request $request) {
        if (!isset($id)) {
            return abort(400);
        }

        $rcc = $request->query('rcc') ?? false;
    
        $urls = [];
        $urls[] = 'http://noname.xyz/Asset/BodyColors.ashx?t=' . time() . '&id=' . $id;
    
        $ownedItems = Owned::where('userId', $id)
            ->where('wearing', 1)
            ->get(['itemId']); 
    
        foreach ($ownedItems as $item) {
            if ($rcc) {
                $url = 'http://noname.xyz/asset/?id=' . $item->itemId . '&rcc=true';
                $urls[] = $url; 
            } else {
                $url = 'http://noname.xyz/asset/?id=' . $item->itemId;
                $urls[] = $url; 
            }
        }
    
        $result = implode(';', $urls);
    
        return response($result)->header('Content-Type', 'text/plain');
    }


    function bodycolors(Request $req) {
        $id = $req->id;

        if (!isset($id)) {
            return abort(400);
        }

        $bc = Bodycolors::where('userId', $id)->first();
        if (!$bc) {
            return abort(404);
        }

        $data =  '
<roblox
	xmlns:xmime="http://www.w3.org/2005/05/xmlmime"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.noname.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="BodyColors">
		<Properties>
			<int name="HeadColor">' . $bc->head . '</int>
			<int name="LeftArmColor">' . $bc->larm . '</int>
			<int name="LeftLegColor">' . $bc->lleg . '</int>
			<string name="Name">Body Colors</string>
			<int name="RightArmColor">' . $bc->rarm . '</int>
			<int name="RightLegColor">' . $bc->rleg . '</int>
			<int name="TorsoColor">' . $bc->torso . '</int>
			<bool name="archivable">true</bool>
		</Properties>
	</Item>
</roblox>
';

    return response($data)
    ->header('Content-Type', 'text/xml')
    ->header('Cache-Control', 'no-cache')
    ->header('Pragma', 'no-cache')
    ->header('Expires', '-1')
    ->header('Last-Modified', gmdate('D, d M Y H:i:s T') . ' GMT');

    }

    function show() {
        $items = Owned::where('userId', Auth::id())->with('asset')->where('wearing', 0)->paginate(12, ['*'], 'items');
        $wearing_items = Owned::where('userId', Auth::id())->where('wearing', 1)->with('asset')->paginate(12, ['*'], 'wearing');
        $bodyColors = Bodycolors::where('userId', Auth::id())->first();

        return view('avatar', compact('items', 'bodyColors', 'wearing_items'));
    }

    function changeBodyColor($color, $part)
    {
        // valid colors and parts
        $validColors = [
            1, 2, 3, 5, 6, 8, 11, 12, 21, 22, 23, 24, 26, 27, 28, 36, 37, 38, 39, 40,
            41, 42, 43, 45, 47, 48, 49, 50, 100, 101, 102, 104, 105, 106, 107, 108, 110,
            111, 112, 115, 116, 118, 119, 120, 121, 123, 124, 126, 127, 128, 131, 133, 134,
            135, 136
        ];
    
        $validParts = ['head', 'torso', 'larm', 'rarm', 'lleg', 'rleg'];
    
        if (!$color || !in_array($color, $validColors)) {
            return response('Invalid color', 400);
        }
    
        if (!$part || !in_array($part, $validParts)) {
            return response('Invalid part', 400);
        }
    
        if (!Auth::check()) {
            return response('Unauthorized', 403);
        }

        $bodycolors = Bodycolors::where('userId', Auth::id())->first();
    
        if (!$bodycolors) {
            $bodycolors = new Bodycolors();
            $bodycolors->userId = Auth::id();
        }

        $bodycolors->$part = $color;
        $bodycolors->save();
    
        return response('Body color updated successfully');
    }

    function wearItem($id) {
        if (!Auth::check()) {
            return response('error');
        }
    
        if (empty($id)) {
            return response('error');
        }
    
        $owned = Owned::where('userId', Auth::id())->where('itemId', $id)->first();
    
        if (!$owned) {
            return response('error');
        }
    
        $owned->wearing = $owned->wearing ? 0 : 1;
    
        try {
            $owned->save();
        } catch (\Exception $e) {
            return response('error');
        }
    
        return response('ok');
    }
    
}
