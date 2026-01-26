<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AppApiController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) throw ValidationException::withMessages(['email' => ['Las credenciales proporcionadas son incorrectas.'],]);
            Auth::login($user, $request->remember ?? false);
            $respuesta['mensaje'] = __('Usuario inició sesión con éxito.');
            $respuesta['error'] = false;
        } catch (\Throwable $e) {
            $respuesta['mensaje'] = $e->getMessage();
            $respuesta['error'] = true;
        }
        echo json_encode($respuesta);
        exit;
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed'
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'admin' => (!empty($request->master_key) && !empty(env('MASTER_KEY'))) && ($request->master_key == env('MASTER_KEY'))
            ]);
            Auth::login($user, $request->remember ?? false);
            $respuesta['mensaje'] = __('Usuario registrado con éxito.');
            $respuesta['error'] = false;
        } catch (\Throwable $e) {
            $respuesta['mensaje'] = $e->getMessage();
            $respuesta['error'] = true;
        }
        echo json_encode($respuesta);
        exit;
    }

    public function logout()
    {
        try {
            Auth::logout();
            $respuesta['mensaje'] = __('Usuario cerró sesión con éxito.');
            $respuesta['error'] = false;
        } catch (\Throwable $e) {
            $respuesta['mensaje'] = $e->getMessage();
            $respuesta['error'] = true;
        }
        echo json_encode($respuesta);
        exit;
    }

    public function logoutAll(Request $request)
    {
        try {
            Auth::logoutOtherDevices($request->user()->password);
            $respuesta['mensaje'] = __('Usuario cerró sesiones activas con éxito.');
            $respuesta['error'] = false;
        } catch (\Throwable $e) {
            $respuesta['mensaje'] = $e->getMessage();
            $respuesta['error'] = true;
        }
        echo json_encode($respuesta);
        exit;
    }

    public function user(Request $request)
    {
        try {
            $respuesta['user'] = $request->user();
            $respuesta['mensaje'] = __('Usuario obtenido con éxito.');
            $respuesta['error'] = false;
        } catch (\Throwable $e) {
            $respuesta['mensaje'] = $e->getMessage();
            $respuesta['error'] = true;
        }
        echo json_encode($respuesta);
        exit;
    }
}
