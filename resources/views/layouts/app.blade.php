<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/custom.css" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/themes/{{ Auth::user()->theme }}.css" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@663d232/css/all.css" rel="stylesheet" type="text/css" />

    <title>@yield('title', 'no title defined') - {{ config('app.name', 'Laravel') }}</title>
  </head>
  <body>

    @include('layouts.navigation')

    {{ $slot }}

    <div class="container my-5">
      <div class="card card-body">
        <p class="mb-0 fw-medium text-muted mb-0">{{ config('app.name', 'Laravel') }} &copy; {{ date('Y') }} <span class="px-1">|</span> {{ config('app.name', 'Laravel') }} is not affiated with the ROBLOX corporation or any other corporation. All characters and binaries belong to ROBLOX.<br> Frontend inspired by Finobe. <span class="px-1">|</span> Built with Laravel</p>
        <p class="mb-0"><a href="/app/policy" class="text-success">Privacy Policy</a> <span class="px-1">|</span> <a href="/app/rules" class="text-success">Rules</a></p>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
    <script>
      let formattedPrice = numeral({{ Auth::user()->peeps }}).format('0.0a');

      document.getElementById('userPeeps').innerHTML = formattedPrice;
    </script>
    <script>
        $(function () {
  $('[data-toggle="popover"]').popover()
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});


    </script>
  </body>
</html>
