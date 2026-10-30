@extends('layouts.app')
@section('title', 'Add department')
@section('content')
<div class="mx-auto max-w-xl">
    <h1 class="mb-4 text-2xl font-semibold">Add department</h1>
    @include('departments._form', ['action' => route('departments.store'), 'department' => new \App\Models\Department])
</div>
@endsection
