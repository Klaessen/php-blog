@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="container mx-auto p-8">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-700">Hello, {{ $user->name }}!</h2>
        <p>Welcome to your dashboard. Here's what's happening with your articles:</p>
    </div>

    @if(count($articles) > 0)
    <div class="space-y-4">
        @foreach($articles as $article)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $article['title'] }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $article['content'] }}</p>
        </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $articles }}
        <!-- If you're using simple pagination, adjust accordingly -->
    </div>
    @else
    <p>No articles to display.</p>
    @endif
</div>
@endsection