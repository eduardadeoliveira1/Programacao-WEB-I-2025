<?php
require_once __DIR__ . '/../config/config.php';
protectAdminPage();

if (empty($_SESSION['precisa_mudar_senha'])) {
    header('Location: index.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($senhaAtual) || empty($novaSenha) || empty($confirmarSenha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif ($novaSenha !== $confirmarSenha) {
        $erro = 'As senhas não coincidem.';
    } else {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT senha FROM usuarios_admin WHERE id_usuario = :id");
        $stmt->execute(['id' => $_SESSION['admin_id']]);
        $usuario = $stmt->fetch();
        
        if (!$usuario || !password_verify($senhaAtual, $usuario['senha'])) {
            $erro = 'Senha atual incorreta.';
        } else {
            $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE usuarios_admin
                SET senha = :senha, precisa_mudar_senha = FALSE
                WHERE id_usuario = :id
            ");
            $stmt->execute([
                'senha' => $novoHash,
                'id' => $_SESSION['admin_id']
            ]);
            
            unset($_SESSION['precisa_mudar_senha']);
            $sucesso = 'Senha alterada com sucesso!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Alterar Senha - Painel Admin</title>
<style>
body { font-family: sans-serif; background: #f0f4f3; display: flex; justify-content: center; align-items: center; height: 100vh; }
.container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); width: 380px; }
h2 { text-align: center; margin-bottom: 20px; color: #3b8c66; }
input { width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid #d1e3db; border-radius: 8px; }
button { width: 100%; padding: 12px; background: #3b8c66; color: white; border: none; border-radius: 8px; cursor: pointer; }
button:hover { background: #317a59; }
.alert { padding: 10px; border-radius: 8px; margin-bottom: 10px; text-align: center; }
.alert.error { background: #f8d7da; color: #721c24; }
.alert.success { background: #d4edda; color: #155724; }
</style>
</head>
<body>
<div class="container">
<h2>Alterar Senha</h2>
<?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<?php if ($sucesso): ?><div class="alert success"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>

<form method="post">
    <input type="password" name="senha_atual" placeholder="Senha atual" required>
    <input type="password" name="nova_senha" placeholder="Nova senha" required>
    <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required>
    <button type="submit">Salvar nova senha</button>
</form>
</div>
</body>
</html>
