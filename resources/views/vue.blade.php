<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
  <script defer type="text/javascript" src="{{ elixir('js/app.js') }}"></script>
</head>
<body>
  <div id="app"></div>
</body>
</html>