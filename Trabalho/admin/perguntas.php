<?php
/**
 * Gerenciamento de Perguntas - Painel Administrativo
 * Tema: Pousada do Sol 🌿
 */
require_once __DIR__ . '/../config/config.php';
protectAdminPage();

$usuario = getUsuarioLogado();
$pdo = getDBConnection();

$mensagem = '';
$erro = '';

// === INSERIR OU ATUALIZAR PERGUNTA ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pergunta'] ?? null;
    $texto = sanitizeString($_POST['texto_pergunta'] ?? '');
    $tipo = sanitizeString($_POST['tipo_resposta'] ?? 'escala_0_10');
    $ordem = (int) ($_POST['ordem_exibicao'] ?? 0);
    $obrigatoria = isset($_POST['obrigatoria']) ? 'true' : 'false';

    try {
        if ($id) {
            $stmt = $pdo->prepare("
                UPDATE perguntas
                SET texto_pergunta = :texto, tipo_resposta = :tipo, ordem_exibicao = :ordem, obrigatoria = :obrigatoria
                WHERE id_pergunta = :id
            ");
            $stmt->execute([
                'texto' => $texto,
                'tipo' => $tipo,
                'ordem' => $ordem,
                'obrigatoria' => $obrigatoria,
                'id' => $id
            ]);
            header('Location: perguntas.php?atualizado=1');
            exit;
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO perguntas (texto_pergunta, tipo_resposta, ordem_exibicao, obrigatoria)
                VALUES (:texto, :tipo, :ordem, :obrigatoria)
            ");
            $stmt->execute([
                'texto' => $texto,
                'tipo' => $tipo,
                'ordem' => $ordem,
                'obrigatoria' => $obrigatoria
            ]);
            header('Location: perguntas.php?inserido=1');
            exit;
        }
    } catch (PDOException $e) {
        $erro = "Erro ao salvar pergunta: " . $e->getMessage();
    }
}

// === EXCLUIR PERGUNTA ===
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM perguntas WHERE id_pergunta = :id");
        $stmt->execute(['id' => $id]);
        header('Location: perguntas.php?excluido=1');
        exit;
    } catch (PDOException $e) {
        $erro = "Erro ao excluir pergunta: " . $e->getMessage();
    }
}

// === BUSCAR PERGUNTAS ===
$stmt = $pdo->query("SELECT * FROM perguntas ORDER BY ordem_exibicao ASC");
$perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Gerenciar Perguntas - Pousada do Sol 🌿</title>
<link rel="stylesheet" href="css/perguntas.css">
</head>
<body>
<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../public/img/logo_pousada.png" alt="Logo Pousada" class="logo">
            <h2>Pousada do Sol</h2>
            <p class="subtitle">Painel Administrativo</p>
        </div>

        <nav class="sidebar-nav">
            <a href="index.php" class="nav-item">🏠 Início</a>
            <a href="perguntas.php" class="nav-item active">📝 Gerenciar Perguntas</a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <strong><?php echo htmlspecialchars($usuario['nome_completo']); ?></strong>
                <small><?php echo htmlspecialchars($usuario['login']); ?></small>
            </div>
            <a href="logout.php" class="btn-logout">Sair da Sessão</a>
        </div>
    </aside>

    <!-- Main -->
    <main class="main-content">
        <div class="page-header">
            <h1>Gerenciar Perguntas</h1>
            <p>Adicione, edite ou remova perguntas da avaliação.</p>
        </div>

        <?php if (isset($_GET['inserido'])): ?>
            <div class="alert alert-success">✅ Pergunta adicionada com sucesso!</div>
        <?php elseif (isset($_GET['atualizado'])): ?>
            <div class="alert alert-success">✅ Pergunta atualizada com sucesso!</div>
        <?php elseif (isset($_GET['excluido'])): ?>
            <div class="alert alert-success">🗑️ Pergunta removida com sucesso!</div>
        <?php elseif ($erro): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <div class="form-card">
            <h2>Adicionar / Editar Pergunta</h2>
            <form method="POST" action="">
                <input type="hidden" name="id_pergunta" id="id_pergunta">
                <div class="form-group">
                    <label for="texto_pergunta">Texto da Pergunta</label>
                    <input type="text" id="texto_pergunta" name="texto_pergunta" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="tipo_resposta">Tipo de Resposta</label>
                    <select id="tipo_resposta" name="tipo_resposta" class="form-control">
                        <option value="escala_0_10">Escala 0 a 10</option>
                        <option value="texto_livre">Texto Livre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ordem_exibicao">Ordem de Exibição</label>
                    <input type="number" id="ordem_exibicao" name="ordem_exibicao" class="form-control" min="1" required>
                </div>
                <div class="form-group checkbox-group">
                    <label><input type="checkbox" name="obrigatoria" id="obrigatoria"> Pergunta obrigatória</label>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>

        <div class="section-card">
            <h2>🌿 Perguntas Cadastradas</h2>
            <div class="table-container">
                <table class="perguntas-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pergunta</th>
                            <th>Tipo</th>
                            <th>Ordem</th>
                            <th>Obrigatória</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($perguntas)): ?>
                            <tr><td colspan="6" class="text-muted">Nenhuma pergunta cadastrada.</td></tr>
                        <?php else: ?>
                            <?php foreach ($perguntas as $p): ?>
                                <tr>
                                    <td><?php echo $p['id_pergunta']; ?></td>
                                    <td><?php echo htmlspecialchars($p['texto_pergunta']); ?></td>
                                    <td><?php echo htmlspecialchars($p['tipo_resposta']); ?></td>
                                    <td><?php echo $p['ordem_exibicao']; ?></td>
                                    <td><?php echo ($p['obrigatoria'] === 't' || $p['obrigatoria'] === true) ? 'Sim' : 'Não'; ?></td>
                                    <td class="actions">
                                        <button class="btn-edit" onclick='editarPergunta(<?php echo json_encode($p); ?>)'>Editar</button>
                                        <a href="?delete=<?php echo $p['id_pergunta']; ?>" class="btn-delete" onclick="return confirm('Excluir esta pergunta?');">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
function editarPergunta(p) {
    document.getElementById('id_pergunta').value = p.id_pergunta;
    document.getElementById('texto_pergunta').value = p.texto_pergunta;
    document.getElementById('tipo_resposta').value = p.tipo_resposta;
    document.getElementById('ordem_exibicao').value = p.ordem_exibicao;
    document.getElementById('obrigatoria').checked = (p.obrigatoria === 't' || p.obrigatoria === true);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
</body>
</html>
