<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" type="image/png" href="{{asset('favicon.png')}}" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,500i,600,700,800" rel="stylesheet"> 
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

  {{-- my css --}}
  <!-- <link rel="stylesheet" href="{{ asset('css/scrolltop.css') }}">
  <link rel="stylesheet" href="{{ asset('css/send_sms.css') }}"> -->

  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  
  <title>{{ config('app.name') }}</title>

  <script>
    window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
    ]) !!};
  </script>
</head>

<body>
  <div id="app"></div>
  @routes
  <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>

  <!-- my libs js -->
  <!-- <script src="{{ asset('js/functions.js') }}"></script>
  <script src="{{ asset('js/checkbox.js') }}"></script>
  <script src="{{ asset('js/ready.js') }}"></script> -->
</body>

</html>