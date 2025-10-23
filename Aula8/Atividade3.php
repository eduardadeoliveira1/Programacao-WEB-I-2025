<?php
function calcularAreaQuadrado($lado) {
    return $lado * $lado;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lado = $_POST["lado"];
    $area = calcularAreaQuadrado($lado);
    $frase = "A área do quadrado de lado $lado metros é $area metros quadrados.";

    header("Location: Atividade3-pt2.php?frase=" . urlencode($frase));
    exit;
}
?>
