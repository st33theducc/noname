@guest
@section('title', 'Welcome')
<x-guest-layout>
<link href="https://cdn.plyr.io/3.4.6/plyr.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-dark bg-success border-bottom shadow-sm">
    <div class="container">
  <a class="navbar-brand fst-italic fw-medium" href="/">{{ config('app.name', 'Laravel') }}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link {{ request()->is('/') ? 'active' : ''}}" href="/">Home <span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/register') ? 'active' : ''}}" href="/app/register">Register</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('app/login') ? 'active' : ''}}" href="/app/login">Log in</a>
        </li>
    </ul>
  </div>
  </div>
</nav>

    <div class="jumbotron jumbotron-fluid mb-4">
        <div class="container">
            <h1 class="display-4">welcome to {{ config('app.name', 'Laravel') }}</h1>
            <p class="lead">{{ config('app.name', 'Laravel') }} is a revival that keeps things simple.</p>
            <a class="btn btn-success btn-lg me-2" href="/app/register" role="button">register</a> <a class="btn btn-secondary btn-lg" href="/app/login" role="button">log in</a>
        </div>
    </div>

    <div class="container">

    <hr>

    <h4 class="fw-bold mb-3">videos from beta testers</h4>

    <div class="row">
    <div class="col-md-4 d-flex">
        <video controls crossorigin playsinline id="video1" class="w-100">
            <source src="/videos/json.mov" type="video/quicktime">
        </video>
    </div>
    <div class="col-md-4 d-flex">
        <video controls crossorigin playsinline id="video2" class="w-100">
            <source src="/videos/mort.mp4" type="video/mp4">
        </video>
    </div>
    
    </div>

<script>
document.addEventListener('DOMContentLoaded', () => { 
  const vid1 = new Plyr('#video1');
  window.vid1 = vid1;

  const vid2 = new Plyr('#video2');
  window.vid2 = vid2;
});
</script>


<script src="https://cdn.plyr.io/3.4.6/plyr.js"></script>
</x-guest-layout>
@endguest

@auth
@section('title', 'Home')
    <x-app-layout>
        <div class="container mt-5">
            <h3 class="fw-bolder">Please wait</h3>
        </div>
    </x-app-layout>
    <script>
        document.location = '/app/home'
    </script>
@endauth