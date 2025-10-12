<?php
function calcularSoma($a, $b, $c) {
    return $a + $b + $c;
}

function definirCor($a, $b, $c) {
    if ($a > 10) {
        return "blue";
    } elseif ($b < $c) {
        return "green";
    } elseif ($c < $a && $c < $b) {
        return "red";
    } else {
        return "black";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $v1 = $_POST["v1"];
    $v2 = $_POST["v2"];
    $v3 = $_POST["v3"];

    $soma = calcularSoma($v1, $v2, $v3);
    $cor = definirCor($v1, $v2, $v3);
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Soma</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado da Soma</h2>
        <p style="color: <?= $cor ?>; font-size: 20px;">
            O resultado da soma é: <?= $soma ?>
        </p>
        <a href="Atividade1.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
