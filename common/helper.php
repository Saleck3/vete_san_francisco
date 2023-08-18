<?php
/**
 * Funciones a usar cross a toda la aplicacion
 */

use JetBrains\PhpStorm\NoReturn;

/**
 * Redirecciona al modulo default y corta la ejecucion
 *
 * @return void
 */
#[NoReturn] function redireccionarAInicio(): void
{
    header("Location: /");
    die();
}


/**
 * Redirecciona a una pagina puntual corta la ejecucion
 *
 * @param string $pagina Default: inicio
 *
 * @return void
 */
#[NoReturn] function redireccionar(string $pagina = ""): void
{
    header("Location: /" . $pagina);
    die();
}

/**
 * Encola un mensaje para mostrar al usuarios
 *
 * @param string $mensaje
 * @param string $tipomensaje
 * @param string $icono
 *
 * @return void
 */
function mensajeAlUsuario(string $mensaje, string $tipomensaje = "w3-yellow", string $icono = ""): void
{
    if ($tipomensaje == "error") {
        $tipomensaje = "w3-red";
    }

    if ($tipomensaje == "exito" || $tipomensaje == "ok") {
        $tipomensaje = "w3-green";
    }
    if ($tipomensaje == "warning" || $tipomensaje == "advertencia") {
        $tipomensaje = "w3-yellow";
    }
    if (!empty($icono)) {
        $icono = sprintf('<i class="fa %s"></i>', $icono);
    }

    $mensaje_formateado = sprintf('<div class="mensaje-al-usuario %s" onclick="this.remove();">%s%s</div>', $tipomensaje, $icono, $mensaje);
    if (!empty($_SESSION['mensaje'])) $_SESSION['mensaje'] .= $mensaje_formateado;
    else $_SESSION['mensaje'] = $mensaje_formateado;
}

/**
 * Sanitiza una unica variable contra injecciones
 *
 * @param mixed $valor
 *
 * @return void
 */
function sanitizarValor(mixed &$valor): void
{
    if (is_numeric($valor)) return;
    if (is_bool($valor)) return;
    if (is_string($valor))
        $valor = htmlspecialchars(trim($valor));
}

function sanitizarArray(&$array): void
{
    if (is_array($array)) {
        foreach ($array as &$campo) {
            if (is_array($campo)) {
                sanitizarArray($campo);
            } else sanitizarValor($campo);
        }
    } else sanitizarValor($array);
}


/**
 * Valida una exprecion regular
 *
 * @param string $valor
 * @param string $regex
 *
 * @return bool|int
 */
function validarRegex(string $valor, string $regex): bool|int
{
    sanitizarValor($valor);
    return preg_match($regex, $valor);
}

/**
 * Valida si un valor es un mail
 *
 * @param string $valor
 *
 * @return int|bool
 */
function validarMail(string $valor): int|bool
{
    sanitizarValor($valor);
    return filter_var($valor, FILTER_VALIDATE_EMAIL);
}

/**
 * Valida si un valor es un numero
 *
 * @param int $valor
 *
 * @return int|bool
 */
function validarNumero(int $valor): int|bool
{
    sanitizarValor($valor);
    return filter_var($valor, FILTER_VALIDATE_INT);
}

/**
 * Valida si el usuario inicio sesion
 *
 * @return bool
 */
function estaLogueado(): bool
{
    return !empty($_SESSION) && !empty($_SESSION["usuario"]);
}

/**
 * Valida si un usuario es administrador
 *
 * @return bool
 */
function esAdmin(): bool
{
    return !empty($_SESSION) && !empty($_SESSION["usuario"]) && $_SESSION["usuario"]["rol_id"] == $_ENV["ROL_ADMIN"];
}