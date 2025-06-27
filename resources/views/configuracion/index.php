@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">⚙️ Configuración General</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('configuracion.update', $configuracion->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre_clinica" class="form-label">Nombre de la Clínica</label>
            <input type="text" class="form-control" name="nombre_clinica" value="{{ $configuracion->nombre_clinica }}" required>
        </div>

        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" name="direccion" value="{{ $configuracion->direccion }}" required>
        </div>

        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" value="{{ $configuracion->telefono }}" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" name="correo" value="{{ $configuracion->correo }}" required>
        </div>

        <div class="mb-3">
            <label for="horario_inicio" class="form-label">Horario de inicio</label>
            <input type="time" class="form-control" name="horario_inicio" value="{{ $configuracion->horario_inicio }}">
        </div>

        <div class="mb-3">
            <label for="horario_fin" class="form-label">Horario de fin</label>
            <input type="time" class="form-control" name="horario_fin" value="{{ $configuracion->horario_fin }}">
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Logo actual</label><br>
            @if($configuracion->logo)
                <img src="{{ asset('storage/' . $configuracion->logo) }}" alt="Logo" height="80">
            @endif
            <input type="file" class="form-control mt-2" name="logo">
        </div>

        <button type="submit" class="btn btn-primary">Guardar Configuración</button>
    </form>
</div>
@endsection
