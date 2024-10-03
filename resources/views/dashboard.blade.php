@section('title', 'Home')
<x-app-layout>
    <div class="jumbotron jumbotron-fluid mb-4 jumbo-bg">
        <div class="container">
            <div class="row">
                <div class="col-auto">
                    <img src="/cdn/users/{{ Auth::id() }}?t={{ time() }}" alt="User" width="200" height="100%">
                </div>
                <div class="col">
                    <h1 class="display-4 text-white">welcome back, {{ Auth::user()->name }}</h1>
                    <p class="lead text-white">今天把你的窥视花在很酷的东西上!!!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <hr>

        @if (\Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <h3 class="font-weight-light mb-3">popular places</h3>

        <div class="row">
        @foreach ($popular as $game)
            <div class="col-md-3">
                <x-place-card :title="$game->name" :status="$game->playing" :creator="$game->user->name" :visits="$game->visits" :year="$game->year" :id="$game->id" :under_review="$game->under_review"  />
            </div>
            @endforeach
        </div>

    </div>




    <script src="/functions.js"></script>
</x-app-layout>
