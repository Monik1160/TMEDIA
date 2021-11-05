<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') | {{config('backpack.base.project_name')}}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('vendor.playground.theme.partials.favicons')
    <link href="{{ asset('/packages/playground/css/laraspace.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/login_page.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset(mix('assets/css/auth.css')) }}" rel="stylesheet" type="text/css">

</head>
<body class="login-page @yield('body-css')">
