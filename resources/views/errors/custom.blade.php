<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error en la aplicación</title>
    <link rel="stylesheet" href="{{ asset('css/colors.css') }}">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--color-terciario, #fff); }
        .error-container { background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); padding: 2rem 2.5rem; text-align: center; max-width: 400px; }
        .error-title { font-size: 2rem; color: var(--color-primario); margin-bottom: 1rem; }
        .error-message { color: var(--color-secundario); margin-bottom: 1.5rem; }
        .btn-volver { background: var(--color-primario); color: #fff; border: none; border-radius: 6px; padding: 0.7rem 2rem; font-size: 1.1rem; cursor: pointer; transition: background 0.2s; }
        .btn-volver:hover { background: var(--color-secundario); }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-title">¡Ha ocurrido un error!</div>
        <div class="error-message">
            @if(isset($exception))
                {{ $exception->getMessage() ?: 'Algo salió mal en la aplicación.' }}
            @elseif(isset($message))
                {{ $message }}
            @else
                Algo salió mal en la aplicación.
            @endif
        </div>
        <button class="btn-volver" onclick="window.location.href='{{ url('/') }}'">Regresar a la aplicación</button>
    </div>
</body>
</html> 