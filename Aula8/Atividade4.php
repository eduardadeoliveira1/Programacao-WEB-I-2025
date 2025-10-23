<?php
function calcularAreaRetangulo($a, $b) {
    return $a * $b;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = $_POST["a"];
    $b = $_POST["b"];
    $area = calcularAreaRetangulo($a, $b);
    $frase = "A área do retângulo de lados $a e $b metros é $area metros quadrados.";


    header("Location: Atividade4-pt2.php?area=$area&frase=" . urlencode($frase));
    exit;
}
?>
