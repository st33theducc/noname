@section('title', $game->name)
<div class="modal fade" id="startGameModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body text-center">
            <p class="text-muted font-weight-regular" id="modal-status-text">
               Starting the client...
            </p>
            <div class="my-4">
               <x-loader />
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-lg btn-secondary w-100" data-dismiss="modal"><i class="far fa-times mr-2"></i>The client started, close this modal.</button>
         </div>
      </div>
   </div>
</div>
<x-app-layout>
   <div class="container mt-5">
      <x-card :title="'View game'">
         <div class="row mb-4">
            <div class="col pr-2">
               <div class="position-relative">
                  <span class="badge badge-danger position-absolute {{ $game->id == 86 ? 'mt-3' : 'mt-2' }} ml-2" style="z-index: 3;">{{ $game->year }}</span>
                  <div id="carouselGameThumbnails" class="carousel slide" data-ride="carousel">
                     <div class="carousel-inner">
                        @if ($game->id == 86)
                        <div class="carousel-item active">
                           <img src="/cdn/86" class="d-block w-100" height="400" alt="1">
                        </div>
                        <div class="carousel-item">
                            <img src="/cdn/86-2" class="d-block w-100" height="400" alt="2">
                        </div>
                        <div class="carousel-item">
                            <img src="/cdn/86-3" class="d-block w-100" height="400" alt="3">
                        </div>
                        <div class="carousel-item">
                            <img src="/cdn/86-4" class="d-block w-100" height="400" alt="4">
                        </div>
                        <div class="carousel-item">
                            <img src="/cdn/86-5" class="d-block w-100" height="400" alt="5">
                        </div>
                        @else
                            @if ($game->under_review == 1)
                                <img src="/images/place_pending_lg.png" alt="Game Thumbnail" width="100%" height="400" class="place-thumbnail border rounded-sm">
                            @else
                            <img src="/cdn/{{ $game->id }}?t={{ time() }}" alt="Game Thumbnail" width="100%" height="400" class="place-thumbnail border rounded-sm">
                            @endif
                        @endif
                     </div>
                     <button class="carousel-control-prev" type="button" data-target="#carouselGameThumbnails" data-slide="prev">
                     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                     <span class="sr-only">Previous</span>
                     </button>
                     <button class="carousel-control-next" type="button" data-target="#carouselGameThumbnails" data-slide="next">
                     <span class="carousel-control-next-icon" aria-hidden="true"></span>
                     <span class="sr-only">Next</span>
                     </button>
                  </div>
               </div>
            </div>
            <div class="col-4">
               <h4 class="font-weight-bolder">{{ $game->name }}</h4>
               <h6 class="font-weight-regular text-muted mb-3">by <a href="/app/user/{{ $game->user->id }}">{{ $game->user->name }}</a></h6>
               <div class="position-relative">
                  <button class="btn btn-success w-100" data-toggle="modal" data-target="#startGameModal" onclick="startGame({{ $game->id }})"><i class="far fa-play mr-2"></i>play</button>
               </div>
            </div>
         </div>
         <ul class="nav nav-tabs" id="gameView" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active" id="description-tab" data-toggle="tab" data-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="stats-tab" data-toggle="tab" data-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="false">Statistics</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="badges-tab" data-toggle="tab" data-target="#badges" type="button" role="tab" aria-controls="badges" aria-selected="false">Badges</button>
            </li>
         </ul>
         <div class="tab-content" id="gameViewTab">
            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
               <h5 class="font-weight-medium mt-4">Description</h5>
               <p class="font-weight-regular text-muted mb-0" id="description-parse">
                  {!! nl2br(e($game->description)) !!}
               </p>
            </div>
            <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab">
               <div class="row my-4 text-center">
                  <div class="col">
                     <h5 class="font-weight-medium">Visits</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->visits }}</h6>
                  </div>
                  <div class="col">
                     <h5 class="font-weight-medium">Max Players</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->max_players }}</h6>
                  </div>
                  <div class="col">
                     <h5 class="font-weight-medium">Created</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->created_at }}</h6>
                  </div>
                  <div class="col">
                     <h5 class="font-weight-medium">Updated</h5>
                     <h6 class="mb-0 font-weight-regular text-muted">{{ $game->updated_at }}</h6>
                  </div>
               </div>
            </div>

            <div class="tab-pane fade" id="badges" role="tabpanel" aria-labelledby="badges-tab">
               <h5 class="font-weight-medium mt-4">Badges</h5>
               <p class="font-weight-regular text-center text-muted mb-0" id="description-parse">
                  This place has no badges.
               </p>
            </div>

         </div>
      </x-card>
      <div class="mt-3">
    <h4 class="font-weight-light mb-3">Servers</h4>
    @if (!empty($serversWithPlayers))
        @foreach ($serversWithPlayers as $data)
            <div class="card card-body mb-2" data-server-id="{{ $data['server']->id }}">
                <h5 class="font-weight-regular mb-1">Server <small class="ml-2"><code>{{ $data['server']->jobId }}</code></small></h5>
                <h6 class="font-weight-light {{ $data['serverPlayers']->isEmpty() ? 'mb-0' : 'mb-4' }}">
                    {{ $data['serverPlayers']->count() }} online out of {{ $game->max_players }} players
                    @if ($data['serverPlayers']->isEmpty())
                        (No players currently online)
                    @endif
                </h6>
                <div class="row">
                    @if (!$data['serverPlayers']->isEmpty())
                        @foreach ($data['serverPlayers'] as $serverPlayer)
                            <x-server-list-user :id="$serverPlayer->userId" />
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p class="text-center mb-0 fw-light">This game has no running servers. Click <i>Play</i> to start a new one.</p>
    @endif
</div>



   </div>
   <script src="/functions.js"></script>
   <script>
      let statusText = document.getElementById('modal-status-text');
      
      async function startGame(id) {
          try {
            // remove tokens to save a trip to settings :P
            await fetch('/app/tickets/remove-game-tickets')  

            const response = await fetch(`/app/tickets/generate-game-ticket/${id}`);
            const result = await response.json();
      
              if (!response.ok) {
                  statusText.innerHTML = "Couldn't start client, please try again later.";
                  return;
              }
              
              statusText.innerHTML = result.message;
      
              if (result.success === true) {
                  document.location = "noname-client://" + result.token;
                  await sleep(3000);
                  location.reload();
              }
          } catch (error) {
              statusText.innerHTML = "Couldn't start client, please try again later.";
          }
      }
      
      
              parseDescription(); // markdown sux 
          
   </script>
</x-app-layout>
