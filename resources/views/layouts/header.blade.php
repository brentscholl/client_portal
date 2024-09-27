<?php
    /**
     * Template Displays Head section
     *
     * @package         Stealth Client Portal
     * @author          Stealth Media
     * @copyright       2020 Stealth Media
     * @link            http://www.stealthmedia.com
     * @since           1.0.0
     */
?>

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>
        @yield('title', 'Stealth Client Portal')
    </title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="theme-color" content="#122e34"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.meta')

    @yield('meta')

    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

{{--    <link href="https://use.typekit.net/meh1zjj.css" rel="stylesheet">--}}


    <!-- Style Sheets -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    @stack('styles')
    <link href="{{ asset('css/app.css') }}" media="all" rel="stylesheet" type="text/css"/>
    @yield('styles')
    @livewireStyles

    @yield('scripts.header')

{{--    <script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=q2irzdk9kujssru5ftc5kq" async="true"></script>--}}
</head>
<body class="antialiased">

<livewire:flash-container />
@yield('alert')
