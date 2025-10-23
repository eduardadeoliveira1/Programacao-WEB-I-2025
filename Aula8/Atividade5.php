<?php
function calcularAreaTriangulo($base, $altura) {
    return ($base * $altura) / 2;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $base = $_POST["base"];
    $altura = $_POST["altura"];
    $area = calcularAreaTriangulo($base, $altura);
    $frase = "A área do triângulo retângulo com base $base m e altura $altura m é $area metros quadrados.";

    // Redireciona para a página de resultado, passando a frase pela URL
    header("Location: Atividade5-pt2.php?frase=" . urlencode($frase));
    exit;
}
?>
