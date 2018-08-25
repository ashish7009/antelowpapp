<!-- Meta Tags -->
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="base-url" content="{{ url('/') }}" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />

<!-- Title -->
<title>{{ config('app.constants.website') }} | Admin @if(isset($pagetitle)) {{ $pagetitle }} @else @yield('pagetitle') @endif</title>

<!-- fav icon -->
<link rel="shortcut icon" href="{{ asset('/manage/images/favicon.ico') }}" />

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/adminlte/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/adminlte/css/skins/skin-blue.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck/square/yellow.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/alertify/css/alertify.core.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/alertify/css/alertify.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('manage/css/style.css?cache=1.2') }}">