@extends('layouts.app')

@section('title', Str::limit($tutorial->title,20) . ' | Tutorial')

@section('content')
    @livewire('admin.tutorials.show', [$tutorial])
@endsection
