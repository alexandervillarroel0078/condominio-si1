<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Gestor de Ventas y Control de Planilla de sueldos" />
    <meta name="author" content="Grupo 3" />
    <title>Login - Gestor de Ventas</title>
    <link href="{{ asset('css/plantilla.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="layoutAuthentication" class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div id="layoutAuthentication_content" class="w-100">
            <main style="height: 100vh;" class="d-flex align-items-center justify-content-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card text-white">
                                <div class="card-header text-center">
                                    <h3 class="my-4">Inicio de sesión</h3>
                                </div>
                                <div class="card-body">
                                    @if ($errors->any())
                                    @foreach ($errors->all() as $item)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ $item }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endforeach
                                    @endif
                                    <form action="/login" method="POST">
                                        @csrf

                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-white"><i class="fas fa-envelope"></i></span>
                                            <input type="email" name="email" class="form-control custom-input" placeholder="Correo electrónico" value="{{ old('email') }}">
                                        </div>

                                        <div class="input-group mb-4">
                                            <span class="input-group-text bg-white"><i class="fas fa-lock"></i></span>
                                            <input type="password" name="password" class="form-control custom-input" placeholder="Contraseña">
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-login">Iniciar sesión</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>