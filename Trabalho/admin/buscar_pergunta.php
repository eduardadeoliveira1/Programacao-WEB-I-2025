<?php
require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json; charset=utf-8');

$pdo = getDBConnection();
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM perguntas WHERE id_pergunta = :id");
$stmt->execute(['id' => $id]);
echo json_encode($stmt->fetch());
