<?php
/**
 * Funções de Autenticação
 * Sistema de Avaliação de Qualidade
 * Atualizado: senha padrão e troca obrigatória no primeiro login
 */

// ===========================================================
// 🔐 STATUS DE LOGIN
// ===========================================================

/**
 * Verifica se usuário está logado
 * @return bool True se logado
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Verifica se sessão ainda é válida
 * @return bool True se válida
 */
function isSessionValid() {
    if (!isset($_SESSION['last_activity'])) {
        return false;
    }
    
    $elapsed = time() - $_SESSION['last_activity'];
    
    if ($elapsed > SESSION_TIMEOUT) {
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Protege página administrativa (exige login válido)
 */
function protectAdminPage() {
    if (!isLoggedIn() || !isSessionValid()) {
        logout();
        header('Location: login.php?error=session_expired');
        exit();
    }
}

// ===========================================================
// 🔑 LOGIN E AUTENTICAÇÃO
// ===========================================================

/**
 * Realiza login do usuário
 * @param string $login Login do usuário
 * @param string $senha Senha do usuário
 * @return bool True se login bem-sucedido
 */
function login($login, $senha) {
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("
        SELECT id_usuario, login, senha, nome_completo, status, precisa_mudar_senha
        FROM usuarios_admin
        WHERE login = :login AND status = 'ativo'
    ");
    
    $stmt->execute(['login' => sanitizeString($login)]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        sleep(1); // evita brute force
        return false;
    }
    
    // Verifica senha
    if (!password_verify($senha, $usuario['senha'])) {
        sleep(1);
        logLoginAttempt($login, false);
        return false;
    }
    
    // Login bem-sucedido
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $usuario['id_usuario'];
    $_SESSION['admin_login'] = $usuario['login'];
    $_SESSION['admin_nome'] = $usuario['nome_completo'];
    $_SESSION['last_activity'] = time();
    
    // Verifica se precisa trocar senha
    if (!empty($usuario['precisa_mudar_senha']) && $usuario['precisa_mudar_senha'] === true) {
        $_SESSION['precisa_mudar_senha'] = true;
        header('Location: alterar_senha.php');
        exit;
    }
    
    // Regenerar ID da sessão (segurança)
    session_regenerate_id(true);
    
    // Atualiza último acesso
    $stmt = $pdo->prepare("
        UPDATE usuarios_admin 
        SET ultimo_acesso = CURRENT_TIMESTAMP 
        WHERE id_usuario = :id
    ");
    $stmt->execute(['id' => $usuario['id_usuario']]);
    
    logLoginAttempt($login, true);
    return true;
}

/**
 * Realiza logout do usuário
 */
function logout() {
    $_SESSION = [];
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

// ===========================================================
// 🧾 REGISTRO E AUDITORIA
// ===========================================================

/**
 * Registra tentativa de login (para auditoria)
 * @param string $login Login tentado
 * @param bool $sucesso Se foi bem-sucedido
 */
function logLoginAttempt($login, $sucesso) {
    $logFile = __DIR__ . '/../logs/login_attempts.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $timestamp = date('Y-m-d H:i:s');
    $status = $sucesso ? 'SUCCESS' : 'FAILED';
    
    $logEntry = "[$timestamp] $status - Login: $login - IP: $ip" . PHP_EOL;
    
    // Cria diretório se não existir
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// ===========================================================
// 🔒 UTILITÁRIOS DE SENHA E USUÁRIO
// ===========================================================

/**
 * Cria hash de senha seguro
 * @param string $senha Senha em texto plano
 * @return string Hash seguro
 */
function hashPassword($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

/**
 * Retorna dados do usuário logado
 * @return array|false
 */
function getUsuarioLogado() {
    if (!isLoggedIn()) {
        return false;
    }
    
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT id_usuario, login, nome_completo, email
        FROM usuarios_admin
        WHERE id_usuario = :id
    ");
    
    $stmt->execute(['id' => $_SESSION['admin_id']]);
    return $stmt->fetch();
}

/**
 * Verifica número de tentativas de login falhadas
 * Implementa bloqueio simples de IP (rate limiting)
 * @param string $ip Endereço IP
 * @return bool True se bloqueado
 */
function isLoginBlocked($ip) {
    $logFile = __DIR__ . '/../logs/login_attempts.log';
    
    if (!file_exists($logFile)) {
        return false;
    }
    
    $logs = file($logFile, FILE_IGNORE_NEW_LINES);
    $recentAttempts = 0;
    $timeLimit = time() - 900; // últimos 15 minutos
    
    foreach (array_reverse($logs) as $line) {
        if (strpos($line, $ip) !== false && strpos($line, 'FAILED') !== false) {
            preg_match('/\[(.*?)\]/', $line, $matches);
            if (isset($matches[1])) {
                $logTime = strtotime($matches[1]);
                if ($logTime >= $timeLimit) {
                    $recentAttempts++;
                }
            }
        }
    }
    
    return $recentAttempts >= 5; // bloqueia após 5 falhas
}

/**
 * Cria novo usuário administrativo
 * @param array $dados Dados do usuário
 * @return int|false ID do novo usuário ou false
 */
function criarUsuario($dados) {
    $pdo = getDBConnection();
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO usuarios_admin (login, senha, nome_completo, email, status, precisa_mudar_senha)
            VALUES (:login, :senha, :nome, :email, :status, :precisa_mudar_senha)
            RETURNING id_usuario
        ");
        
        $stmt->execute([
            'login' => sanitizeString($dados['login']),
            'senha' => hashPassword($dados['senha']),
            'nome'  => sanitizeString($dados['nome_completo']),
            'email' => sanitizeEmail($dados['email']),
            'status' => 'ativo',
            'precisa_mudar_senha' => $dados['precisa_mudar_senha'] ?? false
        ]);
        
        return $stmt->fetchColumn();
        
    } catch (PDOException $e) {
        error_log("Erro ao criar usuário: " . $e->getMessage());
        return false;
    }
}
?>
