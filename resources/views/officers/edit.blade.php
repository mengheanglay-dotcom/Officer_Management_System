@extends('layouts.app')
@section('title', 'Edit officer')
@section('content')
<div class="mx-auto max-w-3xl">
    <h1 class="mb-4 text-2xl font-semibold">Edit officer</h1>
    @include('officers._form', ['action' => route('officers.update', $officer)])
</div>
@endsection
