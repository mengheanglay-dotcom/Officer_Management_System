@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="mx-auto mt-10 max-w-md rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-6 py-4 text-center">
        <i data-lucide="user-plus" class="mx-auto mb-1 h-8 w-8 text-indigo-600"></i>
        <h1 class="text-xl font-semibold">Create officer account</h1>
    </div>
    <form method="POST" action="{{ route('register') }}" class="space-y-4 p-6">
        @csrf
        @foreach ([['name','Name','text'],['email','Email address','email'],['password','Password','password'],['password_confirmation','Confirm password','password']] as [$n,$l,$t])
            <div>
                <label class="mb-1 block text-sm font-medium">{{ $l }}</label>
                <input type="{{ $t }}" name="{{ $n }}" value="{{ $t === 'password' ? '' : old($n) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                @error($n) <p class="mt-1 text-sm text-slate-600">{{ $message }}</p> @enderror
            </div>
        @endforeach
        <button class="flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 py-2.5 font-medium text-white hover:bg-indigo-700">
            <i data-lucide="user-plus" class="h-4 w-4"></i> Register
        </button>
        <p class="text-center text-sm text-slate-500">Already registered? <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:underline">Log in</a></p>
    </form>
</div>
@endsection
