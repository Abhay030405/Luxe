@extends('layouts.app')

@section('title', 'Register - ' . config('app.name'))

@section('content')
<x-auth-card title="Create your account" subtitle="Join us and start shopping">
    <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <x-input 
            label="Full name" 
            name="name" 
            type="text" 
            required 
            autocomplete="name"
            autofocus
        />

        <!-- Email -->
        <x-input 
            label="Email address" 
            name="email" 
            type="email" 
            required 
            autocomplete="email"
        />

        <!-- Password -->
        <x-input 
            label="Password" 
            name="password" 
            type="password" 
            required 
            autocomplete="new-password"
        />

        <!-- Password Confirmation -->
        <div>
            <x-input 
                label="Confirm password" 
                name="password_confirmation" 
                type="password" 
                required 
                autocomplete="new-password"
            />
            
            <p class="mt-1 text-xs text-gray-500">
                Minimum 8 characters required
            </p>
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-start">
            <input 
                type="checkbox"
                name="terms"
                id="terms"
                required
                class="mt-0.5 h-4 w-4 rounded border-gray-300 text-slate-900 focus:ring-slate-900 transition"
            />
            <label for="terms" class="ml-2 block text-sm text-gray-900">
                I agree to the
                <a href="#" class="font-medium text-slate-900 hover:underline transition">Terms of Service</a>
                and
                <a href="#" class="font-medium text-slate-900 hover:underline transition">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit Button -->
        <x-button type="submit" variant="primary" class="w-full">
            Create account
        </x-button>

        <!-- Login Link -->
        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-slate-900 hover:underline transition">
                Sign in
            </a>
        </p>
    </form>
</x-auth-card>
@endsection
