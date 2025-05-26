<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Inicio</div>

                <a class="nav-link" href="{{ route('panel') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Inicio
                </a>
                <!-- -->
                <div class="sb-sidenav-menu-heading">Módulos2</div>


                @canany(['ver usuarios', 'ver roles'])
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseUsuarios" aria-expanded="false" aria-controls="collapseUsuarios">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Usuarios
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUsuarios" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('ver usuarios')
                        <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                        @endcan
                        @can('ver roles')
                        <a class="nav-link" href="{{ route('roles.index') }}">Roles y Permisos</a>
                        @endcan
                    </nav>
                </div>
                @endcanany


                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseAdministracion" aria-expanded="false"
                    aria-controls="collapseAdministracion">
                    <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                    Administración Interna
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAdministracion" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">

                        {{-- Empleados con submenú --}}
                        @canany(['ver empleados', 'ver cargos'])
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseEmpleados" aria-expanded="false" aria-controls="collapseEmpleados">
                            Empleados
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseEmpleados">
                            <nav class="sb-sidenav-menu-nested nav">
                                @can('ver empleados')
                                <a class="nav-link" href="{{ route('empleados.index') }}">Lista de Empleados</a>
                                @endcan
                                @can('ver cargos')
                                <a class="nav-link" href="{{ route('cargos.index') }}">Cargos de Empleados</a>
                                @endcan
                            </nav>
                        </div>
                        @endcanany

                        {{-- Otras secciones --}}
                        @can('ver residentes')
                        <a class="nav-link" href="{{ route('residentes.index') }}">Residentes</a>
                        @endcan
                        @can('ver unidades')
                        <a class="nav-link" href="{{ route('unidades.index') }}">Unidades</a>
                        @endcan
                        @can('ver empresas')
                        <a class="nav-link" href="{{ route('empresas.index') }}">Empresas Externas</a>
                        @endcan
                        @can('ver mantenimiento')
                        <a class="nav-link" href="#">Mantenimiento</a>
                        @endcan
                    </nav>
                </div>


                {{-- Finanzas y Áreas Comunes --}}
@canany(['ver cuotas', 'ver tipos de cuotas', 'ver pagos', 'ver gastos', 'ver tipos de gastos'])
<a class="nav-link {{ request()->routeIs('cuotas.*') || request()->routeIs('tipos-cuotas.*') ? '' : 'collapsed' }}"
    href="#" data-bs-toggle="collapse" data-bs-target="#collapseFinanzas"
    aria-expanded="{{ request()->routeIs('cuotas.*') || request()->routeIs('tipos-cuotas.*') ? 'true' : 'false' }}"
    aria-controls="collapseFinanzas">
    <div class="sb-nav-link-icon"><i class="fas fa-hand-holding-usd"></i></div>
    Finanzas y Áreas Comunes
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse {{ request()->routeIs('cuotas.*') || request()->routeIs('tipos-cuotas.*') || request()->routeIs('pagos.*') || request()->routeIs('gastos.*') || request()->routeIs('tipo-gastos.*') ? 'show' : '' }}"
    id="collapseFinanzas">
    <nav class="sb-sidenav-menu-nested nav">

        {{-- Submenú: Cuotas y Pagos --}}
        @canany(['ver cuotas', 'ver tipos de cuotas', 'ver pagos'])
        <a class="nav-link {{ request()->routeIs('cuotas.*') || request()->routeIs('tipos-cuotas.*') ? '' : 'collapsed' }}"
            href="#" data-bs-toggle="collapse" data-bs-target="#collapseCuotas"
            aria-expanded="{{ request()->routeIs('cuotas.*') || request()->routeIs('tipos-cuotas.*') ? 'true' : 'false' }}"
            aria-controls="collapseCuotas">
            <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
            Cuotas y Pagos
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse {{ request()->routeIs('cuotas.*') || request()->routeIs('tipos-cuotas.*') || request()->routeIs('pagos.*') ? 'show' : '' }}"
            id="collapseCuotas">
            <nav class="sb-sidenav-menu-nested nav">
                @can('ver cuotas')
                <a class="nav-link {{ request()->routeIs('cuotas.index') ? 'active' : '' }}"
                    href="{{ route('cuotas.index') }}">Lista de Cuotas</a>
                @endcan
                @can('ver tipos de cuotas')
                <a class="nav-link {{ request()->routeIs('tipos-cuotas.index') ? 'active' : '' }}"
                    href="{{ route('tipos-cuotas.index') }}">Tipos de Cuotas</a>
                @endcan
                @can('ver pagos')
                <a class="nav-link {{ request()->routeIs('pagos.index') ? 'active' : '' }}"
                    href="{{ route('pagos.index') }}">Pagos Realizados</a>
                @endcan
            </nav>
        </div>
        @endcanany

        {{-- Submenú: Gestión de Gastos --}}
        @canany(['ver gastos', 'ver tipos de gastos'])
        <a class="nav-link {{ request()->routeIs('tipo-gastos.*') || request()->routeIs('gastos.*') ? '' : 'collapsed' }}"
            href="#" data-bs-toggle="collapse" data-bs-target="#collapseGastos"
            aria-expanded="{{ request()->routeIs('tipo-gastos.*') || request()->routeIs('gastos.*') ? 'true' : 'false' }}"
            aria-controls="collapseGastos">
            <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
            Gestión de Gastos
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse {{ request()->routeIs('tipo-gastos.*') || request()->routeIs('gastos.*') ? 'show' : '' }}"
            id="collapseGastos">
            <nav class="sb-sidenav-menu-nested nav">
                @can('ver gastos')
                <a class="nav-link {{ request()->routeIs('gastos.index') ? 'active' : '' }}"
                    href="{{ route('gastos.index') }}">Lista de Gastos</a>
                @endcan
                @can('ver tipos de gastos')
                <a class="nav-link {{ request()->routeIs('tipo-gastos.index') ? 'active' : '' }}"
                    href="{{ route('tipo-gastos.index') }}">
                    Tipos de Gastos
                </a>
                @endcan
            </nav>
        </div>
        @endcanany

        {{-- Otras secciones visibles sin permisos aún --}}
        <a class="nav-link" href="#">Áreas comunes</a>
        <a class="nav-link" href="#">Multas y sanciones</a>
    </nav>
</div>
@endcanany






                @canany(['ver calificaciones', 'ver comunicados', 'ver reclamos', 'ver notificaciones'])
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseComunicacion" aria-expanded="false"
                    aria-controls="collapseComunicacion">
                    <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                    Comunicación y Atención al Residente
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseComunicacion" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('ver calificaciones')
                        <a class="nav-link" href="#">Calificaciones de Servicios</a>
                        @endcan
                        @can('ver comunicados')
                        <a class="nav-link" href="#">Comunicados y Noticias</a>
                        @endcan
                        @can('ver reclamos')
                        <a class="nav-link" href="#">Reclamos y Sugerencias</a>
                        @endcan
                        @can('ver notificaciones')
                        <a class="nav-link" href="#">Notificaciones</a>
                        @endcan
                    </nav>
                </div>
                @endcanany

                {{-- Seguridad y Accesos --}}
                @canany(['ver control de acceso', 'ver visitas', 'ver invitaciones', 'ver vigilancia'])
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseSeguridad" aria-expanded="false" aria-controls="collapseSeguridad">
                    <div class="sb-nav-link-icon"><i class="fas fa-shield-alt"></i></div>
                    Seguridad y Accesos
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSeguridad" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('ver control de acceso')
                        <a class="nav-link" href="#">Control de Acceso</a>
                        @endcan
                        @can('ver visitas')
                        <a class="nav-link" href="#">Visitas</a>
                        @endcan
                        @can('ver invitaciones')
                        <a class="nav-link" href="#">Invitación de Visitas</a>
                        @endcan
                        @can('ver vigilancia')
                        <a class="nav-link" href="#">Seguridad y Vigilancia</a>
                        @endcan
                    </nav>
                </div>
                @endcanany

                {{-- Comunidad y Reportes --}}
                @canany(['ver agenda', 'ver reportes', 'ver documentos', 'ver asambleas', 'ver foro'])
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseComunidad" aria-expanded="false" aria-controls="collapseComunidad">
                    <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                    Comunidad y Reportes
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseComunidad" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        @can('ver agenda')
                        <a class="nav-link" href="#">Agenda de Eventos</a>
                        @endcan
                        @can('ver reportes')
                        <a class="nav-link" href="#">Reportes</a>
                        @endcan
                        @can('ver documentos')
                        <a class="nav-link" href="#">Documentos del Condominio</a>
                        @endcan
                        @can('ver asambleas')
                        <a class="nav-link" href="#">Asambleas Vecinales</a>
                        @endcan
                        @can('ver foro')
                        <a class="nav-link" href="#">Foro Vecinal</a>
                        @endcan
                    </nav>
                </div>
                @endcanany

                {{-- Bitácora --}}
                @can('ver bitacora')
                <a class="nav-link" href="{{ route('bitacora.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                    Bitácora
                </a>
                @endcan

                {{-- Salir (Logout) --}}
                <a class="nav-link" href="{{ route('logout') }}">
                    <div class="sb-nav-link-icon"><i class="fa fa-sign-out" aria-hidden="true"></i></div>
                    Salir
                </a>


            </div>
        </div>
    </nav>
</div>