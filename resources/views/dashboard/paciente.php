@extends('layouts.app')

@section('title', 'Mis Citas')

@section('content')
<div class="container">
    <h2>Mis Citas Programadas</h2>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Médico</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($citas as $cita)
                <tr>
                    <td>{{ $cita->medico->nombre }}</td>
                    <td>{{ $cita->fecha }}</td>
                    <td>{{ $cita->hora }}</td>
                    <td>{{ $cita->motivo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
