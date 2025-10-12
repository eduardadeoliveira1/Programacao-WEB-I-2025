<?php
function calcularAreaRetangulo($a, $b) {
    return $a * $b;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = $_POST["a"];
    $b = $_POST["b"];
    $area = calcularAreaRetangulo($a, $b);
    $frase = "A área do retângulo de lados $a e $b metros é $area metros quadrados.";
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
        <?php if ($area > 10): ?>
            <h1 style="color:#ff69b4;"><?= $frase ?></h1>
        <?php else: ?>
            <h3 style="color:#ff69b4;"><?= $frase ?></h3>
        <?php endif; ?>
        <a href="Atividade4.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
