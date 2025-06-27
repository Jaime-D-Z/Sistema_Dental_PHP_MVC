<nav class="navbar navbar-expand-lg navbar-dark header shadow-sm">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Clínica Dental</span>

        <div class="d-flex align-items-center ms-auto">
            <span class="me-3">👤 {{ Auth::user()->name ?? 'Invitado' }}</span>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
