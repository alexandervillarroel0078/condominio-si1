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

                <div class="sb-sidenav-menu-heading">Módulos</div>


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
                        <a class="nav-link" href="{{ route('users.perfil') }}">Perfil</a>

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
                                @auth
                                @if(auth()->user()->residente)
                                <a class="nav-link {{ request()->routeIs('pagos.mis_cuotas') ? 'active' : '' }}" href="{{ route('pagos.mis_cuotas') }}">
                                    Mis Cuotas
                                </a>
                                @endif
                                @endauth

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
                        <a class="nav-link {{ request()->routeIs('areas-comunes.*') || request()->routeIs('reservas.*') ? '' : 'collapsed' }}"
                            href="#" data-bs-toggle="collapse" data-bs-target="#collapseAreas"
                            aria-expanded="{{ request()->routeIs('areas-comunes.*') || request()->routeIs('reservas.*') ? 'true' : 'false' }}"
                            aria-controls="collapseAreas">
                            <div class="sb-nav-link-icon"><i class="fas fa-swimming-pool"></i></div>
                            Gestión de Áreas Comunes
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ request()->routeIs('areas-comunes.*') || request()->routeIs('reservas.*') ? 'show' : '' }}"
                            id="collapseAreas" data-bs-parent="#collapseFinanzas">
                            <nav class="sb-sidenav-menu-nested nav">
                                {{-- Catálogo de Áreas --}}
                                <a class="nav-link {{ request()->routeIs('areas-comunes.index') ? 'active' : '' }}"
                                    href="{{ route('areas-comunes.index') }}">Catálogo
                                </a>
                                {{-- Reservas --}}
                                <a class="nav-link {{ request()->routeIs('reservas.index') ? 'active' : '' }}"
                                    href="{{ route('reservas.index') }}">Reservas
                                </a>
                            </nav>
                        </div>
                        {{-- Multas --}}
                        <a class="nav-link" href="{{route('multas.index')}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-gavel"></i></div>
                            Panel de Multas
                        </a>

                        <a class="nav-link {{ request()->routeIs('inventario.*') ? 'active' : '' }}" href="{{ route('inventario.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                            Inventario
                        </a>
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
                        <a class="nav-link" href="{{ route('comunicados.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-newspaper"></i></div>
                            Comunicados y Noticias</a>
                        {{-- Reclamos y Sugerencias --}}
                        <a class="nav-link" href="{{route('reclamos.index')}}">
                            <div class="sb-nav-link-icon"><i class="fa fa-paper-plane"></i></div>
                            Reclamos y Sugerencias</a>
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

                        {{-- CONTROL DE ACCESO Y VISITAS --}}
                        <a class="nav-link {{ request()->routeIs('visitas.*') ? '' : 'collapsed' }}"
                        href="#" data-bs-toggle="collapse" data-bs-target="#collapseVisitas"
                        aria-expanded="{{ request()->routeIs('visitas.*') ? 'true' : 'false' }}"
                        aria-controls="collapseVisitas">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Control de Acceso y Visitas
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ request()->routeIs('visitas.*') ? 'show' : '' }}"
                            id="collapseVisitas">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="#">Control de Acceso</a>

                                {{-- Solo para usuarios con permiso 'gestionar visitas' --}}
                                @can('gestionar visitas')
                                <a class="nav-link {{ request()->routeIs('visitas.index') ? 'active' : '' }}"
                                href="{{ route('visitas.index') }}">
                                     Gestionar Visitas
                                </a>
                                @endcan

                                {{-- Solo para usuarios con permiso 'operar porteria' --}}
                                @can('operar porteria')
                                <a class="nav-link {{ request()->routeIs('visitas.panel-guardia') ? 'active' : '' }}"
                                href="{{ route('visitas.panel-guardia') }}">
                                    Panel Guardia
                                </a>
                                @endcan

                                {{-- Solo para usuarios con permiso 'administrar visitas' --}}
                                @can('administrar visitas')
                                <a class="nav-link {{ request()->routeIs('visitas.mostrar-validar-codigo') ? 'active' : '' }}"
                                href="{{ route('visitas.mostrar-validar-codigo') }}">
                                    Validar Código Visita
                                </a>
                                @endcan
                            </nav>
                        </div>

                        {{-- SEGURIDAD Y VIGILANCIA --}}
                        <a class="nav-link {{ request()->routeIs('seguridad.*') ? '' : 'collapsed' }}"
                        href="#" data-bs-toggle="collapse" data-bs-target="#collapseVigilancia"
                        aria-expanded="{{ request()->routeIs('seguridad.*') ? 'true' : 'false' }}"
                        aria-controls="collapseVigilancia">
                            <div class="sb-nav-link-icon"><i class="fas fa-eye"></i></div>
                            Seguridad y Vigilancia
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ request()->routeIs('seguridad.*') ? 'show' : '' }}"
                            id="collapseVigilancia">
                            <nav class="sb-sidenav-menu-nested nav">

                                {{-- ADMINISTRADOR - Ve todo --}}
                                @can('administrar-seguridad')
                                <a class="nav-link {{ request()->routeIs('seguridad.index') && !request('estado') ? 'active' : '' }}"
                                href="{{ route('seguridad.index') }}">
                                    Administrar Seguridad
                                </a>
                                @endcan

                                {{-- DIRECTIVA - Solo lectura --}}
                                @can('ver-registros-seguridad')
                                    @cannot('administrar-seguridad')
                                    <a class="nav-link {{ request()->routeIs('seguridad.index') ? 'active' : '' }}"
                                    href="{{ route('seguridad.index') }}">
                                        Registros de Seguridad
                                    </a>
                                    @endcannot
                                @endcan

                                {{-- PERSONAL DE SEGURIDAD - Panel completo --}}
                                @can('crear-registro-seguridad')
                                    @cannot('administrar-seguridad')
                                    <a class="nav-link {{ request()->routeIs('seguridad.index') && !request('estado') ? 'active' : '' }}"
                                    href="{{ route('seguridad.index') }}">
                                         Panel de Seguridad
                                    </a>
                                    @endcannot

                                    <a class="nav-link {{ request()->routeIs('seguridad.create') ? 'active' : '' }}"
                                    href="{{ route('seguridad.create') }}">
                                         Nuevo Registro
                                    </a>

                                    <a class="nav-link {{ request()->routeIs('seguridad.index') && request('estado') == 'pendiente' ? 'active' : '' }}"
                                    href="{{ route('seguridad.index', ['estado' => 'pendiente']) }}">
                                        Incidentes Pendientes
                                    </a>
                                @endcan

                                {{-- RESIDENTE - Reportes personales --}}
                                @can('reportar-incidentes')
                                    {{-- Solo mostrar si NO es personal de seguridad --}}
                                    @cannot('crear-registro-seguridad')
                                    <a class="nav-link {{ request()->routeIs('seguridad.reportar-incidente') ? 'active' : '' }}"
                                    href="{{ route('seguridad.reportar-incidente') }}">
                                         Reportar Incidente
                                    </a>

                                    <a class="nav-link {{ request()->routeIs('seguridad.index') ? 'active' : '' }}"
                                    href="{{ route('seguridad.index') }}">
                                        Mis Reportes
                                    </a>
                                    @endcannot
                                @endcan
                            </nav>
                        </div>

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
