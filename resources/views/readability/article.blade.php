<!doctype html>
<html lang="{{ $lang }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito-sans:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=source-serif-4:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

        @vite('resources/css/app.css')
    </head>
    <body>
        <main class="prose font-serif">
            <h1>{{ $title }}</h1>

            <p class="pb-4">
                <a href="{{ $url }}">
                    {{ $url }}
                </a>
            </p>

            @if ($author)
                <h2>@lang('readability.article.by', ['author' => $author])</h2>
            @endif

            <div>{!! $content !!}</div>
        </main>
    </body>
</html>
