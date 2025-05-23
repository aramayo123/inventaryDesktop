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
    protected $redirectTo = '/';

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
        $urlGoogle = "https://www.google.com.ar";
        // Verificar conexión a internet antes de hacer requests HTTP

        if(!$email)
            return back()->withErrors(['email' => 'El correo electronico es requerido'])->withInput();
        
        if(!$password)
            return back()->withErrors(['password' => 'La contraseña es requerida'])->withInput();

        try {
            Http::timeout(1)->get($urlGoogle);
        } catch (\Exception $e) {
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
        $urlAuth = env('URL_AUTH_CLIENT', 'http://127.0.0.1:8001/clientes/api/v1/auth-user');
        $urlUpdateLast = env('URL_UPDATE_CLIENT', 'http://127.0.0.1:8001/clientes/api/v1/update-last-used');

        if (!$urlAuth || !$urlUpdateLast) {
            return back()->withErrors(['email' => 'Configuración del servidor incompleta. Faltan URLs de conexión.'])->withInput();
        }

        // tenemos conexion, vemos si existe
        try{
            $response = Http::get($urlAuth, ['email' => $email, 'password' => $password]);
        } catch (\Exception $e) {
            $previous = $e->getPrevious();
            $host = $previous->getRequest()->getUri()->getHost();

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
            return back()->withErrors(['email' => 'No se ha podido establecer una conexion con '.$host. ' contacte al desarrollador aramayo420@gmail.com'])->withInput();
        }
    
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
        
        try{
            $response = Http::get($urlUpdateLast, ['email' => $email]);
        } catch (\Exception $e) {
            $previous = $e->getPrevious();
            $host = $previous->getRequest()->getUri()->getHost();

            return back()->withErrors(['email' => 'No se ha podido establecer una conexion con '.$host. ' contacte al desarrollador aramayo420@gmail.com'])->withInput();
        }
    
        if($response->serverError()){
            return back()->withErrors(['email' => "No se ha podido establecer conexion con la API"])->withInput();
        }

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
