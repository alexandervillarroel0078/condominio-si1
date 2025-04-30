<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Traits\BitacoraTrait;

class LoginController extends Controller
{
    use BitacoraTrait;

    public function index(){
        // si hay un usuario autenticado
        if(Auth::check()){
            return redirect()->route('panel');
        }
        // si no hay un usuario autenticado
        return view('auth.login');
    }

    public function login(LoginRequest $request){
        if(!Auth::validate($request->only('email','password'))){
            return redirect()->to('login')->withErrors('Credenciales incorrectas');
        }
    
        $user = Auth::getProvider()->retrieveByCredentials($request->only('email','password'));
        Auth::login($user);
    
        // Aquí se registra automáticamente la IP desde el Trait
        $this->registrarEnBitacora('Usuario inició sesión', $user->id);
    
        return redirect()->route('panel');
    }
    
}
