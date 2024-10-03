<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\ThumbnailController;

use Illuminate\Support\Facades\Auth;

use App\Models\Asset;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:a,m,d,ts,s,p',
            'fileupload' => 'required|file',
        ];
    
        switch ($request->type) {
            case 'a':
                $rules['fileupload'] = 'required|file|mimes:mp3,wav,ogg|max:9500'; 
                break;
            case 'm':
                $rules['fileupload'] = 'required|file|max:15360'; 
                break;
            case 'd':
                $rules['fileupload'] = 'required|file|mimes:png,jpg,jpeg|max:5120'; 
                break;
            case 'ts':
                $rules['fileupload'] = 'required|file|mimes:png,jpg,jpeg|max:5120'; 
                $rules['price'] = 'required|numeric|min:1|max:50';
                break;
            case 's':
                $rules['fileupload'] = 'required|file|mimes:png,jpg,jpeg|max:5120'; 
                $rules['price'] = 'required|numeric|min:1|max:50';
                break;
            case 'p':
                $rules['fileupload'] = 'required|file|mimes:png,jpg,jpeg|max:5120'; 
                $rules['price'] = 'required|numeric|min:1|max:50';
                $rules['max_players'] = 'numeric|min:1|max:500';
                break;
        }
    
        $validated = $request->validate($rules);
    
        switch ($validated['type']) {
            case 'a':
                return $this->handleAudioUpload($validated);
            case 'm':
                return $this->handleModelUpload($validated);
            case 'ts':
                return $this->handleTShirtUpload($validated);
            case 's':
                return $this->handleShirtUpload($validated);
            case 'p':
                return $this->handlePantsUpload($validated);
            case 'd':
                return $this->handleDecalUpload($validated);
        }
    }
    
    private function handleAudioUpload($validated)
    {
        try {
            $asset = Asset::create([
                'name' => Str::uuid()->toString() . '_audio',
                'type' => 'clothing',
                'off_sale' => 1,
                'peeps' => 0,
                'creator_id' => Auth::id(),
                'under_review' => 0,
                'year' => 2016,
                'thumbnailUrl' => 'pp',
                'description' => $validated['description'] ?? 'This game has no description.',
                'off_sale' => true,
            ]);
    
            $id = $asset->id;
            $uploadPath = storage_path('app/public/asset');
    
            if ($validated['fileupload']->isValid()) {
                $validated['fileupload']->move($uploadPath, $id);
            } else {
    
                \Log::error('File upload failed', [
                    'file' => $validated['fileupload'],
                    'error' => $validated['fileupload']->getError(),
                ]);
                return response()->json(['message' => 'File upload failed.'], 422);
            }
    
            $this->generateXml($id, 'audio', $validated['name'], $validated['description']);
    
            return redirect()->back()->with('message', 'Uploaded.');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', 'Sorry, an error occured.');
        }
    }
    
    private function handleModelUpload($validated)
    {
        $asset = Asset::create([
            'name' => $validated['name'],
            'type' => 'model',
            'creator_id' => Auth::id(),
            'under_review' => 1,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This game has no description.',
            'year' => 2016,
        ]);
    
        $extension = $validated['fileupload']->getClientOriginalExtension(); // workaround because for some reason rbxmx mimes are ignored
        if (!in_array($extension, ['rbxm', 'rbxmx'])) {
            return response()->json(['message' => 'Only valid R* models are allowed.'], 422);
        }

        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);

        // render it!!!!
        //ThumbnailController::renderModel($id--); // ++ the id because i think it'd give the xml - Due to moderation, i feel like it would be better NOT to render the model
    
        return redirect()->back()->with('message', 'Uploaded.');
    }
    
    private function handleTShirtUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This game has no description.',
            'year' => 2016,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
        ThumbnailController::renderTShirt($id--);
    
        $this->generateXml($id, 'tshirt', $validated['name'], $validated['description'], $validated['price']);

        return redirect()->back()->with('message', 'Uploaded.');
    }

    private function handleShirtUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This game has no description.',
            'year' => 2016,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        $this->generateXml($id, 'shirt', $validated['name'], $validated['description'], $validated['price']);

        return redirect()->back()->with('message', 'Uploaded.');
    }

    private function handlePantsUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This game has no description.',
            'year' => 2016,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        $this->generateXml($id, 'pants', $validated['name'], $validated['description'], $validated['price']);

        return redirect()->back()->with('message', 'Uploaded.');
    }

    public function uploadFace(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:40',
            'description' => 'nullable|string|max:300',
            'price' => 'required|numeric',
            'fileupload' => 'required|image|max:5120|mimes:jpg,jpeg,png',
        ];
    
        $lol = $request->validate($rules);

        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_here_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => $lol['price'],
            'thumbnailUrl' => 'pp',
            'description' => $lol['description'] ?? 'This item has no description.',
            'year' => 2016,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');

        $lol['fileupload']->move($uploadPath, $id);
    
        $this->generateXml($id, 'face', $lol['name'], $lol['description'], $lol['price'], 0);
    
        return redirect()->back()->with('message', 'Uploaded.');
    }
    
    private function handleDecalUpload($validated)
    {
        $asset = Asset::create([
            'name' => Str::uuid()->toString() . '_here_asset',
            'type' => 'clothing',
            'creator_id' => Auth::id(),
            'under_review' => 0,
            'peeps' => 0,
            'thumbnailUrl' => 'pp',
            'description' => $validated['description'] ?? 'This game has no description.',
            'year' => 2016,
        ]);
    
        $id = $asset->id;
        $uploadPath = storage_path('app/public/asset');
    
        $validated['fileupload']->move($uploadPath, $id);
    
        $this->generateXml($id, 'decal', $validated['name'], $validated['description']);
    
        return redirect()->back()->with('message', 'Uploaded.');
    }
    
    private function generateXml($id, $templateFile, $name, $description, $price = 0, $underreview = 1)
    {
        $fuckshitpenis = Asset::create([
            'name' => (string) $name, 
            'type' => $templateFile,
            'peeps' => $price,
            'creator_id' => Auth::id(),
            'under_review' => $underreview,
            'year' => 2016,
            'thumbnailUrl' => 'pp',
            'description' => $description ?? 'This game has no description.',
        ]);

        $PleaseGodKillMeForFucksSakeAlreadyIAmInSoMuchPainRightNowSoStopItPleaseThankYou = $fuckshitpenis->id - 1;

        $xmlTemplate = file_get_contents(resource_path('xml/' . $templateFile . '.xml'));
        $xmlContent = str_replace('{{ id }}', $PleaseGodKillMeForFucksSakeAlreadyIAmInSoMuchPainRightNowSoStopItPleaseThankYou, $xmlTemplate);
    
        Storage::put('public/asset/' . $fuckshitpenis->id, $xmlContent);
    }

    public function uploadPlace(Request $request)
    {

    if (Auth::user()->place_slots_left == 0) {
        return redirect()->back()->with('message', 'Only valid R* places are allowed.');
    }
    $rules = [
        'name' => 'required|string|max:40',
        'description' => 'nullable|string|max:300',
        'fileupload' => 'required|file|max:20480',
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    ];

    $validated = $request->validate($rules);

    $extension = $validated['fileupload']->getClientOriginalExtension(); // workaround because for some reason rbxmx mimes are ignored
    if (!in_array($extension, ['rbxl', 'rbxlx'])) {
        return redirect()->back()->with('message', 'Only valid R* places are allowed.');
    }

    $asset = Asset::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'type' => 'place',
        'peeps' => 0,
        'thumbnailUrl' => 'pp',
        'creator_id' => Auth::id(),
        'under_review' => 1,
        'year' => 2016,
        'max_players' => $validated['max_players'],
        'description' => $validated['description'] ?? 'This place has no description.',
    ]);

    $asset->peeps = 0;

    $id = $asset->id;
    $uploadPath = storage_path('app/public/places');

    $validated['fileupload']->move($uploadPath, $id);

    if ($request->hasFile('thumbnail')) {
        $thumbnailPath = public_path('cdn');
        $request->file('thumbnail')->move($thumbnailPath, $id);
    } else {
        ThumbnailController::placeThumbnail($id); // render the place thumbnail
    }

    Auth::user()->place_slots_left--;
    Auth::user()->save();

    return redirect('/app/place/' . $id);
}

}
