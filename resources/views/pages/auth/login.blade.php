@extends('layouts.app')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<x-auth-card title="Welcome back" subtitle="Sign in to your account">
    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <x-input 
            label="Email address" 
            name="email" 
            type="email" 
            required 
            autocomplete="email"
            autofocus
        />

        <!-- Password -->
        <x-input 
            label="Password" 
            name="password" 
            type="password" 
            required 
            autocomplete="current-password"
        />

        <div class="flex items-center justify-between">
            <!-- Remember Me -->
            <x-checkbox label="Remember me" />

            <!-- Forgot Password -->
            <a href="#" class="text-sm font-medium text-slate-600 hover:text-slate-900 hover:underline transition">
                Forgot password?
            </a>
        </div>

        <!-- Submit Button -->
        <x-button type="submit" variant="primary" class="w-full">
            Sign in
        </x-button>

        <!-- Register Link -->
        <p class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-slate-900 hover:underline transition">
                Create one now
            </a>
        </p>
    </form>
</x-auth-card>
@endsection
