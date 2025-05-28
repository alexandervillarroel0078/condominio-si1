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
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdministracion"
                    aria-expanded="false" aria-controls="collapseAdministracion">
                    <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                    Administración Interna
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAdministracion" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">

                        {{-- Empleados con submenú --}}
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEmpleados"
                            aria-expanded="false" aria-controls="collapseEmpleados">
                            Empleados
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseEmpleados">
                            <nav class="sb-sidenav-menu-nested nav">
                                @can('ver ver empleado')

                                <a class="nav-link" href="{{ route('empleados.index') }}">Lista de Empleados</a>
                                @endcan
                                <a class="nav-link" href="{{ route('cargos.index') }}">Cargos de Empleados</a>

                            </nav>
                        </div>

                        {{-- Otras secciones --}}
                        {{-- Ver Residentes --}}
                        @can('ver residentes')
                        <a class="nav-link" href="{{ route('residentes.index') }}">Residentes</a>
                        @endcan

                        {{-- Ver Unidades --}}
                        @can('ver unidades')
                        <a class="nav-link" href="{{ route('unidades.index') }}">Unidades</a>
                        @endcan

                        {{-- Ver Empresas Externas --}}
                        @can('ver empresas')
                        <a class="nav-link" href="{{ route('empresas.index') }}">Empresas Externas</a>
                        @endcan

                        {{-- Ver Mantenimientos --}}
                        @can('ver mantenimiento')
                        <a class="nav-link" href="{{ route('mantenimientos.index') }}">Mantenimientos</a>
                        @endcan
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFinanzas"
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
                                <a class="nav-link {{ request()->routeIs('cuotas.index') ? 'active' : '' }}" href="{{ route('cuotas.index') }}">Lista de Cuotas</a>
                                <a class="nav-link {{ request()->routeIs('tipos-cuotas.index') ? 'active' : '' }}" href="{{ route('tipos-cuotas.index') }}">Tipos de Cuotas</a>
                                @endcan
                                @can('ver pagos')
                                <a class="nav-link {{ request()->routeIs('pagos.index') ? 'active' : '' }}" href="{{ route('pagos.index') }}">Pagos Realizados</a>
                                @endcan
                            </nav>
                        </div>

                        {{-- Submenú: Gestión de Gastos --}}
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
                                <a class="nav-link {{ request()->routeIs('gastos.index') ? 'active' : '' }}" href="{{ route('gastos.index') }}">Lista de Gastos</a>
                                <a class="nav-link {{ request()->routeIs('tipo-gastos.index') ? 'active' : '' }}" href="{{ route('tipo-gastos.index') }}">Tipos de Gastos</a>
                            </nav>
                        </div>

                        {{-- Gestión de Áreas Comunes --}}
                    <a  class="nav-link {{ request()->routeIs('areas-comunes.*') || request()->routeIs('reservas.*') ? '' : 'collapsed' }}"
                        href="#" data-bs-toggle="collapse" data-bs-target="#collapseAreas"
                        aria-expanded="{{ request()->routeIs('areas-comunes.*') || request()->routeIs('reservas.*') ? 'true' : 'false' }}"
                        aria-controls="collapseAreas">
                        <div class="sb-nav-link-icon"><i class="fas fa-swimming-pool"></i></div>
                        Gestión de Áreas Comunes
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div  class="collapse {{ request()->routeIs('areas-comunes.*') || request()->routeIs('reservas.*') ? 'show' : '' }}"
                        id="collapseAreas" data-bs-parent="#collapseFinanzas">
                        <nav class="sb-sidenav-menu-nested nav">
                            {{-- Catálogo de Áreas --}}
                            <a  class="nav-link {{ request()->routeIs('areas-comunes.index') ? 'active' : '' }}"
                                href="{{ route('areas-comunes.index') }}">Catálogo
                            </a>
                            {{-- Reservas --}}
                            <a  class="nav-link {{ request()->routeIs('reservas.index') ? 'active' : '' }}"
                                href="{{ route('reservas.index') }}">Reservas
                            </a>
                        </nav>
                    </div>

                        {{-- Otras secciones visibles sin permisos aún --}}
                        <a class="nav-link" href="#">Multas y sanciones</a>
                    </nav>
                </div>






                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseComunicacion" aria-expanded="false"
                    aria-controls="collapseComunicacion">
                    <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                    Comunicación y Atención al Residente
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseComunicacion" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="#">Calificaciones de Servicios</a>
                        <a class="nav-link" href="#">Comunicados y Noticias</a>
                        <a class="nav-link" href="#">Reclamos y Sugerencias</a>
                        <a class="nav-link" href="#">Notificaciones</a>
                    </nav>
                </div>


                {{-- Seguridad y Accesos --}}
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseSeguridad" aria-expanded="false" aria-controls="collapseSeguridad">
                    <div class="sb-nav-link-icon"><i class="fas fa-shield-alt"></i></div>
                    Seguridad y Accesos
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSeguridad" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="#">Control de Acceso</a>
                        <a class="nav-link" href="#">Visitas</a>
                        <a class="nav-link" href="#">Invitación de Visitas</a>
                        <a class="nav-link" href="#">Seguridad y Vigilancia</a>
                    </nav>
                </div>


                {{-- Comunidad y Reportes --}}

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                    data-bs-target="#collapseComunidad" aria-expanded="false" aria-controls="collapseComunidad">
                    <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                    Comunidad y Reportes
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseComunidad" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="#">Agenda de Eventos</a>
                        <a class="nav-link" href="#">Reportes</a>
                        <a class="nav-link" href="#">Documentos del Condominio</a>
                        <a class="nav-link" href="#">Asambleas Vecinales</a>
                        <a class="nav-link" href="#">Foro Vecinal</a>
                    </nav>
                </div>




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
