<?php
/**
 * obter_perguntas.php
 * Retorna as perguntas ativas em formato JSON para o formulário
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

// === Inclui configurações e funções ===
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/sanitize.php';
require_once __DIR__ . '/../includes/db_functions.php';

try {
    $perguntas = getPerguntasAtivas();

    if ($perguntas && count($perguntas) > 0) {
        echo json_encode([
            'success' => true,
            'perguntas' => $perguntas
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Nenhuma pergunta ativa encontrada.'
        ]);
    }

} catch (Exception $e) {
    error_log("Erro ao obter perguntas: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erro no servidor: ' . $e->getMessage()
    ]);
}
