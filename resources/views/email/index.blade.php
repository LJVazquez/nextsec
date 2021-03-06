@extends('layout')

@section('content')

    <div class="container">
        <h1 class="text-primary">Lista de emails</h1>
        <ul class="list-group">
            @foreach ($emails as $email)
                <li class="list-group-item list-group-item-primary">
                    <a href="/emails/{{ $email->id }}" style="text-decoration: none">{{ $email->name }}</a>

                </li>
            @endforeach
        </ul>
        @if (session('message'))
            <p class="text-success">{{ session('message') }}</p>
        @endif
        <a href="/emails/create" class="btn btn-primary mt-1">Crear</a>

    </div>

@endsection
