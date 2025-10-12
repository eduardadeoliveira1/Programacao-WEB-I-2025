<?php
session_start();
if (isset($_SESSION['user_id'])) {
($_SESSION['username']) . "! Você está logado.";
} else {
    echo "Acesso negado. Por favor, faça login.";
    echo '<br><a href="login.html">Voltar ao login</a>';
}
?>