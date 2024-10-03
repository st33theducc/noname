@section('title', $user->name)
<div class="modal fade" id="wearingModal" tabindex="-1" aria-labelledby="wearingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wearingModalLabel">wearing</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
         @if ($owned->isEmpty())
         <p class="text-center text-muted mb-0 mt-2">This user is naked.</p>
         @else
         <div class="row">
         @foreach ($owned as $item)
         <div class="col-3 px-2 mb-3">
         <x-catalog-card :title="$item->asset->name" :special="$item->special" :status="$item->asset->peeps . ' Peeps'" :id="$item->asset->id" :price="$item->asset->peeps" :offsale="$item->asset->off_sale" :under_review="$item->asset->under_review" :type="$item->asset->type" :imgsize="106" />
   </div>
         @endforeach
         @endif
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<x-app-layout>
   <div class="container mt-5">
      @if ($user->banned == 0)
      <x-card :title="'profile'" class="mb-3">
         <div class="container">
            <div class="row">
               <div class="col-auto">
                  <img src="/cdn/users/{{ $user->id }}?t={{ time() }}" alt="{{ $user->name }}" width="200px" height="200px">
               </div>
               <div class="col">
                  <h4 class="fw-bold">{{ $user->name }}</h4>
                  <h6 class="fw-medium mb-3">
                     @foreach ($userbadges as $usrbadge)
                     <span class="badge {{ $usrbadge->color }} mr-1">{{ $usrbadge->text }}</span>
                     @endforeach
                  </h6>
                  <small class="fw-light mb-1">Bio:</small>
                  <p class="text-muted fw-regular mb-2" id="user-description">
                     {!! nl2br(e($user->description)) !!}
                  </p>
                  <p class="fw-regular mb-2">
                     Joined {{ config('app.name', 'Laravel') }} {{ $user->created_at->diffForHumans() }}.
                     @if ($user->banned == 1)
                     <br><span class="text-danger">This user has been banned. Reason: {{ $user->ban_reason }}</span>
                     @endif
                     @if ($user->admin ==1) 
                     <br><span class="text-danger"><i class="far fa-gavel mr-2"></i> This user is an administrator.</span>
                     @endif
                  </p>
                  <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#wearingModal">view wearing</button>
               </div>
               <div class="col text-right pr-0">
                  <button class="btn btn-success"><i class="far fa-user-plus mr-2"></i>Add Friend</button>
               </div>
            </div>
         </div>
      </x-card>
      <div class="mt-4">
         <div class="row">
            <div class="col-md-5">
               <x-card :hasall="'yep'" :alltext="'see all &nbsp; 🢒'" :alllink="'/user/' . $user->id . '/friends'" :title="'friends'">
                  <!--<p class="text-muted text-center mb-0">This user has no friends.</p>-->
                  <div class="row">
                     <div class="col-md-4 text-center">
                        <img src="/cdn/users/0?t={{ time() }}" alt="[ NONAME ]" width="100%" class="mb-2">
                        <a href="/app/user/0" class="text-truncate" class="mb-0">[ NONAME ]</a>
                     </div>
                     <div class="col-md-4 text-center">
                        <img src="/cdn/users/1?t={{ time() }}" alt="ectoBiologist" width="100%" class="mb-2">
                        <a href="/app/user/1" class="text-truncate" class="mb-0">ectoBiologist</a>
                     </div>
                     <div class="col-md-4 text-center">
                        <img src="/cdn/users/8?t={{ time() }}" alt="json" width="100%" class="mb-2">
                        <a href="/app/user/8" class="text-truncate" class="mb-0">json</a>
                     </div>
                  </div>
               </x-card>
            </div>
            <div class="col">
               <x-card :title="'places'">
                  @if ($assets->isEmpty())
                  <p class="text-muted text-center mb-0">This user has no places.</p>
                  @else
                  <div class="accordion" id="userplaces">
                  @foreach ($assets as $asset)

                     <div class="card">
                        <div class="card-header" id="place{{ $asset->id }}">
                           <h2 class="mb-0">
                              <button class="btn btn-link text-success btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $asset->id }}" aria-expanded="true" aria-controls="collapse{{ $asset->id }}">
                              {{ $asset->name }}
                              </button>
                           </h2>
                        </div>
                        <div id="collapse{{ $asset->id }}" class="collapse" aria-labelledby="place{{ $asset->id }}" data-parent="#userplaces">
                           <div class="card-body">
                              <img src="/cdn/{{ $asset->id }}?t={{ time() }}" alt="Place Thumbnail" width="100%" height="auto" class="rounded border mb-2">

                              <button class="btn btn-success w-100" onclick="document.location = '/app/place/{{ $asset->id }}'">view place</button>
                           </div>
                        </div>
                     </div>

                  @endforeach
                  </div>
                  @endif
               </x-card>
            </div>
         </div>
      </div>
      @else

      <div class="bg-danger p-2 font-weight-bold mb-5 text-white">This user is no longer available. Reason: <i class="font-weight-regular">{{ $user->ban_reason }}</i></div>
      <div class="d-flex justify-content-center">
  <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>

@endif
   </div>

   <script src="/functions.js"></script>
   <script>
      function replacePeeps() {
          let userHasAlternativePeeps = {{ $user->using_alternative_peeps ? 'true' : 'false' }};
          let userPeeps = {{ $user->peeps }};
          let peepsImg = userHasAlternativePeeps 
              ? '<img src="/images/peeps_alternative.png" alt="Peep icon" width="16" class="mx-1">[' + userPeeps + ']' 
              : '<img src="/images/peeps.png" alt="Peep icon" width="16" class="mx-1">[' + userPeeps + ']';
      
          let userDescriptionElement = document.getElementById('user-description');
          let description = userDescriptionElement.innerHTML;
          userDescriptionElement.innerHTML = description.replace('{myPeeps}', peepsImg);
      }
      
      document.addEventListener('DOMContentLoaded', replacePeeps);
   </script>
</x-app-layout>
