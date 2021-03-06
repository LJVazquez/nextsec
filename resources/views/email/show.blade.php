@extends('layout')

@section('content')

    <div class="container">
        <h1 class="text-primary">Email</h1>
        <h2 class="text-info">{{ "$email->name@$domain->name" }}</h2>
        <p class="text-secondary">Registered by {{ $user->name }}</p>
    </div>
    <div class="container">
        <form action="/emails/{{ $email->id }}" method="post">
            @csrf
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>
    </div>
    <div class="container" id="results">
        <h3>Resultados de la busqueda</h3>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. A quibusdam voluptates atque rem doloribus voluptatibus
            necessitatibus pariatur saepe, nobis quasi rerum vel id eos illo voluptate quam nam maiores neque!</p>
    </div>

@endsection
