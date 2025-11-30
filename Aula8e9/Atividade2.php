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

    header("Location: Atividade2-pt2.php?resultado=" . urlencode($resultado));
    exit;
}
?>
