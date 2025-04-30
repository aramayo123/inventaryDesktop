<div class="container d-flex justify-content-center align-items-center">
    <div class="text-center w-100" style="max-width: 400px;">
      <div class="card-body">
        <h4 class="card-title fw-bold">{{ Auth::user()->nombre }}</h4>
        <p class="card-subtitle text-muted mb-3">{{ Auth::user()->email }}</p>
        @php
            use Carbon\Carbon;

            $fechaVencimiento = Carbon::parse(Auth::user()->licencia_expires_at);
            $diasRestantes = now()->diffInDays($fechaVencimiento, false);
            $diasRestantes = (int) $diasRestantes;
            $dia = $diasRestantes > 1 ? 's' : '';
            $mensaje = "Licencia válida por $diasRestantes día$dia";
            if($diasRestantes <= 0) {
              $mensaje = "Su licencia vence hoy a las 00 hrs";
            }
        @endphp
        <div class="border rounded p-3 mb-3">
          <p class="mb-1 fw-semibold">{{ $mensaje }}</p>
          <small class="text-muted">Vencimiento el {{ $fechaVencimiento->format('d/m/Y') }}</small>
        </div>
        
        <p class="text-muted small mb-0">
          © 2025 {{ Auth::user()->nombre }}. Contacto: <a href="mailto:aramayo420@email.com">aramayo420@email.com</a><br>
          para renovación de licencia o nuevas compras.
        </p>
      </div>
    </div>
  </div>