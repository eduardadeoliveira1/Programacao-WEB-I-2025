<?php
/**
 * Funções de Autenticação
 * Sistema de Avaliação de Qualidade
 */

function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

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

function protectAdminPage() {
    if (!isLoggedIn() || !isSessionValid()) {
        logout();
        header('Location: login.php?error=session_expired');
        exit();
    }
}

// ===========================================================
// LOGIN
// ===========================================================

function login($login, $senha) {
    $pdo = getDBConnection();

    // ❗ status agora é boolean, portanto TRUE
    $stmt = $pdo->prepare("
        SELECT id_usuario, login, senha, nome_completo, status, precisa_mudar_senha
        FROM usuarios_admin
        WHERE login = :login AND status = TRUE
    ");

    $stmt->execute(['login' => sanitizeString($login)]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        sleep(1);
        return false;
    }

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

    // Verificar troca obrigatória de senha
    if ($usuario['precisa_mudar_senha'] === true) {
        $_SESSION['precisa_mudar_senha'] = true;
        header('Location: alterar_senha.php');
        exit;
    }

    session_regenerate_id(true);

    $stmt = $pdo->prepare("
        UPDATE usuarios_admin 
        SET ultimo_acesso = CURRENT_TIMESTAMP
        WHERE id_usuario = :id
    ");
    $stmt->execute(['id' => $usuario['id_usuario']]);

    logLoginAttempt($login, true);
    return true;
}

function logout() {
    $_SESSION = [];

    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    session_destroy();
}

// ===========================================================
// LOGS
// ===========================================================

function logLoginAttempt($login, $sucesso) {
    $logFile = __DIR__ . '/../logs/login_attempts.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $timestamp = date('Y-m-d H:i:s');
    $status = $sucesso ? 'SUCCESS' : 'FAILED';

    $logEntry = "[$timestamp] $status - Login: $login - IP: $ip" . PHP_EOL;

    $logDir = dirname($logFile);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// ===========================================================
// UTILITÁRIOS
// ===========================================================

function hashPassword($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

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

function isLoginBlocked($ip) {
    $logFile = __DIR__ . '/../logs/login_attempts.log';

    if (!file_exists($logFile)) {
        return false;
    }

    $logs = file($logFile, FILE_IGNORE_NEW_LINES);
    $recentAttempts = 0;
    $timeLimit = time() - 900;

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

    return $recentAttempts >= 5;
}

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
            // ❗ status BOOLEAN
            'status' => true,
            'precisa_mudar_senha' => !empty($dados['precisa_mudar_senha'])
        ]);

        return $stmt->fetchColumn();

    } catch (PDOException $e) {
        error_log("Erro ao criar usuário: " . $e->getMessage());
        return false;
    }
}
?>
