function formatearNumero(valor, conSimbolo = false) {
    // Normalizar la entrada: reemplazar coma por punto si es string
    if (typeof valor === 'string') {
        valor = valor.replace(',', '.');
    }

    // Convertir a número
    let numero = parseFloat(valor);

    // Si no es número válido, devolver '0,00' si no es moneda, o '$ 0,00' si es moneda
    if (isNaN(numero)) {
        return conSimbolo ? '$ 0,00' : '0,00';
    }

    // Configuración de formato
    const opciones = {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    };

    if (conSimbolo) {
        opciones.style = 'currency';
        opciones.currency = 'ARS';
    }

    return new Intl.NumberFormat('es-AR', opciones).format(numero);
}