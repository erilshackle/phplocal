<?php

/**
 * Inicia a sessão se ainda não estiver iniciada.
 */
function session_start_if_not_started()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Define ou obtém uma mensagem flash da sessão.
 *
 * @param string|null $message
 * @return string|null
 */
function flash_message($message = null)
{
    session_start_if_not_started();
    
    if ($message !== null) {
        // Define a mensagem flash
        $_SESSION['flash_message'] = $message;
    } else {
        // Obtém e limpa a mensagem flash
        if (isset($_SESSION['flash_message'])) {
            $msg = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $msg;
        }
        return null;
    }
}

/**
 * Define um usuário autenticado na sessão.
 *
 * @param mixed $user
 */
function set_authenticated_user($user)
{
    session_start_if_not_started();
    $_SESSION[CONFIG_SESSION['user_auth']] = $user;
}

/**
 * Obtém o usuário autenticado da sessão.
 *
 * @return mixed|null
 */
function get_authenticated_user()
{
    session_start_if_not_started();
    return isset($_SESSION[CONFIG_SESSION['user_auth']]) ? $_SESSION[CONFIG_SESSION['user_auth']] : null;
}

/**
 * Verifica se um usuário está autenticado.
 *
 * @return bool
 */
function is_user_authenticated(): bool
{
    return get_authenticated_user() !== null;
}

/**
 * Destrói a sessão e remove o usuário autenticado.
 */
function logout()
{
    session_start_if_not_started();
    unset($_SESSION[CONFIG_SESSION['user_auth']]);
    session_destroy();
}

/**
 * Define um valor padrão para a variável de sessão, se não existir.
 *
 * @param string $key
 * @param mixed $default
 */
function session_set_default($key, $default)
{
    session_start_if_not_started();
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = $default;
    }
}
