<?php
function verificarDivisibilidade($n) {
    if ($n % 2 == 0) {
        return "Valor divisível por 2";
    } else {
        return "O valor não é divisível por 2";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero = $_POST["num"];
    $resultado = verificarDivisibilidade($numero);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Verificação</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado</h2>
        <p style="color:#ff69b4; font-size:20px;">
            <?= $resultado ?>
        </p>
        <a href="Atividade2.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
