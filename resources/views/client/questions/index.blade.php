@extends('layouts.app')

@section('title', 'Questionnaire')

@section('content')
    <div class="min-h-screen py-12 bg-gray-50 sm:px-6 lg:px-8">
        <h1 class="text-center text-2xl font-bold leading-7 text-gray-900">Questionnaire</h1>
        @livewire('client.questions.show-all')
    </div>
@endsection
