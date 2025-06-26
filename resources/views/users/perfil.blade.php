@extends('layouts.ap')

@section('content')
<div class="container">
    <h3>Mi Perfil</h3>
    <table class="table">
        <tr>
            <th>Nombre:</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Rol:</th>
            <td>{{ $user->getRoleNames()->implode(', ') }}</td>
        </tr>
        <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-primary btn-sm">Editar</a>

    </table>
</div>
@endsection