<?php

require_once __DIR__ . '/../config/config.php';

// Se já está logado, redireciona para dashboard
if (isLoggedIn() && isSessionValid()) {
    header('Location: index.php');
    exit;
}

$erro = '';
$sucesso = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isLoginBlocked($ip)) {
        $erro = 'Muitas tentativas falhadas. Tente novamente em 15 minutos.';
    } elseif (empty($login) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        if (login($login, $senha)) {
            header('Location: index.php');
            exit;
        } else {
            $erro = 'Login ou senha incorretos.';
        }
    }
}

// Mensagens via GET
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'session_expired':
            $erro = 'Sua sessão expirou. Faça login novamente.';
            break;
        case 'unauthorized':
            $erro = 'Acesso não autorizado.';
            break;
    }
}

if (isset($_GET['success']) && $_GET['success'] === 'logout') {
    $sucesso = 'Logout realizado com sucesso.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../public/img/logo_pousada.png" alt="Logo Pousada" class="logo">
            <h1>Pousada do Sol</h1>
            <p>Painel Administrativo</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="login">Usuário</label>
                <input 
                    type="text" 
                    id="login" 
                    name="login" 
                    required 
                    autofocus
                    autocomplete="username"
                    value="<?php echo htmlspecialchars($_POST['login'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input 
                    type="password" 
                    id="senha" 
                    name="senha" 
                    required
                    autocomplete="current-password">
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="footer-text">
            © <?php echo date('Y'); ?> - Sistema de Avaliação 
        </div>
    </div>
</body>
</html>
