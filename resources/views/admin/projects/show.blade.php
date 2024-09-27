@extends('layouts.app')

@section('title', Str::limit($project->title,20) . ' | Project')

@section('content')

    @livewire('admin.projects.show', [$project])

@endsection
