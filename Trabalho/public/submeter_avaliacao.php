<?php
/**
 * submeter_avaliacao.php
 * Recebe e salva avaliações enviadas pelo formulário (frontend)
 * Retorna JSON com sucesso ou erro
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

// === Arquivos de configuração e funções ===
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/sanitize.php';
require_once __DIR__ . '/../includes/db_functions.php';

try {
    // Lê o JSON enviado pelo JavaScript
    $dados = json_decode(file_get_contents('php://input'), true);

    if (!$dados || empty($dados['respostas'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Dados inválidos ou vazios recebidos.'
        ]);
        exit;
    }

    // Salva a avaliação (função vem de db_functions.php)
    $idAvaliacao = salvarAvaliacao($dados);

    if ($idAvaliacao) {
        echo json_encode([
            'success' => true,
            'message' => 'Sua avaliação foi registrada com sucesso!',
            'id_avaliacao' => $idAvaliacao
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Falha ao salvar a avaliação. Verifique o log do servidor.'
        ]);
    }

} catch (Exception $e) {
    error_log("Erro ao submeter avaliação: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno no servidor: ' . $e->getMessage()
    ]);
}
