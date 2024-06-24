<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@^2.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="min-h-screen flex flex-col">
        <header class=" bg-gray-800 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-lg"><a href="/"></a>@yield('header', 'Welcome!')</h1>
                <nav>
                    <a href="/login" class="text-blue-200 hover:text-blue-400 mx-2">Login</a>
                    <a href="/register" class="text-blue-200 hover:text-blue-400">Register</a>
                </nav>
            </div>
        </header>

        @if (isset($error) && $error)
        <p class="bg-red-100 text-red-500 p-3 rounded text-center">
            {{ $error }}
        </p>
        @endif

        @if(isset($success) && $success)
        <p class="bg-green-100 text-green-500 p-3 rounded text-center">
            {{ $success }}
        </p>
        @endif

        <div class="flex flex-col justify-center mx-auto flex-grow bg-gray-200 w-full">
            @yield('content')
        </div>

        <hr>
        <footer class="text-center text-gray-600 py-4">
            © {{ date('Y') }} Florian Klaessen
        </footer>
    </div>


    @yield('scripts')
</body>

</html>