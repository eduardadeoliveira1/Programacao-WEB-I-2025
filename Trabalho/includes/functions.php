<?php
/**
 * Funções Gerais do Sistema
 * Sistema de Avaliação de Qualidade
 */

/**
 * Formata data/hora para exibição
 * @param string $datetime Data/hora no formato do banco
 * @param string $format Formato desejado
 * @return string Data formatada
 */
function formatarDataHora($datetime, $format = 'd/m/Y H:i') {
    if (empty($datetime)) {
        return '-';
    }
    $date = new DateTime($datetime);
    return $date->format($format);
}

/**
 * Formata data para exibição
 * @param string $date Data no formato do banco
 * @return string Data formatada
 */
function formatarData($date) {
    return formatarDataHora($date, 'd/m/Y');
}

/**
 * Calcula diferença de tempo legível
 * @param string $datetime Data/hora passada
 * @return string Tempo decorrido (ex: "2 horas atrás")
 */
function tempoDecorrido($datetime) {
    if (empty($datetime)) {
        return '-';
    }
    
    $now = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->diff($past);
    
    if ($diff->y > 0) {
        return $diff->y . ($diff->y == 1 ? ' ano atrás' : ' anos atrás');
    }
    if ($diff->m > 0) {
        return $diff->m . ($diff->m == 1 ? ' mês atrás' : ' meses atrás');
    }
    if ($diff->d > 0) {
        return $diff->d . ($diff->d == 1 ? ' dia atrás' : ' dias atrás');
    }
    if ($diff->h > 0) {
        return $diff->h . ($diff->h == 1 ? ' hora atrás' : ' horas atrás');
    }
    if ($diff->i > 0) {
        return $diff->i . ($diff->i == 1 ? ' minuto atrás' : ' minutos atrás');
    }
    return 'agora mesmo';
}

/**
 * Gera hash único
 * @return string Hash gerado
 */
function gerarHash() {
    return hash('sha256', uniqid(mt_rand(), true));
}

/**
 * Gera ID único
 * @param string $prefix Prefixo opcional
 * @return string ID gerado
 */
function gerarId($prefix = '') {
    return $prefix . uniqid() . bin2hex(random_bytes(4));
}

/**
 * Formata número com separadores
 * @param mixed $number Número a formatar
 * @param int $decimals Casas decimais
 * @return string Número formatado
 */
function formatarNumero($number, $decimals = 0) {
    return number_format($number, $decimals, ',', '.');
}

/**
 * Calcula porcentagem
 * @param float $parte Parte
 * @param float $total Total
 * @param int $decimals Casas decimais
 * @return float Porcentagem
 */
function calcularPorcentagem($parte, $total, $decimals = 2) {
    if ($total == 0) {
        return 0;
    }
    return round(($parte / $total) * 100, $decimals);
}

/**
 * Trunca texto mantendo palavras completas
 * @param string $text Texto
 * @param int $length Tamanho máximo
 * @param string $suffix Sufixo
 * @return string Texto truncado
 */
function truncarTexto($text, $length = 100, $suffix = '...') {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    $text = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($text, ' ');
    
    if ($lastSpace !== false) {
        $text = mb_substr($text, 0, $lastSpace);
    }
    
    return $text . $suffix;
}

/**
 * Converte array para JSON seguro
 * @param array $data Dados
 * @return string JSON
 */
function toJson($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Decodifica JSON para array
 * @param string $json JSON string
 * @return array|null Array ou null em erro
 */
function fromJson($json) {
    return json_decode($json, true);
}

/**
 * Verifica se é requisição AJAX
 * @return bool True se AJAX
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * Redireciona para URL
 * @param string $url URL de destino
 * @param int $statusCode Código HTTP
 */
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Retorna resposta JSON
 * @param array $data Dados
 * @param int $statusCode Código HTTP
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo toJson($data);
    exit();
}

/**
 * Retorna resposta de erro JSON
 * @param string $message Mensagem de erro
 * @param int $statusCode Código HTTP
 */
function jsonError($message, $statusCode = 400) {
    jsonResponse([
        'success' => false,
        'error' => $message
    ], $statusCode);
}

/**
 * Retorna resposta de sucesso JSON
 * @param mixed $data Dados
 * @param string $message Mensagem
 */
function jsonSuccess($data = null, $message = 'Sucesso') {
    $response = [
        'success' => true,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    jsonResponse($response);
}

/**
 * Obtém IP do usuário
 * @return string IP
 */
function getUserIP() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Verificar se está atrás de proxy
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    
    return $ip;
}

/**
 * Obtém user agent do navegador
 * @return string User agent
 */
function getUserAgent() {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
}

/**
 * Log de mensagem no arquivo
 * @param string $message Mensagem
 * @param string $level Nível (info, warning, error)
 * @param string $file Arquivo de log
 */
function logMessage($message, $level = 'info', $file = 'system.log') {
    $logDir = __DIR__ . '/../logs';
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/' . $file;
    $timestamp = date('Y-m-d H:i:s');
    $ip = getUserIP();
    
    $logEntry = "[$timestamp] [$level] [IP: $ip] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

/**
 * Debug - imprime variável formatada
 * @param mixed $var Variável
 * @param bool $die Se deve parar execução
 */
function debug($var, $die = false) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * Verifica se string está vazia (null, '', '   ')
 * @param mixed $value Valor
 * @return bool True se vazio
 */
function isEmpty($value) {
    if ($value === null || $value === '') {
        return true;
    }
    
    if (is_string($value) && trim($value) === '') {
        return true;
    }
    
    return false;
}

/**
 * Obtém valor de array com fallback
 * @param array $array Array
 * @param string $key Chave
 * @param mixed $default Valor padrão
 * @return mixed Valor
 */
function arrayGet($array, $key, $default = null) {
    return $array[$key] ?? $default;
}

/**
 * Cria slug a partir de string
 * @param string $text Texto
 * @return string Slug
 */
function createSlug($text) {
    $text = mb_strtolower($text, 'UTF-8');
    
    // Substituir acentos
    $from = 'àáâãäåçèéêëìíîïñòóôõöùúûüýÿ';
    $to   = 'aaaaaaceeeeiiiinooooouuuuyy';
    $text = strtr($text, $from, $to);
    
    // Remover caracteres especiais
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    
    // Substituir espaços e múltiplos hífens
    $text = preg_replace('/[\s-]+/', '-', $text);
    
    return trim($text, '-');
}

/**
 * Verifica se é data válida
 * @param string $date Data
 * @param string $format Formato
 * @return bool True se válida
 */
function isValidDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Obtém primeiro e último dia do mês
 * @param int $month Mês (opcional)
 * @param int $year Ano (opcional)
 * @return array [primeiro_dia, ultimo_dia]
 */
function getMonthRange($month = null, $year = null) {
    $month = $month ?? date('n');
    $year = $year ?? date('Y');
    
    $firstDay = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
    $lastDay = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));
    
    return [$firstDay, $lastDay];
}

/**
 * Converte bytes para formato legível
 * @param int $bytes Bytes
 * @param int $precision Precisão
 * @return string Tamanho formatado
 */
function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * Gera cor aleatória hexadecimal
 * @return string Cor hex
 */
function randomColor() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

/**
 * Pluraliza palavra baseado em quantidade
 * @param int $count Quantidade
 * @param string $singular Singular
 * @param string $plural Plural (opcional)
 * @return string Palavra pluralizada
 */
function pluralize($count, $singular, $plural = null) {
    if ($count == 1) {
        return $singular;
    }
    
    return $plural ?? $singular . 's';
}
?>