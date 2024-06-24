@extends('app')

@section('title', 'Home Page')
@section('header', 'Welcome to the PHP Demo Project!')

@section('content')
<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            PHP Demo Project
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Personal insights and demonstration of handling PHP without a framework.
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Project Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    This project is a simple demo designed to show how you can build a functional PHP application using
                    BladeOne for templating and Tailwind CSS for styling, all without relying on a full PHP framework
                    like Laravel.
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Why No Framework?
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    Working without a framework can provide deeper insights into the underlying mechanics of web
                    development, improve performance for small projects, and reduce server dependencies.
                </dd>
            </div>
        </dl>
    </div>
</div>
@endsection