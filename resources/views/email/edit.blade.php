@extends('layout')

@section('content')
    <div class="container border bg-light py-2">
        <h2>Editar email</h2>
        @if (session('create-error'))
            <p class="text-danger">{{ session('create-error') }}</p>
        @endif
        <form method="post" action="/emails/{{ $email->id }}">
            @csrf
            @method('PUT')
            <div class="form-group ">
                <label for="first_name">Nombre:</label>
                <input class="form-control full_name" type="text" name="first_name" id="first_name"
                    value="{{ $email->first_name }}" placeholder="Juan">
            </div>
            <div class="form-group">
                <label for="last_name">Apellido:</label>
                <input class="form-control full_name" type="text" name="last_name" id="last_name"
                    value="{{ $email->last_name }}" placeholder="Perez">
            </div>

            <div class="form-group">
                <label for="domain">Asociar email a dominio:</label>
                <select id="domain" class="form-control" name="domain">
                    <option value="none">Ninguno</option>
                    @foreach ($domains as $domain)
                        @if ($domain->id === $email->domain_id)
                            <option selected="selected" value={{ $domain->id }}>{{ $domain->name }}</option>
                        @endif
                        <option value={{ $domain->id }}>{{ $domain->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary my-2" type="submit">Actualizar</button>
            <a href="/" onClick="return confirm('¿Volver y cancelar cambios?')" class="btn btn-danger my-2">Cancelar</a>
        </form>
    </div>

@endsection
