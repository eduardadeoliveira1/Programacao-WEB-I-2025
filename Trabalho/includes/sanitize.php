<?php
/**
 * Funções de Sanitização e Validação
 * Sistema de Avaliação de Qualidade
 */

/**
 * Remove tags HTML e espaços extras de uma string
 * @param string|null $str
 * @return string
 */
function sanitizeString($str) {
    if ($str === null) return '';
    return trim(strip_tags($str));
}

/**
 * Sanitiza um número inteiro
 * @param mixed $value
 * @return int|null
 */
function sanitizeInt($value) {
    if ($value === null || $value === '') return null;
    return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Sanitiza texto longo (comentários, descrições, etc.)
 * Mantém acentuação e pontuação básica
 * @param string|null $text
 * @return string
 */
function sanitizeText($text) {
    if ($text === null) return '';
    $text = strip_tags($text, '<b><i><u><strong><em><br><p>');
    return trim($text);
}

/**
 * Sanitiza endereço de e-mail
 * @param string|null $email
 * @return string|null
 */
function sanitizeEmail($email) {
    if (empty($email)) return null;
    $clean = filter_var($email, FILTER_SANITIZE_EMAIL);
    return filter_var($clean, FILTER_VALIDATE_EMAIL) ? $clean : null;
}

/**
 * Sanitiza valores booleanos (t/f, true/false, 1/0)
 * @param mixed $value
 * @return bool
 */
function sanitizeBool($value) {
    return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
}

/**
 * Escapa string para uso seguro em HTML
 * @param string|null $str
 * @return string
 */
function escapeHTML($str) {
    if ($str === null) return '';
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanitiza array recursivamente
 * @param array $data
 * @return array
 */
function sanitizeArray(array $data) {
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $data[$key] = sanitizeArray($value);
        } elseif (is_string($value)) {
            $data[$key] = sanitizeString($value);
        }
    }
    return $data;
}
?>
