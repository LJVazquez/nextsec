@extends('layout')

@section('content')
    <div class="container border bg-light py-2">
        <h2>Crear dominio</h2>
        @if (session('create-error'))
            <p class="text-danger">{{ session('create-error') }}</p>
        @endif
        <form method="post" action="/domains">
            @csrf
            <div class="form-group">
                <label for="name">Nombre del dominio:</label>
                <input class="form-control" type="text" name="name" id="name" placeholder="Ej. google.com" required>
            </div>
            <button class="btn btn-primary mt-1" type="submit">Crear</button>
            <a href="/" onClick="return confirm('¿Volver y cancelar cambios?')" class="btn btn-danger mt-1">Cancelar</a>
        </form>
    </div>

@endsection
