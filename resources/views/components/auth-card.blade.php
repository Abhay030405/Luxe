@props(['title', 'subtitle' => null])

<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo -->
        <div class="flex justify-center">
            <a href="/" class="flex items-center space-x-2">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-blue-600 to-purple-600">
                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Title -->
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
            {{ $title }}
        </h2>
        
        @if($subtitle)
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white px-6 py-12 shadow-xl rounded-lg sm:px-12 border border-gray-200">
            {{ $slot }}
        </div>
    </div>
</div>
