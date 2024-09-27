@extends('layouts.app')

@section('title', Str::limit($phase->title,20) . ' | Phase')

@section('content')
    @livewire('admin.phases.show', [$phase])
@endsection
