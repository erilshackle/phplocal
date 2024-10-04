<?php

/**
 * Sanitiza uma variável com um filtro específico.
 *
 * @param mixed $value
 * @param int $filter
 * @return string
 */
function var_clean($value, int $filter = FILTER_SANITIZE_STRING): string
{
    return filter_var($value, $filter);
}

/**
 * Valida um email.
 *
 * @param string $email
 * @return bool
 */
function var_is_valid_email($email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida uma URL.
 *
 * @param string $url
 * @return bool
 */
function var_is_valid_url($url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Formata uma string para título (capitaliza a primeira letra de cada palavra).
 *
 * @param string $string
 * @return string
 */
function var_title_case($string): string
{
    return ucwords(strtolower($string));
}

/**
 * Verifica se uma variável está definida e não é vazia.
 *
 * @param mixed $var
 * @return bool
 */
function var_exists($var): bool
{
    return isset($var) && !empty($var);
}

/**
 * Recupera um parâmetro GET sanitizado ou um valor padrão.
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function var_get_param(string $key, $default = null)
{
    return var_exists($_GET[$key]) ? var_clean($_GET[$key]) : $default;
}

/**
 * Recupera um parâmetro POST sanitizado ou um valor padrão.
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function var_post_param(string $key, $default = null)
{
    return var_exists($_POST[$key]) ? var_clean($_POST[$key]) : $default;
}

/**
 * Escapa HTML para evitar XSS.
 *
 * @param string $value
 * @return string
 */
function var_escape($value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Retorna valor padrão se a variável não for definida ou vazia.
 *
 * @param mixed $var
 * @param mixed $default
 * @return mixed
 */
function var_default_if_empty($var, $default)
{
    return var_exists($var) ? $var : $default;
}

/**
 * Cria um array associativo a partir das variáveis passadas.
 *
 * @param mixed ...$vars
 * @return array
 */
function var_array(...$vars): array
{
    $result = [];
    foreach ($vars as $var) {
        $result[$var] = $var;
    }
    return $result;
}
