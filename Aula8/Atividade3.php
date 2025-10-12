<?php
function calcularAreaQuadrado($lado) {
    return $lado * $lado;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lado = $_POST["lado"];
    $area = calcularAreaQuadrado($lado);
    $frase = "A área do quadrado de lado $lado metros é $area metros quadrados.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Área</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado</h2>
        <p style="color:#ff69b4; font-size:20px;">
            <?= $frase ?>
        </p>
        <a href="Atividade3.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
