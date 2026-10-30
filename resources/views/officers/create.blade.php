@extends('layouts.app')
@section('title', 'Add officer')
@section('content')
<div class="mx-auto max-w-3xl">
    <h1 class="mb-4 text-2xl font-semibold">Add officer</h1>
    @include('officers._form', ['action' => route('officers.store'), 'officer' => new \App\Models\Officer])
</div>
@endsection
