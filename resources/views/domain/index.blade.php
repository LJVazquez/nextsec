@extends('layout')

@section('content')

    <div class="container">
        <h1 class="text-primary">List of domains</h1>
        <ul class="list-group">
            @foreach ($domains as $domain)
                <li class="list-group-item list-group-item-primary">
                    <a href="/domains/{{ $domain->id }}" style="text-decoration: none">{{ $domain->name }}</a>
                </li>
            @endforeach
        </ul>
        @if (session('message'))
            <p class="text-success">{{ session('message') }}</p>
        @endif
        <a href="/domains/create" class="btn btn-primary mt-1">Crear</a>
    </div>

@endsection
