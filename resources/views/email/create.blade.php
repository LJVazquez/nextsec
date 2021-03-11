@extends('layout')

@section('content')
    <div class="container border bg-light py-2">
        <h2>Crear email</h2>
        @if (session('create-error'))
            <p class="text-danger">{{ session('create-error') }}</p>
        @endif
        <form method="post" action="/emails">
            @csrf

            <div class="form-check">
                <input id="use_user" class="form-check-input" type="checkbox" name="use_user" value="true"
                    onchange="document.querySelectorAll('.full_name').forEach(elem => elem.disabled = this.checked);">
                <label for="use_user" class="form-check-label">Usar mi nombre y apellido</label>
            </div>

            <div class="form-group ">
                <label for="first_name">Nombre:</label>
                <input class="form-control full_name" type="text" name="first_name" id="first_name"
                    value="{{ old('first_name') }}" placeholder="Juan">
            </div>

            <div class="form-group">
                <label for="last_name">Apellido:</label>
                <input class="form-control full_name" type="text" name="last_name" id="last_name"
                    value="{{ old('last_name') }}" placeholder="Perez">
            </div>

            <div class="form-group">
                <label for="name">Email:</label>
                <input class="form-control" type="email" name="name" id="name" value="{{ old('name') }}"
                    placeholder="Ej. contacto" required>
                @error('name')
                    <p class="text-danger">{{ $errors->first('name') }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="domain">Asociar email a dominio:</label>
                <select id="domain" class="form-control" name="domain">
                    <option></option>
                    @foreach ($domains as $domain)
                        <option value={{ $domain->id }}>{{ $domain->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary my-2" type="submit">Crear</button>
            <a href="/" onClick="return confirm('¿Volver y cancelar cambios?')" class="btn btn-danger my-2">Cancelar</a>
        </form>
    </div>

@endsection
