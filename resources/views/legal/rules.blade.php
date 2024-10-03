@section('title', 'Rules')
@auth
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'Rules'">
            <p class="text-muted mb-0">
                Welcome to {{ config('app.name', 'Laravel') }}! We are a old Roblox revival. <br>
                1. No spamming allowed. This can result in a 2 day ban.<br>
                2. No slurs. Slurs are strictly forbidden and will result in a permanent ban. (Yes, this includes the "F" slur and the "N" slur.)<br>
                3. You must be over 13 years old to play {{ config('app.name', 'Laravel') }}. This can result in a permanent ban from both our Discord and the {{ config('app.name', 'Laravel') }} site.<br>
                4. Please don't harass people. If you need to file a report, just do it in the #reports channel on Discord or DM ectoBiologist on Discord.<br>
                5. Don't spam places. Use your {{ Auth::user()->place_slots_left }} place slots very wisely.<br>
                6. NO EXPLOITING. This is strictly forbidden and will result in a permanent ban.<br>
                7. Repetitive places/spam places are not allowed. Personally, if you make those type of places, fuck you.<br><br>

                Those are all the rules. For a better list, please check the #rules channel in the Discord servers.
                <br>
                Last updated: <code>9/7/2024 5:21PM</code>
            </p>
        </x-card>
    </div>
</x-app-layout>
@endauth

@guest
<x-guest-layout>
<nav class="navbar navbar-expand-lg navbar-dark bg-success border-bottom shadow-sm ">
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

<div class="container mt-5">
        <x-card :title="'Rules'">
            <p class="text-muted mb-0">
                Welcome to {{ config('app.name', 'Laravel') }}! We are a old Roblox revival. <br>
                1. No spamming allowed. This can result in a 2 day ban.<br>
                2. No slurs. Slurs are strictly forbidden and will result in a permanent ban. (Yes, this includes the "F" slur and the "N" slur.)<br>
                3. You must be over 13 years old to play {{ config('app.name', 'Laravel') }}. This can result in a permanent ban from both our Discord and the {{ config('app.name', 'Laravel') }} site.<br>
                4. Please don't harass people. If you need to file a report, just do it in the #reports channel on Discord or DM ectoBiologist on Discord.<br>
                5. Don't spam places. Use your 2 place slots very wisely.<br>
                6. Repetitive places/spam places are not allowed. Personally, if you make those type of places, fuck you.<br><br>

                Those are all the rules. For a better list, please check the #rules channel in the Discord servers.
                <br>
                Last updated: <code>9/6/2024 9:16PM</code>
            </p>
        </x-card>
    </div>

</x-guest-layout>
@endguest