@extends('layouts.app')

@section('title', Str::limit($package->title,20) . ' | Package')

@section('content')
    @livewire('admin.packages.show', ['package' => $package])
@endsection
