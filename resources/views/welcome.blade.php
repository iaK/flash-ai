<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="antialiased">
        <div class="flex mx-auto max-w-4xl w-full py-10">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach($flashCards as $card)
                    @if(trim($card->front) == "")
                        @continue
                    @endif
                    <div class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400">
                        {{-- <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        </div> --}}
                        <div class="min-w-0 flex-1">
                        <a x-data="{open: false}" @click="open = !open" href="#" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900 mb-2">{{ $card->front  }}</p>
                            <p x-show="open" class="text-sm text-gray-500">{{ $card->back }}</p>
                        </a>
                        </div>
                    </div>
                @endforeach
                <!-- More people... -->
            </div>
        </div>
    </body>
</html>
