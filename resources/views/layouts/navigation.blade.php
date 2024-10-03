<nav class="navbar navbar-expand-lg navbar-light bg-noname shadow-sm">
<div class="container">
  <a class="navbar-brand fst-italic fw-medium" href="/app/home"><img src="/images/logo.png" width="32" alt="NONAME Logo" class="mr-2">
  {{ config('app.name', 'Laravel') }}
</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item {{ request()->is('app/home', '/') ? 'active' : ''}}">
        <a class="nav-link" href="/app/home">Home</a>
      </li>
      <li class="nav-item {{ request()->is('app/places', 'app/place/*', 'app/places/*') ? 'active' : ''}}">
        <a class="nav-link" href="/app/places">Places</a>
      </li>
      <li class="nav-item {{ request()->is('app/catalog', 'app/item/*', 'app/catalog/*') ? 'active' : ''}}">
        <a class="nav-link" href="/app/catalog">Catalog</a>
      </li>
      <li class="nav-item {{ request()->is('app/create', 'app/create/*') ? 'active' : ''}}">
        <a class="nav-link" href="/app/create">Create</a>
      </li>

      <li class="nav-item {{ request()->is('app/forum/*', 'app/forum') ? 'active' : ''}}">
        <a class="nav-link" href="/app/forum">Forum</a>
      </li>

      <li class="nav-item dropdown fw-semibold">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
        More
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="/app/download">Download</a>
          @if (Auth::user()->admin == 1)
          <a class="dropdown-item text-danger" href="/app/admin/main">Admin</a>
          @endif
          <a class="dropdown-item" href="#">Coming soon...</a>
        </div>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="You have {{ Auth::user()->peeps }} Peeps"><img src="/images/{{ Auth::user()->using_alternative_peeps ? 'peeps_alternative.png' : 'peeps.png' }}" width="24" class="mr-1"> <span id="userPeeps">{{ Auth::user()->peeps }}</span></a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
        <i class='far fa-user mr-2'></i>{{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu">
        <a class="dropdown-item" href="/app/user/{{ Auth::user()->id }}">Profile</a>
          <a class="dropdown-item" href="/app/settings">Settings</a>
          <a class="dropdown-item" href="/app/avatar">Avatar Editor</a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
          @csrf
          <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); this.closest('form').submit();">Log out</a>
          </form>
        </div>
      </li>
    </ul>

  </div>
</div>
</nav>

<div class="bg-warning w-100 p-1 text-center text-black"><small>阴茎屁股公鸡PP</small></div>
<div class="bg-danger w-100 p-1 text-center text-white">Hawk Tuah Spit On That Tahng </div>