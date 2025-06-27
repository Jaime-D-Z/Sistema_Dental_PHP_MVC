@extends('layouts.app')

@section('title', 'Panel del Médico')

@section('content')
<div class="container">
    <h2>Citas asignadas</h2>
    <table class="table table-hover mt-4">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($citas as $cita)
                <tr>
                    <td>{{ $cita->paciente->nombre }}</td>
                    <td>{{ $cita->fecha }}</td>
                    <td>{{ $cita->hora }}</td>
                    <td>{{ $cita->motivo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
