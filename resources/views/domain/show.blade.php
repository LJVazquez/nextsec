@extends('layout')

@section('content')

    <div class="container">
        <h1 class="text-primary">Domain</h1>
        <h2 class="text-info">{{ $domain->name }}</h2>
        <p class="text-secondary">Registered by {{ $domain->user->name }}</p>
        <ul class="list-group">
            @foreach ($emails as $email)
                <li class="list-group-item list-group-item-primary">
                    <a href="/emails/{{ $email->id }}" style="text-decoration: none">{{ $email->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>

@endsection
