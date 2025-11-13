<?php
/**
 * Funções de Banco de Dados
 * Sistema de Avaliação de Qualidade
 */

// ==================== PERGUNTAS ====================

/**
 * Busca todas as perguntas ativas ordenadas
 * @return array Array com perguntas
 */
function getPerguntasAtivas() {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT id_pergunta, texto_pergunta, tipo_resposta, ordem_exibicao, obrigatoria
        FROM perguntas
        WHERE status = 'ativa'
        ORDER BY ordem_exibicao ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Insere nova pergunta
 * @param array $dados Dados da pergunta
 * @return int ID da pergunta inserida
 */
function inserirPergunta($dados) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO perguntas (texto_pergunta, tipo_resposta, ordem_exibicao, obrigatoria, status)
        VALUES (:texto, :tipo, :ordem, :obrigatoria, :status)
        RETURNING id_pergunta
    ");
    
    $stmt->execute([
        'texto' => sanitizeText($dados['texto_pergunta']),
        'tipo' => $dados['tipo_resposta'],
        'ordem' => sanitizeInt($dados['ordem_exibicao']),
        'obrigatoria' => $dados['obrigatoria'] ? 't' : 'f',
        'status' => 'ativa'
    ]);
    
    return $stmt->fetchColumn();
}

/**
 * Atualiza pergunta existente
 * @param int $id ID da pergunta
 * @param array $dados Dados atualizados
 * @return bool True se sucesso
 */
function atualizarPergunta($id, $dados) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        UPDATE perguntas 
        SET texto_pergunta = :texto,
            tipo_resposta = :tipo,
            ordem_exibicao = :ordem,
            obrigatoria = :obrigatoria,
            data_atualizacao = CURRENT_TIMESTAMP
        WHERE id_pergunta = :id
    ");
    
    return $stmt->execute([
        'id' => sanitizeInt($id),
        'texto' => sanitizeText($dados['texto_pergunta']),
        'tipo' => $dados['tipo_resposta'],
        'ordem' => sanitizeInt($dados['ordem_exibicao']),
        'obrigatoria' => $dados['obrigatoria'] ? 't' : 'f'
    ]);
}

/**
 * Altera status da pergunta
 * @param int $id ID da pergunta
 * @param string $status Novo status
 * @return bool True se sucesso
 */
function alterarStatusPergunta($id, $status) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        UPDATE perguntas SET status = :status WHERE id_pergunta = :id
    ");
    return $stmt->execute(['id' => sanitizeInt($id), 'status' => $status]);
}

// ==================== DISPOSITIVOS ====================

/**
 * Busca todos os dispositivos
 * @return array Array com dispositivos
 */
function getDispositivos() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT d.*, s.nome_setor
        FROM dispositivo d
        LEFT JOIN setores s ON d.id_setor = s.id_setor
        ORDER BY d.nome_dispositivo
    ");
    return $stmt->fetchAll();
}

/**
 * Busca dispositivo por ID único
 * @param string $identificador Identificador único
 * @return array|false Dados do dispositivo ou false
 */
function getDispositivoPorIdentificador($identificador) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM dispositivo WHERE identificador_unico = :id AND status = 'ativo'
    ");
    $stmt->execute(['id' => sanitizeString($identificador)]);
    return $stmt->fetch();
}

/**
 * Insere novo dispositivo
 * @param array $dados Dados do dispositivo
 * @return int ID do dispositivo inserido
 */
function inserirDispositivo($dados) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO dispositivo (nome_dispositivo, id_setor, identificador_unico, status)
        VALUES (:nome, :setor, :identificador, :status)
        RETURNING id_dispositivo
    ");
    
    $stmt->execute([
        'nome' => sanitizeString($dados['nome_dispositivo']),
        'setor' => sanitizeInt($dados['id_setor']),
        'identificador' => sanitizeString($dados['identificador_unico']),
        'status' => 'ativo'
    ]);
    
    return $stmt->fetchColumn();
}

// ==================== SETORES ====================

/**
 * Busca todos os setores ativos
 * @return array Array com setores
 */
function getSetoresAtivos() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT * FROM setores WHERE status = 'ativo' ORDER BY nome_setor
    ");
    return $stmt->fetchAll();
}

/**
 * Insere novo setor
 * @param array $dados Dados do setor
 * @return int ID do setor inserido
 */
function inserirSetor($dados) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO setores (nome_setor, descricao, status)
        VALUES (:nome, :descricao, :status)
        RETURNING id_setor
    ");
    
    $stmt->execute([
        'nome' => sanitizeString($dados['nome_setor']),
        'descricao' => sanitizeText($dados['descricao']),
        'status' => 'ativo'
    ]);
    
    return $stmt->fetchColumn();
}

// ==================== AVALIAÇÕES ====================

/**
 * Salva avaliação completa
 * @param array $dados Dados da avaliação
 * @return int|false ID da avaliação ou false
 */
function salvarAvaliacao($dados) {
    $pdo = getDBConnection();
    
    try {
        $pdo->beginTransaction();
        
        // Gerar hash de sessão
        $sessaoHash = hash('sha256', uniqid(mt_rand(), true));
        
        // Inserir avaliação
        $stmt = $pdo->prepare("
            INSERT INTO avaliacoes (id_setor, id_dispositivo, feedback_textual, sessao_hash)
            VALUES (:setor, :dispositivo, :feedback, :hash)
            RETURNING id_avaliacao
        ");
        
        $stmt->execute([
            'setor' => sanitizeInt($dados['id_setor']),
            'dispositivo' => sanitizeInt($dados['id_dispositivo']),
            'feedback' => sanitizeText($dados['feedback_textual'] ?? ''),
            'hash' => $sessaoHash
        ]);
        
        $idAvaliacao = $stmt->fetchColumn();
        
        // Inserir respostas
        $stmt = $pdo->prepare("
            INSERT INTO respostas (id_avaliacao, id_pergunta, resposta_numerica, resposta_texto)
            VALUES (:avaliacao, :pergunta, :numerica, :texto)
        ");
        
        foreach ($dados['respostas'] as $resposta) {
            $stmt->execute([
                'avaliacao' => $idAvaliacao,
                'pergunta' => sanitizeInt($resposta['id_pergunta']),
                'numerica' => isset($resposta['resposta_numerica']) ? sanitizeInt($resposta['resposta_numerica']) : null,
                'texto' => isset($resposta['resposta_texto']) ? sanitizeText($resposta['resposta_texto']) : null
            ]);
        }
        
        // Atualizar última avaliação do dispositivo
        $stmt = $pdo->prepare("
            UPDATE dispositivo SET ultima_avaliacao = CURRENT_TIMESTAMP WHERE id_dispositivo = :id
        ");
        $stmt->execute(['id' => sanitizeInt($dados['id_dispositivo'])]);
        
        $pdo->commit();
        return $idAvaliacao;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erro ao salvar avaliação: " . $e->getMessage());
        return false;
    }
}

/**
 * Busca estatísticas gerais
 * @param string $dataInicio Data inicial (opcional)
 * @param string $dataFim Data final (opcional)
 * @return array Estatísticas
 */
function getEstatisticas($dataInicio = null, $dataFim = null) {
    $pdo = getDBConnection();
    
    $where = "";
    $params = [];
    
    if ($dataInicio && $dataFim) {
        $where = "WHERE a.data_hora_avaliacao BETWEEN :inicio AND :fim";
        $params = ['inicio' => $dataInicio, 'fim' => $dataFim];
    }
    
    $stmt = $pdo->prepare("
        SELECT 
            COUNT(DISTINCT a.id_avaliacao) as total_avaliacoes,
            ROUND(AVG(r.resposta_numerica::numeric), 2) as media_geral,
            COUNT(DISTINCT a.id_setor) as setores_avaliados,
            COUNT(DISTINCT a.id_dispositivo) as dispositivos_ativos
        FROM avaliacoes a
        LEFT JOIN respostas r ON a.id_avaliacao = r.id_avaliacao
        $where
    ");
    
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * Busca média por pergunta
 * @return array Médias por pergunta
 */
function getMediaPorPergunta() {
    $pdo = getDBConnection();
    $stmt = $pdo->query("
        SELECT 
            p.texto_pergunta,
            ROUND(AVG(r.resposta_numerica::numeric), 2) as media,
            COUNT(r.id_resposta) as total_respostas
        FROM perguntas p
        LEFT JOIN respostas r ON p.id_pergunta = r.id_pergunta
        WHERE r.resposta_numerica IS NOT NULL
        GROUP BY p.id_pergunta, p.texto_pergunta
        ORDER BY p.ordem_exibicao
    ");
    return $stmt->fetchAll();
}

/**
 * Busca feedbacks textuais recentes
 * @param int $limit Limite de resultados
 * @return array Feedbacks
 */
function getFeedbacksRecentes($limit = 20) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT 
            a.feedback_textual,
            a.data_hora_avaliacao,
            s.nome_setor
        FROM avaliacoes a
        LEFT JOIN setores s ON a.id_setor = s.id_setor
        WHERE a.feedback_textual IS NOT NULL AND a.feedback_textual != ''
        ORDER BY a.data_hora_avaliacao DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>