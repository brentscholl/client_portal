@extends('layouts.app')

@section('title', Str::limit($user->fullname,20) . ' | User')

@section('content')
    @livewire('admin.users.show', [$user])
@endsection
