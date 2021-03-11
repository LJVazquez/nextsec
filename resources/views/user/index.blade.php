@extends('layout')

@section('content')

    <div class="container">
        <h1>Users, domains and emails list</h1>
        @foreach ($users as $user)
            <ul class="list-group">
                <li class="list-group-item list-group-item-info">
                    <a href="/users/{{ $user->id }}" style="text-decoration:none">{{ $user->name }}</a>
                    @foreach ($user->domains as $domain)

                <li class="list-group-item">
                    <a href="/domains/{{ $domain->id }}" style="text-decoration:none">{{ $domain->name }}</a>
                    <ul>
                        @foreach ($domain->emails as $email)
                            <li class="list-group-item list-group-item-info">
                                <a href="/emails/{{ $email->id }}"
                                    style="text-decoration:none">{{ "$email->name@$domain->name" }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                </li>

        @endforeach
        </ul>

        @endforeach
    </div>

@endsection
