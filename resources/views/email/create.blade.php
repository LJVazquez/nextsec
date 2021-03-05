@extends('layout')

@section('content')
    <h2>Crear dominio</h2>
    <form method="post" action="/emails">
        @csrf
        <div class="form-group">
            <label for="name">Nombre del email:</label>
            <input class="form-control" type="text" name="name" id="name" placeholder="Ej. contacto" required>
        </div>

        <div class="form-group">
            <label for="domain">Dominio</label>
            <select id="domain" class="form-control" name="domain">
                @foreach ($domains as $domain)
                    <option value={{ $domain->id }}>{{ "@$domain->name" }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary mt-1" type="submit">Crear</button>
    </form>

@endsection
