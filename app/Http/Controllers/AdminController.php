<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Roblox\Grid\Rcc\RCCServiceSoap;
use App\Roblox\Grid\Rcc\Job;
use App\Roblox\Grid\Rcc\ScriptExecution;
use App\Roblox\Grid\Rcc\Status;
use App\Roblox\Grid\Rcc\LuaType;
use App\Roblox\Grid\Rcc\LuaValue;

use Illuminate\Support\Facades\Auth;

use App\Models\Asset;

class AdminController extends Controller
{
    public function getJobs() {
        $RCCServiceSoap = new RCCServiceSoap("127.0.0.1", 6969);
    
        $jobs = $RCCServiceSoap->GetAllJobs();
    
        $jobsArray = [$jobs];
    
        $jobsArray = array_map(function($job) {
            return (array) $job;
        }, $jobsArray);

        return view('admin.instances.jobs', ['jobs' => $jobsArray]);
    }

    public function getPendingAssets() {
        $underReview = Asset::where('under_review', 1)
        ->whereNotIn('type', ['clothing', 'place'])
        ->paginate(12);

        return view('admin.pending.assets', compact('underReview'));
    }
    

    public function UploadHat(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'fileupload' => 'required|file',
        ];
    
        $validated = $request->validate($rules);
    
        $asset = Asset::create([
            'name' => $validated['name'],
            'type' => 'hat',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $validated['price'],
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This item has no description. Wow.',
            'year' => 2016,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension();
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return redirect()->back()->with('message', 'Only valid R* hats are allowed.');
        }
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $fileContents = file_get_contents($validated['fileupload']->getPathname());
    
        $finalContents = str_replace('Accessory', 'Hat', $fileContents);
    
        file_put_contents($uploadPath . '/' . $id, $finalContents);
    
        ThumbnailController::renderHat($id); 
    
        return redirect()->back()->with('message', 'Uploaded.');
    }

    public function UploadGear(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'fileupload' => 'required|file',
        ];
    
        $validated = $request->validate($rules);
    
        $asset = Asset::create([
            'name' => $validated['name'],
            'type' => 'gear',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $validated['price'],
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This item has no description. Wow.',
            'year' => 2016,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension();
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return redirect()->back()->with('message', 'Only valid R* gears are allowed.');
        }
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        ThumbnailController::renderGear($id); 
    
        return redirect()->back()->with('message', 'Uploaded.');
    }

    public function UploadHead(Request $request) {

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'fileupload' => 'required|file',
        ];
    
        $validated = $request->validate($rules);
    
        $asset = Asset::create([
            'name' => $validated['name'],
            'type' => 'head',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $validated['price'],
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This item has no description. Wow.',
            'year' => 2016,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension();
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return redirect()->back()->with('message', 'Only valid R* heads are allowed.');
        }
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        ThumbnailController::renderHead($id); 
    
        return redirect()->back()->with('message', 'Uploaded.');
    }
}
