@extends('layouts.app')

@section('title', Str::limit($client->title,20) . ' | Client')

@section('content')
    @livewire('admin.clients.show', [$client])
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css"/>
@endsection
@section('scripts.header')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/plugins/char_counter.min.js"></script>
@endsection
