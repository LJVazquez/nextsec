@extends('layout')

@section('content')
    <h2>Crear dominio</h2>
    <form method="post" action="/domains">
        @csrf
        <div class="form-group">
            <label for="name">Nombre del dominio:</label>
            <input class="form-control" type="text" name="name" id="name" placeholder="Ej. google.com" required>
        </div>

        <div class="form-group">
            <label for="user">Usuario (este campo se borra final)</label>
            <select id="user" class="form-control" name="user">
                @foreach ($users as $user)
                    <option value={{ $user->id }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary mt-1" type="submit">Crear</button>
    </form>

@endsection
