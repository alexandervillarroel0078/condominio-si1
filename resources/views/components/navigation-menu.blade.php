<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Inicio</div>
                <a class="nav-link" href="{{ route('panel') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Inicio
                </a>
<!--
                <div class="sb-sidenav-menu-heading">Módulos</div>

                <a class="nav-link" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Usuarios
                </a>

                <a class="nav-link" href="{{ route('roles.index') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-person-circle-plus"></i></div>
                    Roles
                </a>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEmpleados" aria-expanded="false" aria-controls="collapseEmpleados">
                    <div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
                    Empleados
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseEmpleados" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('empleados.index') }}">Lista de Empleados</a>
                        <a class="nav-link" href="{{ route('cargos.index') }}">Cargos de Empleados</a>
                    </nav>
                </div>


                <a class="nav-link" href="{{ route('residentes.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Residentes
                </a>

                <a class="nav-link" href="{{ route('bitacora.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                    Bitácora
                </a>

                <a class="nav-link" href="{{ route('logout') }}">
                    <div class="sb-nav-link-icon"><i class="fa fa-sign-out" aria-hidden="true"></i></div>
                    Salir
                </a>
   -->
                <div class="sb-sidenav-menu-heading">Módulos2</div>


                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false" aria-controls="collapseUsuarios">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Usuarios
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUsuarios" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                        <a class="nav-link" href="{{ route('roles.index') }}">Roles y Permisos</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdministracion" aria-expanded="false" aria-controls="collapseAdministracion">
                    <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                    Administración Interna
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAdministracion" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">

                        {{-- Empleados con submenú --}}
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEmpleados" aria-expanded="false" aria-controls="collapseEmpleados">
                            Empleados
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseEmpleados">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('empleados.index') }}">Lista de Empleados</a>
                                <a class="nav-link" href="{{ route('cargos.index') }}">Cargos de Empleados</a>
                            </nav>
                        </div>

                        {{-- Otras secciones --}}
                        <a class="nav-link" href="{{ route('residentes.index') }}">Residentes</a>
                        <a class="nav-link" href="#">Unidades</a>
                        <a class="nav-link" href="#">Empresas Externas</a>
                        <a class="nav-link" href="#">Mantenimiento</a>
                    </nav>
                </div>


                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseFinanzas" aria-expanded="false" aria-controls="collapseFinanzas">
                    <div class="sb-nav-link-icon"><i class="fas fa-hand-holding-usd"></i></div>
                    Finanzas y Áreas Comunes
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseFinanzas" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="#">Cuotas y Pagos</a>
                        <a class="nav-link" href="#">Gastos del Condominio</a>
                        <a class="nav-link" href="#">Reservas de Áreas Comunes</a>
                        <a class="nav-link" href="#">Multas y Sanciones</a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseComunicacion" aria-expanded="false" aria-controls="collapseComunicacion">
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
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSeguridad" aria-expanded="false" aria-controls="collapseSeguridad">
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
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseComunidad" aria-expanded="false" aria-controls="collapseComunidad">
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
<a class="nav-link" href="{{ route('bitacora.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                    Bitácora
                </a>

                <a class="nav-link" href="{{ route('logout') }}">
                    <div class="sb-nav-link-icon"><i class="fa fa-sign-out" aria-hidden="true"></i></div>
                    Salir
                </a>

            </div>
        </div>
    </nav>
</div>