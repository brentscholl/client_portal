@extends('layouts.app')

@section('title', Str::limit($question->body,20) . ' | Question')

@section('content')
    @livewire('admin.questions.show', [$question])
@endsection
