<div class="container bg-light border py-2 mb-4">

    <div class="row">
        <h4>Busqueda de email por datos personales</h4>
        @if (session('search-person'))
            @if (session('search-person')[0] === 'success')
                <p class="text-success">{{ session('search-person')[1] }}</p>
            @else
                <p class="text-danger">{{ session('search-person')[1] }}</p>
            @endif
        @endif
        <form method="post" action="/person-search/{{ $domain->id }}">
            <div class="row">
                @csrf
                <div class="col-12 col-sm-6 col-md-4">
                    <input class="form-control" type="text" name="first_name" id="first_name" placeholder="Nombre"
                        required>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <input class="form-control" type="text" name="last_name" id="last_name" placeholder="Apellido"
                        required>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <button class="btn  bg-green-soft" type="submit">Buscar</button>
                </div>
            </div>
        </form>
        @if ($person)
            <div class="table-responsive mt-4">
                <h5>Ultima persona buscada</h5>
                <table id="hunter-person-results" class="table border bg-white mt-2">
                    <thead class="bg-green-soft">
                        <tr>
                            <th>Email</th>
                            <th>Datos personales</th>
                            <th>Verif</th>
                            <th>Prob</th>
                            <th>Fuentes</th>
                            <th>Asociar mail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>{{ $person ? $person->email : '-' }}</td>
                        <td>{{ ($person->first_name ? $person->first_name : '-' . ' ' . $person->last_name) ? $person->last_name : '' }}
                        </td>
                        <td>{{ $person->verified ? $person->verified : 'No' }}</td>
                        <td>{{ $person->confidence }}%</td>
                        <td>
                            <div style="height: 50px; overflow-y: scroll;">
                                <ul>
                                    @if (json_decode($person->sources) !== [])
                                        @foreach (json_decode($person->sources) as $source)
                                            <li>
                                                <a class="text-dark text-decoration-none"
                                                    href="{{ $source }}">{{ $source }}</a>
                                            </li>
                                        @endforeach
                                    @else
                                        <p class="text-warning">Sin fuentes</p>
                                    @endif
                                </ul>
                        </td>
                        <td>
                            <form action="/person-save/{{ $domain->id }}" method="post">
                                @csrf
                                <button class="btn  btn-primary" type="submit">Asociar</button>
                            </form>
                        </td>
                        @if (session('person-message'))
                            <p class="text-danger">{{ session('person-message') }}</p>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
