@extends('plantilla')

@section('title', 'Inicio')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sistema de Gestión de Condominios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Panel principal</li>
    </ol>

    <div class="row">
        <!---->

        <div class="row">
            {{-- Paquete: Usuarios --}}
            @canany(['ver usuarios', 'ver roles'])
            <div class="col-xl-4 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i><br>
                        Usuarios
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#usuariosModal">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            @endcanany

            {{-- Paquete: Administración Interna --}}
            @canany(['ver empleados', 'ver cargos', 'ver residentes', 'ver unidades', 'ver empresas'])
            <div class="col-xl-4 col-md-6">
                <div class="card bg-secondary text-white mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-cogs fa-2x mb-2"></i><br>
                        <strong>Administración Interna</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#adminInternaModal">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            @endcanany

            {{-- Paquete: Finanzas y Áreas Comunes --}}
            @canany(['ver cuotas', 'ver tipos de cuotas', 'ver pagos', 'ver gastos', 'ver tipos de gastos'])
            <div class="col-xl-4 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-hand-holding-usd fa-2x mb-2"></i><br>
                        Finanzas y Áreas Comunes
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#finanzasModal">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            @endcanany

            {{-- Paquete: Comunicación y Atención al Residente --}}
            @canany(['ver comunicados', 'ver notificaciones', 'ver reclamos', 'ver calificaciones'])
            <div class="col-xl-4 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-comments fa-2x mb-2"></i><br>
                        <strong>Comunicación y Atención al Residente</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#comunicacionModal">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            @endcanany

            {{-- Paquete: Seguridad y Accesos --}}
            @canany(['ver control de acceso', 'ver visitas', 'ver invitaciones', 'ver vigilancia'])
            <div class="col-xl-4 col-md-6">
                <div class="card bg-warning text-dark mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt fa-2x mb-2"></i><br>
                        <strong>Seguridad y Accesos</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-dark stretched-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#seguridadModal">Ver detalles</a>
                        <div class="small text-dark"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            @endcanany

            {{-- Paquete: Comunidad y Reportes --}}
            @canany(['ver agenda', 'ver reportes', 'ver documentos', 'ver asambleas', 'ver foro'])
            <div class="col-xl-4 col-md-6">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-users-cog fa-2x mb-2"></i><br>
                        <strong>Comunidad y Reportes</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#comunidadModal">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            @endcanany

            {{-- Paquete: Bitácora --}}
            @can('ver bitacora')
            <div class="col-xl-4 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body text-center">
                        <i class="fas fa-book fa-2x mb-2"></i><br>
                        <strong>Bitácora</strong>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#" data-bs-toggle="modal"
                            data-bs-target="#bitacoraModal">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            @endcan
        </div>


        <!-- Modal para Usuarios -->
        <div class="modal fade" id="usuariosModal" tabindex="-1" aria-labelledby="usuariosModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="usuariosModalLabel">Opciones del módulo Usuarios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <a href="{{ route('users.index') }}"
                                class="list-group-item list-group-item-action">Usuarios</a>
                            <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action">Roles
                                y Permisos</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal: Administración Interna -->
        <div class="modal fade" id="adminInternaModal" tabindex="-1" aria-labelledby="adminInternaModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                        <h5 class="modal-title" id="adminInternaModalLabel">Opciones de Administración Interna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <a href="{{ route('empleados.index') }}"
                                class="list-group-item list-group-item-action">Lista de Empleados</a>
                            <a href="{{ route('cargos.index') }}"
                                class="list-group-item list-group-item-action">Cargos de Empleados</a>
                            <a href="{{ route('residentes.index') }}"
                                class="list-group-item list-group-item-action">Residentes</a>
                            <a href="{{ route('unidades.index') }}" class="list-group-item list-group-item-action">Unidades</a>
                            <a href="#" class="list-group-item list-group-item-action">Empresas Externas</a>
                            <a href="{{ route('mantenimientos.index') }}"
                                class="list-group-item list-group-item-action">Mantenimiento</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal: Finanzas y Áreas Comunes -->
        <div class="modal fade" id="finanzasModal" tabindex="-1" aria-labelledby="finanzasModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="finanzasModalLabel">Opciones de Finanzas y Áreas Comunes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">Cuotas y Pagos</a>
                            <a class="list-group-item list-group-item-action"
                                href="{{ route('tipo-gastos.index') }}">Tipos Gastos del Condominio</a>
                            <a href="{{ route('gastos.index') }}"
                                class="list-group-item list-group-item-action">Gastos del Condominio</a>
                            <a href="{{ route('areas-comunes.index') }}"
                                class="list-group-item list-group-item-action">Catalogo de Areas Comunes</a>
                            <a href="{{ route('reservas.index') }}"
                                class="list-group-item list-group-item-action">Reservas de Áreas Comunes</a>
                            <a href="{{ route('multas.index') }}"
                                class="list-group-item list-group-item-action">Panel de Multas</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal: Comunicación y Atención al Residente -->
        <div class="modal fade" id="comunicacionModal" tabindex="-1" aria-labelledby="comunicacionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="comunicacionModalLabel">Opciones de Comunicación y Atención</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">Calificaciones de
                                Servicios</a>
                            <a href="#" class="list-group-item list-group-item-action">Comunicados y
                                Noticias</a>
                            <a href="#" class="list-group-item list-group-item-action">Reclamos y
                                Sugerencias</a>
                            <a href="{{ route('notificaciones.index') }}" 
                                class="list-group-item list-group-item-action">Notificaciones</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal: Seguridad y Accesos -->
        <div class="modal fade" id="seguridadModal" tabindex="-1" aria-labelledby="seguridadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="seguridadModalLabel">Opciones de Seguridad y Accesos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">Control de Acceso</a>
                            <a href="#" class="list-group-item list-group-item-action">Visitas</a>
                            <a href="#" class="list-group-item list-group-item-action">Invitación de Visitas</a>
                            <a href="#" class="list-group-item list-group-item-action">Seguridad y
                                Vigilancia</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal: Comunidad y Reportes -->
        <div class="modal fade" id="comunidadModal" tabindex="-1" aria-labelledby="comunidadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="comunidadModalLabel">Opciones de Comunidad y Reportes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">Agenda de Eventos</a>
                            <a href="#" class="list-group-item list-group-item-action">Reportes</a>
                            <a href="#" class="list-group-item list-group-item-action">Documentos del
                                Condominio</a>
                            <a href="#" class="list-group-item list-group-item-action">Asambleas Vecinales</a>
                            <a href="#" class="list-group-item list-group-item-action">Foro Vecinal</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal: Bitácora -->
        <div class="modal fade" id="bitacoraModal" tabindex="-1" aria-labelledby="bitacoraModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="bitacoraModalLabel">Bitácora del Sistema</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <a href="{{ route('bitacora.index') }}"
                                class="list-group-item list-group-item-action">Ver Bitácora</a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
    crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
