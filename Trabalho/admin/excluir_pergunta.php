<?php
require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json; charset=utf-8');

$pdo = getDBConnection();
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM perguntas WHERE id_pergunta = :id");
$ok = $stmt->execute(['id' => $id]);

echo json_encode([
    'success' => $ok,
    'message' => $ok ? 'Pergunta excluída com sucesso!' : 'Erro ao excluir a pergunta.'
]);
