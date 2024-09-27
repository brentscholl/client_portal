@extends('layouts.app')

@section('title', $team->title . ' | Team')

@section('content')
    @livewire('admin.teams.show', [$team])
@endsection
