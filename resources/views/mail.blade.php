@extends('layout')

@section('content')

    <form method="post" action="/email">
        @csrf

        <input class="form-control w-25 mb-2" type="email" id="email" name="email">
        @error('email')
            <p class="text-danger">{{ $message }}</p>
        @enderror
        <button class="btn btn-success" type="submit">enviar</button>
        @if (session('message'))
            <p class="text-success">{{ session('message') }}</p>
        @endif
    </form>

@endsection
