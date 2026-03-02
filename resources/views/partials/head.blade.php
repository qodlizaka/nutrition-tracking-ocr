<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ ($title ?? __('No title')) . config('app.name') }}</title>

<link rel="icon" href="{{ asset('favicon.ico') }}?v={{ date('YmdHis') }}" sizes="any" type="image/x-icon">
<link rel="icon" href="{{ asset('favicon.svg') }}?v={{ date('YmdHis') }}" type="image/svg+xml">

<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
