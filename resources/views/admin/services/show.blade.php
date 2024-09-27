@extends('layouts.app')

@section('title', $service->title . ' | Service')

@section('content')
    @livewire('admin.services.show', ['service' => $service])
@endsection
