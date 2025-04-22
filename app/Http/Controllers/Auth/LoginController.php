<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Carbon;       
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function guard()
    {
        return Auth::guard('client');
    }
    public function login(Request $request)
    {
        $email = strtolower($request->input('email'));
        $password = $request->input('password');
        // Verificar conexión a internet antes de hacer requests HTTP
        try {
            Http::timeout(3)->get('https://www.google.com.ar');
        } catch (\Exception $e) {
            if(!$email){
                return back()->withErrors(['email' => 'El correo electronico es requerido'])->withInput();
            }
            if(!$password){
                return back()->withErrors(['password' => 'La contraseña es requerida'])->withInput();
            }
            // si no hay conexion buscar cliente en la base de datos local
            $client = Client::where('email', $email)->first();
            if ($client) {
                
                if(!Hash::check($password, $client->password)){
                    return back()->withErrors(['password' => 'Contraseña incorrecta'])->withInput();
                }

                // 🔐 Verificar firma
                $firmaLocal = hash('sha256', $client->nombre . '|' .
                    $client->email . '|' .
                    $client->licencia_expires_at . '|' .
                    $client->secret_hash);

                if ($client->firma !== $firmaLocal) {
                    return back()->withErrors(['email' => 'Firma inválida, posible manipulación'])->withInput();
                }
    
                // 📅 Verificar expiración
                if (Carbon::now()->greaterThan($client->licencia_expires_at)) {
                    return back()->withErrors(['email' => 'Licencia vencida'])->withInput();
                }

                // 🕓 Verificar si la fecha del sistema fue atrasada
                if (Carbon::now()->lessThan($client->last_used_at)) {
                    return back()->withErrors(['email' => 'Fecha del sistema alterada'])->withInput();
                }    

                $client->last_used_at = now();
                $client->update();

                Auth::guard('client')->login($client);
                return redirect()->route('home')->with('success', 'Inicio de sesión local exitoso');
            }
            return back()->withErrors(['email' => 'El cliente no existe'])->withInput();

        }
        // tenemos conexion, vemos si eexiste
        $response = Http::get('http://127.0.0.1:8001/clientes/api/auth-user', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->failed()) { // si fallo la conexion o escribio mal pw o email
            $emailFailed = $response->json('email');
            $passwordFailed = $response->json('password');
            if($emailFailed)
                return back()->withErrors(['email' => $emailFailed])->withInput();

            if($passwordFailed)
                return back()->withErrors(['password' => $passwordFailed])->withInput();
        }
        $data = $response->json(); // obtenemos los datos verificados y correctos

        // Verificamos expiración
        if (Carbon::now()->greaterThan($data['licencia_expires_at'])) {
            return back()->withErrors(['email' => 'Licencia vencida'])->withInput();
        }
        if(!Hash::check($password, $data['password'])){
            return back()->withErrors(['password' => 'Contraseña incorrecta'])->withInput();
        }
        $response = Http::get('http://127.0.0.1:8001/clientes/api/update-last-used', [
            'email' => $email,
        ]);
        if ($response->failed()) {
            $error = $response->json('error');
            return back()->withErrors(['email' => $error])->withInput();
        }
        
        $client = Client::where('email', $email)->first();
        if($client){
            $client->nombre = $data['nombre'];
            $client->email = $data['email'];
            $client->password = $data['password'];
            $client->licencia_expires_at = $data['licencia_expires_at'];
            $client->secret_hash = $data['secret_hash'];
            $client->last_used_at = now();
            $client->firma = hash('sha256', $data['nombre'] . '|' . $data['email'] . '|' . $data['licencia_expires_at'] . '|' . $data['secret_hash']);
            $client->update();
        }else{
            // 💾 Insertar en SQLite
            $client = new Client();
            $client->nombre = $data['nombre'];
            $client->email = $data['email'];
            $client->password = $data['password'];
            $client->licencia_expires_at = $data['licencia_expires_at'];
            $client->secret_hash = $data['secret_hash'];
            $client->last_used_at = now();
            $client->firma = hash('sha256', $data['nombre'] . '|' . $data['email'] . '|' . $data['licencia_expires_at'] . '|' . $data['secret_hash']);
            $client->save();
        }
        Auth::guard('client')->login($client);
        return redirect()->route('home')->with('success', 'Inicio de sesión descargado exitoso');
    }
}
