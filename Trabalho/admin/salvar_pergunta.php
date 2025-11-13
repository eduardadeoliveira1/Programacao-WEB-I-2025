<?php
require_once __DIR__ . '/../config/config.php';
header('Location: perguntas.php?atualizado=1');
exit;


$pdo = getDBConnection();
$data = json_decode(file_get_contents("php://input"), true);

try {
    if (!empty($data['id_pergunta'])) {
        $stmt = $pdo->prepare("UPDATE perguntas 
            SET texto_pergunta = :texto, tipo_resposta = :tipo, ordem_exibicao = :ordem, obrigatoria = :obrigatoria
            WHERE id_pergunta = :id");
        $stmt->execute([
            'texto' => $data['texto_pergunta'],
            'tipo' => $data['tipo_resposta'],
            'ordem' => $data['ordem_exibicao'],
            'obrigatoria' => ($data['obrigatoria'] === 'true'),
            'id' => $data['id_pergunta']
        ]);
        echo json_encode(['success' => true, 'message' => 'Pergunta atualizada com sucesso!']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO perguntas (texto_pergunta, tipo_resposta, ordem_exibicao, obrigatoria)
            VALUES (:texto, :tipo, :ordem, :obrigatoria)");
        $stmt->execute([
            'texto' => $data['texto_pergunta'],
            'tipo' => $data['tipo_resposta'],
            'ordem' => $data['ordem_exibicao'],
            'obrigatoria' => ($data['obrigatoria'] === 'true')
        ]);
        echo json_encode(['success' => true, 'message' => 'Pergunta criada com sucesso!']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
}

