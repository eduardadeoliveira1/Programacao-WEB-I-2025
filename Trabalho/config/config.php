<?php
/**
 * Configurações principais do sistema
 * Sistema de Avaliação de Qualidade
 */

session_start();

// ==================== CONFIGURAÇÕES GERAIS ====================

define('SITE_NAME', 'Sistema de Avaliação de Qualidade');
define('MAX_FEEDBACK_LENGTH', 500);
define('THANK_YOU_TEXT', 'A Pousada Sol agradece sua resposta e ela é muito importante para nós, pois nos ajuda a melhorar continuamente nossos serviços.');
define('PRIVACY_TEXT', 'Sua avaliação espontânea é anônima, nenhuma informação pessoal é solicitada ou armazenada.');
define('SESSION_TIMEOUT', 1800); // 30 minutos

// ==================== CONFIGURAÇÃO DO BANCO DE DADOS ====================
// (Apenas define as constantes, a função está no arquivo database.php)

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'sistema_avaliacao'); // nome do seu banco
define('DB_USER', 'postgres');
define('DB_PASS', 'sua_senha_aqui'); // senha do PostgreSQL

// ==================== INCLUSÕES PADRÃO ====================

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/sanitize.php';
require_once __DIR__ . '/../includes/db_functions.php';
require_once __DIR__ . '/../includes/auth.php';
