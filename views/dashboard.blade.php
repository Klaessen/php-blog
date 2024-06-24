@extends('app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="container mx-auto p-8">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-700">Hello, {{ $user->username }}!</h2>
        <p>Welcome to your dashboard. Here's what's happening with your articles:</p>
    </div>

    <!-- Articles Display -->
    @if(!empty($articles))
    <div class="space-y-4">
        @foreach($articles as $article)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $article['title'] }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $article['content'] }}</p>
            <small>Published on: {{ $article['created_at'] }}</small>
        </div>
        @endforeach
    </div>
    <div class="my-6 text-center text-blue-500">
        <a class="mr-2" href="?page={{ $page - 1 }}" class="{{ $page <= 1 ? 'invisible' : 'visible' }}">Previous</a>
        <a href="?page={{ $page + 1 }}" class="{{ count($articles) < $articlesPerPage ? 'invisible' : 'visible' }}">Next
        </a>
    </div>
    @else
    <p class="text-center my-2 font-bold">No articles to display.</p>
    <div class="my-6 text-center text-blue-500">
        <a href="?page={{ $page - 1 }}" class="{{ $page <= 1 ? 'invisible' : 'visible' }}">Previous</a>
    </div>
    @endif

    <!-- New Article Form -->
    <div class="mb-8">
        <form action="/articles/create" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Title
                </label>
                <input type="text" id="title" name="title" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                    Content
                </label>
                <textarea id="content" name="content" rows="4" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Publish Article
                </button>
            </div>
        </form>
    </div>
</div>
@endsection