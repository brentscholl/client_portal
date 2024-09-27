@extends('layouts.app')

@section('title', 'Files')

@section('content')
    @livewire('admin.files.index', ['selected_id' => $selected_id])
@endsection
