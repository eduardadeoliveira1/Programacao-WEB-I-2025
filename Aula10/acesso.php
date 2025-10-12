<?php
session_start();
echo "<h2>Conteúdo da sessão:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo '<a href="login.html">Voltar ao login</a>';
?>