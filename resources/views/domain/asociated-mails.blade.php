<div class="container bg-light border py-2 mb-4" id="asociated-emails">
    <h3>Emails asociados al dominio</h3>
    <ul class="list-group">
        @foreach ($emails as $email)
            <li class="list-group-item">
                <a href="/emails/{{ $email->id }}" class="text-dark text-decoration-none">{{ $email->name }}</a>
            </li>
        @endforeach
    </ul>
</div>
