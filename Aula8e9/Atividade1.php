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

    header("Location: Atividade1-pt2.php?soma=$soma&cor=$cor");
    exit;
}
?>

