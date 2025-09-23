@extends('layouts.app')
<!-- AGREGAR VERIFICADOR DE ACTUALIZACION ACA TAMBIEN !-->
@php
    use App\Services\UpdateChecker;
    $updateChecker = new UpdateChecker();
    $updateInfo = $updateChecker->isUpdateAvailable();
@endphp
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Iniciar sesion</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Correo electrónico</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Contraseña</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                      Recordar sesion
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Iniciar sesion
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    @if($updateInfo['available'])
                        <div id="actualizador" class="card mx-auto my-4 p-3" style="max-width:500px;">
                            <h4 class="text-center mb-3">¡Nueva actualización disponible!</h4>
                            <button id="btn-actualizar" class="btn btn-primary w-100">Actualizar</button>

                            <div id="progreso" style="display:none;" class="mt-3">
                                <div class="progress mb-2">
                                    <div id="barra-progreso" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%">0%</div>
                                </div>
                                <div id="mensaje-progreso" class="text-center"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
let pollingInterval = null;

async function actualizarBarraProgreso() {
  try {
    const response = await fetch('/update-progress'); // el JSON debe estar accesible
    const data = await response.json();

    const barra = document.getElementById('barra-progreso');
    const mensaje = document.getElementById('mensaje-progreso');

    barra.style.width = (data.percent || 0) + '%';
    barra.innerText = (data.percent || 0) + '%';
    mensaje.innerText = data.msg;

    // El último paso es 8 según tu UpdateChecker
    if (data.step === 8 || data.step === -1) {
      clearInterval(pollingInterval);
      pollingInterval = null;
      document.getElementById('btn-actualizar').disabled = false;
      if(data.step === 8) {
        mensaje.innerText = data.msg;
      }
    }
  } catch (err) {
    console.error("Error leyendo JSON:", err);
  }
}
var btn_actualizar = document.getElementById('btn-actualizar');
if(btn_actualizar){
    document.getElementById('btn-actualizar').onclick = function() {
        this.disabled = true;
        document.getElementById('progreso').style.display = '';

        // Disparar el job
        fetch('/check-updates').then(r => r.json()).then(console.log);

        // Empezar a actualizar la barra
        pollingInterval = setInterval(actualizarBarraProgreso, 1000);
    };
}

</script>
@endsection

