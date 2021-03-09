@extends('layout')

@section('content')
    <h2>Crear email</h2>
    <form method="post" action="/emails">
        @csrf

        <div class="form-check">
            <input id="use_user" class="form-check-input" type="checkbox" name="use_user" value="true"
                onchange="document.querySelectorAll('.full_name').forEach(elem => elem.disabled = this.checked);">
            <label for="use_user" class="form-check-label">Usar mi nombre y apellido</label>
        </div>

        <div class="form-group ">
            <label for="first_name">Nombre:</label>
            <input class="form-control full_name" type="text" name="first_name" id="first_name" placeholder="Juan" required>
        </div>
        <div class="form-group">
            <label for="last_name">Apellido:</label>
            <input class="form-control full_name" type="text" name="last_name" id="last_name" placeholder="Perez" required>
        </div>
        <div class="form-group">
            <label for="name">Email:</label>
            <input class="form-control" type="text" name="name" id="name" placeholder="Ej. contacto" required>
        </div>

        <div class="form-group">
            <label for="domain">Asociar email a dominio:</label>
            <select id="domain" class="form-control" name="domain">
                <option value="none">Ninguno</option>
                @foreach ($domains as $domain)
                    <option value={{ $domain->id }}>{{ "@$domain->name" }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary mt-1" type="submit">Crear</button>
    </form>

@endsection
