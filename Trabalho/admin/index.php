<?php
/**
 * Painel Administrativo - Página Inicial
 * Tema Pousada do Sol 🌿
 */
require_once __DIR__ . '/../config/config.php';
protectAdminPage();

$usuario = getUsuarioLogado();
$estatisticas = getEstatisticas();
$feedbacks = getFeedbacksRecentes(10);

// Exemplo: buscar dados para o gráfico (avaliações por setor)
$pdo = getDBConnection();
$stmt = $pdo->query("
    SELECT s.nome_setor, COUNT(a.id_avaliacao) AS total
    FROM avaliacoes a
    JOIN setores s ON s.id_setor = a.id_setor
    GROUP BY s.nome_setor
    ORDER BY s.nome_setor
");
$dadosGrafico = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Painel - <?php echo SITE_NAME; ?></title>
<link rel="stylesheet" href="css/admin.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="index.php" class="nav-item active">🏠 Início</a>
            <a href="perguntas.php" class="nav-item">📝 Gerenciar Perguntas</a>
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
            <h1>Bem-vindo(a), <?php echo htmlspecialchars($usuario['nome_completo']); ?>!</h1>
            <p>Gerencie as avaliações, perguntas e usuários do sistema.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Avaliações</h3>
                <p class="stat-value"><?php echo $estatisticas['total_avaliacoes'] ?? 0; ?></p>
            </div>
            <div class="stat-card">
                <h3>Média Geral</h3>
                <p class="stat-value"><?php echo $estatisticas['media_geral'] ?? '-'; ?></p>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="section-card">
            <h2>📊 Avaliações por Setor</h2>
            <canvas id="graficoSetores" height="100"></canvas>
        </div>

        <div class="section-card">
            <h2>🌿 Feedbacks Recentes</h2>
            <?php if (empty($feedbacks)): ?>
                <p class="text-muted">Nenhum feedback recebido ainda.</p>
            <?php else: ?>
                <ul class="feedback-list">
                    <?php foreach ($feedbacks as $f): ?>
                        <li class="feedback-item">
                            <div class="feedback-header">
                                <span class="feedback-setor"><?php echo htmlspecialchars($f['nome_setor']); ?></span>
                                <span class="feedback-date"><?php echo formatarDataHora($f['data_hora_avaliacao']); ?></span>
                            </div>
                            <div class="feedback-text"><?php echo htmlspecialchars($f['feedback_textual']); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
const ctx = document.getElementById('graficoSetores');
const grafico = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($dadosGrafico, 'nome_setor')); ?>,
        datasets: [{
            label: 'Total de Avaliações',
            data: <?php echo json_encode(array_column($dadosGrafico, 'total')); ?>,
            backgroundColor: 'rgba(59, 140, 102, 0.7)',
            borderColor: '#3b8c66',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#3b8c66',
                titleColor: '#fff',
                bodyColor: '#fff'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>
</body>
</html>
