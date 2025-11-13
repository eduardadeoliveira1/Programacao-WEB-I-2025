<?php
/**
 * Logout do Sistema
 * Sistema de Avaliação de Qualidade
 */

require_once __DIR__ . '/../config/config.php';

logout();

header('Location: login.php?success=logout');
exit;
?>