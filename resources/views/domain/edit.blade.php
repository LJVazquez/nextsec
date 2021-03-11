@extends('layout')

@section('content')
    <div class="container bg-light border py-2">
        <div class="row-12">
            <h2>Editar {{ $domain->name }}</h2>
        </div>
        @if (session('update-error'))
            <p class="text-danger">{{ session('update-error') }}</p>
        @endif
        <form method="post" action="/domains/{{ $domain->id }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nombre del dominio:</label>
                <input class="form-control" type="text" name="name" id="name" value="{{ $domain->name }}"
                    placeholder="Ej. google.com" required>
                @error('name')
                    <p class="text-danger">{{ $errors->first('name') }}</p>
                @enderror
            </div>
            <div class="form-group">
                <button class="btn btn-primary mt-1" type="submit">Actualizar</button>
                <a href="/" onClick="return confirm('¿Volver y cancelar cambios?')" class="btn btn-danger mt-1">Cancelar</a>
            </div>
        </form>
    </div>

@endsection
