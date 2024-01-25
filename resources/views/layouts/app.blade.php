<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href={{asset('logo-factura.png')}} type="image/png">
        


        {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik+Doodle+Shadow&display=swap" rel="stylesheet"> --}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:ital@1&display=swap" rel="stylesheet">


        <link rel="stylesheet" href="https://unpkg.com/tippy.js@6.3.2/dist/tippy.css" integrity="sha384-KJcc5i4hFA0plJ8r+9e0HpAzstJKpOTFZZ84lCvNU4Aq5bM2XdI8pFbKVA8DB7yB" crossorigin="anonymous">
        <script src="https://unpkg.com/tippy.js@6.3.2/dist/tippy.all.min.js" integrity="sha384-s3HyohJS5j5ADwqh3Qj3sVef5L+8CEaA7IMoD4PFFl4ylyj7Gn/E3guCPsziPXi" crossorigin="anonymous"></script>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @livewireStyles
      <link rel="stylesheet" href="{{ asset('css/app.css') }}">    
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Scripts -->

     <style>
         [x-cloak] { display: none; }
     </style>


    </head>
    <body class="background-black dark font-sans antialiased">
        <x-jet-banner />

        <div class="background-black min-h-screen ">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
      <script src="{{ asset('js/app.js') }}" defer></script>
        @livewireScripts


        @yield('scripts')
    </body>
</html>
