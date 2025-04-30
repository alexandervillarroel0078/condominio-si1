<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Traits\BitacoraTrait;

class LogoutController extends Controller
{
    use BitacoraTrait;

    public function logout()
    {
        // Registrar acción en la bitácora
        $this->registrarEnBitacora('Usuario cerró sesión', Auth::id());

        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
