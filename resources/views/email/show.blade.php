@extends('layout')

@section('content')

    <div class="container">
        <h1 class="text-primary">Email</h1>
        <h2 class="text-info">{{ "$email->name@$domain->name" }}</h2>
        <p class="text-secondary">Registered by {{ $user->name }}</p>

    </div>

@endsection
