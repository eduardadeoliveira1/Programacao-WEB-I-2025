<?php
function calcularAreaTriangulo($base, $altura) {
    return ($base * $altura) / 2;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $base = $_POST["base"];
    $altura = $_POST["altura"];
    $area = calcularAreaTriangulo($base, $altura);
    $frase = "A área do triângulo retângulo com base $base m e altura $altura m é $area metros quadrados.";
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
        <a href="Atividade5.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
