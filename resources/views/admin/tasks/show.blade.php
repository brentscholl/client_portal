@extends('layouts.app')

@section('title', Str::limit($task->title,20) . ' | Task')

@section('content')
    @livewire('admin.tasks.show', [$task])
@endsection
