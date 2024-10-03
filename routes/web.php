<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\RenderController;
use App\Http\Controllers\GameserverController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameTicketController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PersistenceController;

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\BanMiddleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/app/rules', function () {
    return view('legal.rules');
})->name('legal.rules');
Route::get('/app/policy', function () {
    return view('legal.policy');
})->name('legal.policy');

Route::middleware(['auth'])->group( function() {
    Route::get('/app/moderation', function () {
        return view('moderation');
    })->name('app.moderation');
});

Route::middleware(['auth', BanMiddleware::class])->group(function () {

    /*Route::get('/app/home', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('app.home');*/

    Route::get('/app/home', [UserController::class, 'home'])->name('app.home');

    Route::get('/app/settings', [ProfileController::class, 'edit'])->name('app.profile.edit');

    Route::get('/app/user/{id}', [UserController::class, 'view'])->name('app.profile.view');

    Route::post('/app/settings/change-bio', [ProfileController::class, 'updateBio'])->name('app.profile.change-bio')->middleware('throttle:15,1');;

    Route::get('/app/places', [GamesController::class, 'index'])->name('app.places');
    Route::get('/app/places/search', [GamesController::class, 'search'])->name('app.place.search');
    Route::get('/app/place/{id}', [GamesController::class, 'show'])->name('app.place.view');

    Route::get('/app/download', function () {
        return view('download');
    })->name('app.download');

    Route::get('/app/catalog', [CatalogController::class, 'index'])->name('app.catalog');
    Route::get('/app/catalog/search', [CatalogController::class, 'search'])->name('app.catalog.search');
    
    
    Route::get('/app/model/{id}', [CatalogController::class, 'model'])->name('app.item.view-model');
    Route::get('/app/catalog/{by}', [CatalogController::class, 'sort'])->name('app.catalog.sort');
    Route::get('/app/item/{id}', [CatalogController::class, 'show'])->name('app.item.view');

    Route::get('/app/create', function () {
        return view('create');
    })->name('app.create');

    // create routes for the options
    Route::get('/app/create/asset', function () {
        return view('create.asset');
    })->name('app.create.asset');
    
    Route::get('/app/create/place', function () {
        return view('create.place');
    })->name('app.create.place');

    Route::get('/app/3d-thumbnail-testing/', function () {
        return view('3d-thumbnail-test.main');
    })->name('app.3d-thumbnail-test');
    
    /*Route::get('/app/user/1', function () {
        return view('view.user');
    })->name('app.user.');*/

    Route::get('/app/forum', [ForumController::class, 'index'])->name('app.forum');

    Route::get('/app/forum/{category_id}', [ForumController::class, 'viewCategory'])->name('app.forum.viewcat');

    Route::get('/app/forum/view/{post_id}', [ForumController::class, 'viewPost'])->name('app.forum.view');
    Route::get('/app/forum/reply/{post_id}', [ForumController::class, 'replyToPost'])->name('app.forum.reply');
    Route::post('/app/forum/reply/{post_id}', [ForumController::class, 'createReply'])->name('app.forum.reply.create')->middleware('throttle:1,1');

    Route::get('/app/forum/new/{category_id}', [ForumController::class, 'newPost'])->name('app.forum.new-post');
    Route::post('/app/forum/new/{category_id}', [ForumController::class, 'createPost'])->name('app.forum.new-post.create')->middleware('throttle:1,1');

    Route::get('/app/avatar', [AvatarController::class, 'show'])->name('app.avatar');

    // APIs
    Route::get('/app/buy-slot', [CatalogController::class, 'buyPlaceSlot'])->name('app.buy.slot')->middleware('throttle:15,1');
    Route::get('/app/buy-item/{id}', [CatalogController::class, 'buy'])->name('app.buy.item')->middleware('throttle:15,1');
    Route::get('/app/change-body-color/{color}/{part}', [AvatarController::class, 'changeBodyColor'])->name('app.change-body-color')->middleware('throttle:15,1');
    Route::get('/app/wear-item/{id}', [AvatarController::class, 'wearItem'])->name('app.wear-item')->middleware('throttle:15,1');

    Route::get('/app/tickets/generate-game-ticket/{placeId}', [GameTicketController::class, 'requestTicket'])->name('app.tickets.generate-game-ticket')->middleware('throttle:20,1');
    Route::get('/app/tickets/remove-game-tickets', [GameTicketController::class, 'DeleteAllTickets'])->name('app.tickets.remove-game-tickets'); // This is less destructive

    Route::post('/app/upload-place', [UploadController::class, 'uploadPlace'])->name('app.upload-place')->middleware('throttle:1,1');
    Route::post('/app/upload-asset', [UploadController::class, 'upload'])->name('app.upload-asset')->middleware('throttle:1,1');
    Route::get('/app/theming/change', [UserController::class, 'change_theme'])->name('app.change-theme');
    
});

    // Client APIs
    Route::get('/asset', [ClientController::class, 'asset'])->name('client.asset');
    Route::get('/get-place', [ClientController::class, 'getPlace'])->name('client.get-place');

    Route::get('/game/visit.ashx', [ClientController::class, 'visit_2016'])->name('studio.visit');
    Route::get('/game/join.ashx', [ClientController::class, 'join_16'])->name('client.join');
    Route::get('/Game/PlaceLauncher.ashx', [ClientController::class, 'placelauncher'])->name('client.place.launcher');

    Route::get('/marketplace/productinfo', [ClientController::class, 'placeinfo'])->name('client.productinfo');
    
    Route::get('/char/{id}', [AvatarController::class, 'charapp'])->name('client.character');

    Route::get('/Asset/BodyColors.ashx', [AvatarController::class, 'bodycolors'])->name('body.colors');

    Route::get('/game/GetCurrentUser.ashx', function () {
        if (Auth::check()) {
            return Auth::user()->id;
        } else {
            return '-1';
        }
    })->name('gcu');

    Route::get('/Game/LuaWebService/HandleSocialRequest.ashx', [ClientController::class, 'LuaWebServiceHandleSocial'])->name('lua.web.service');

    Route::get('/clienttest/currentuser', [ClientController::class, 'getCurrentUser'])->name('test.test');
    
    // Studio pages
    Route::get('/app/studio/landing', function () {
        return view('studio.landing');
    })->name('app.studio.landing');

    Route::get('/test/{id}', [RenderController::class, 'full'])->name('test')->middleware('throttle:15,1');

    Route::get('/thumbnail/test/{id}', [ThumbnailController::class, 'placeThumbnail'])->name('thumbnailtest')->middleware('throttle:15,1');

    Route::get('/thumbnail/gear/{id}', [ThumbnailController::class, 'renderGear'])->name('thumbnail.gear')->middleware('throttle:15,1');
    Route::get('/thumbnail/shirt/{id}', [ThumbnailController::class, 'renderShirt'])->name('thumbnail.shirt')->middleware('throttle:15,1');
    Route::get('/thumbnail/pants/{id}', [ThumbnailController::class, 'renderPants'])->name('thumbnail.pants')->middleware('throttle:15,1');
    Route::get('/thumbnail/hat/{id}', [ThumbnailController::class, 'renderHat'])->name('thumbnail.hat')->middleware('throttle:15,1');

    Route::get('/universes/validate-place-join', function () {
            return "true";
    })->name('validate.place.join');

    Route::get('/GameServer/{jobId}/renew', [GameserverController::class, 'renewGameserver'])->name('gameserver.renew');
    Route::get('/GameServer/{jobId}/complete', [GameserverController::class, 'completeGameserver'])->name('gameserver.complete');
    Route::get('/GameServer/{jobId}/delete', [GameserverController::class, 'deleteJobGameserver'])->name('gameserver.delete');

    Route::middleware(['auth', CheckAdmin::class])->group(function () {
        Route::get('/app/admin/main', function () {
            return view('admin.main');
        })->name('app.admin.main');

        Route::get('/app/admin/ban-user', function () {
            return view('admin.ban.user');
        })->name('app.admin.ban-user');

        Route::get('/app/admin/render', function () {
            return view('admin.render.main');
        })->name('app.admin.render-asset');

        Route::get('/app/admin/instances', function () {
            return view('admin.instances.main');
        })->name('app.admin.instances.main');

        Route::get('/app/admin/create/hat', function () {
            return view('admin.create.hat');
        })->name('admin.create.hat.view');

        Route::get('/app/admin/create/face', function () {
            return view('admin.create.face');
        })->name('admin.create.face.view');

        Route::get('/app/admin/create/gear', function () {
            return view('admin.create.gear');
        })->name('admin.create.gear.view');

        Route::get('/app/admin/create/head', function () {
            return view('admin.create.head');
        })->name('admin.create.head.view');

        
        Route::get('/app/admin/thumbnail/gear/{id}', [AdminController::class, 'uploadGear'])->name('admin.thumbnail.gear');

        Route::get('/app/admin/thumbnail/head/{id}', [AdminController::class, 'uploadHead'])->name('admin.thumbnail.head');

        Route::get('/app/admin/thumbnail/model/{id}', [ThumbnailController::class, 'renderModel'])->name('admin.thumbnail.model');

        Route::get('/app/admin/thumbnail/user/{id}', [RenderController::class, 'full'])->name('admin.thumbnail.user');
        
        Route::get('/app/admin/thumbnail/gear/{id}', [ThumbnailController::class, 'renderGear'])->name('admin.thumbnail.gear');

        Route::get('/app/admin/thumbnail/shirt/{id}', [ThumbnailController::class, 'renderShirt'])->name('admin.thumbnail.shirt');

        Route::get('/app/admin/thumbnail/pants/{id}', [ThumbnailController::class, 'renderPants'])->name('admin.thumbnail.pants');

        Route::get('/app/admin/thumbnail/hat/{id}', [ThumbnailController::class, 'renderHat'])->name('admin.thumbnail.hat');

        Route::get('/app/admin/thumbnail/place/{id}', [ThumbnailController::class, 'placeThumbnail'])->name('admin.thumbnail.place');
        
        Route::get('/app/admin/jobs', [AdminController::class, 'getJobs'])->name('admin.jobs');

        Route::get('/app/admin/moderation', [AdminController::class, 'getPendingAssets'])->name('admin.moderation.assets');

        Route::post('/app/admin/create/hat', [AdminController::class, 'UploadHat'])->name('admin.create.hat');

        Route::post('/app/admin/create/face', [UploadController::class, 'uploadFace'])->name('admin.create.face');

        Route::post('/app/admin/create/head', [AdminController::class, 'uploadHead'])->name('admin.create.head');

        Route::post('/app/admin/create/gear', [AdminController::class, 'uploadGear'])->name('admin.create.gear');
        
    });

    Route::get('/rcc/register', [GameserverController::class, 'registerRcc'])->name('rcc.register');
    Route::get('/rcc/remove/{uuid}', [GameserverController::class, 'removeRcc'])->name('rcc.remove');

    Route::get('/Game/LoadPlaceInfo.ashx', [ClientController::class, 'loadPlaceInfo'])->name('game.load-place-info');

    Route::get('/game/gears-enabled/{id}', [GamesController::class, 'getGearEnabled'])->name('game.gears-enabled');

    Route::get('/IDE/ClientToolbox.aspx', [ClientController::class, 'Toolbox'])->name('game.toolbox');

    Route::get('/setup/launcherVersion', function () {
            return '0.1';
    })->name('setup.launcher.version');

    Route::get('/setup/clientVersion/2016', function () {
        return 'b2025acd-ece8-4983-9206-681b5e305fe7';
    })->name('setup.launcher.2016');

    Route::get('/ping', function () {
        return '';
    })->name('ping');

    Route::get('/server/add', [GameserverController::class, 'AddToServer'])->name('game.add-to-server')->middleware('throttle:15,1');
    Route::get('/server/remove', [GameserverController::class, 'RemoveFromServer'])->name('game.remove-from-server')->middleware('throttle:15,1');

    Route::get('/Login/Negotiate.ashx', [ClientController::class, 'getAuth'])->name('login.negotiate');

    Route::get('/app/blog/home', function () {
        return view('blog.main');
    })->name('app.blog.home');

    Route::get('/universes/validate-place-join', function () {
        return 'true';
    })->name('vpj');

    Route::get('/game/players/{id}', function () {
        return 'true';
    })->name('game.players');

    Route::post('/persistence/getSortedValues', [PersistenceController::class, 'getSortedValues']);
    Route::post('/persistence/getV2', [PersistenceController::class, 'getV2']);
    Route::post('/persistence/set', [PersistenceController::class, 'set']);

    
    Route::get('/Game/GamePass/GamePassHandler.ashx', function () {
        return '<Value type="boolean">true</Value>';
    })->name('game.gamepass.handler');

require __DIR__.'/auth.php';
