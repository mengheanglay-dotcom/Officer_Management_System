@extends('layouts.app')
@section('title', 'Edit department')
@section('content')
    <div class="mx-auto max-w-xl">
        <h1 class="mb-4 text-2xl font-semibold">Edit department</h1>
        <p class="mb-4 text-sm text-slate-500">Update the department details.</p>
        @include('departments._form', ['action' => route('departments.update', $department)])
    </div>
@endsection