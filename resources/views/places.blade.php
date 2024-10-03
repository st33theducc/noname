@section('title', 'Places')
<x-app-layout>
    <div class="container">
        <h3 class="fw-bolder mt-5">Places</h3>
        <h6 class="fw-regular text-muted mb-4">See all places made by NONAME users just like you</h6>

        <form action="/app/places/search" method="GET">
        <div class="row mb-3 gap-1">
           
            <div class="col pr-1">
                <div class="form-group">
                    <input type="text" class="form-control w-100" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Search">
                </div>
            </div>
            <div class="col-auto pl-1"><button class="btn btn-success"><i class="far fa-search"></i></button></div>
            
        </div>
        </form>

            @if ($games->isEmpty())

            <p class="text-center text-muted">No one's around.</p>

            @else 
            <div class="row">
            @foreach ($games as $game)
            <div class="col-md-3 mb-3">
                <x-place-card :title="$game->name" :under_review="$game->under_review" :status="$game->playing" :creator="$game->user->name" :special="$game->special" :visits="$game->visits" :year="$game->year" :id="$game->id" :thumbnail="$game->thumbnailUrl" />
            </div>
            @endforeach
            </div>
            <div class="mt-3 d-flex justify-content-center w-100">
                {{ $games->links() }}
            </div>
            @endif

    </div>


    <script src="/functions.js"></script>
</x-app-layout>
