@props(['title', 'subtitle' => null])

<div class="flex min-h-[calc(100vh-4rem)] flex-col justify-center bg-gray-50 py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <!-- Logo -->
        <div class="flex justify-center">
            <a href="/" class="flex items-center space-x-2 group">
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-slate-900 shadow-md group-hover:bg-slate-800 transition-colors duration-200">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
            <p class="mt-2 text-center text-base text-gray-600">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[32rem]">
        <div class="bg-white px-10 py-16 shadow-lg sm:rounded-lg border border-gray-100">
            {{ $slot }}
        </div>
    </div>
</div>
