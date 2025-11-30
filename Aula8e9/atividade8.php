<?php
function calcularParcelas($valor, $parcelas) {
    // Define a taxa conforme a quantidade de parcelas
    switch ($parcelas) {
        case 24:
            $taxa = 0.015;
            break;
        case 36:
            $taxa = 0.020;
            break;
        case 48:
            $taxa = 0.025;
            break;
        case 60:
            $taxa = 0.030;
            break;
        default:
            $taxa = 0;
    }

    // Juros simples
    $montante = $valor * (1 + ($taxa * ($parcelas / 12))); // proporcional ao tempo
    $valorParcela = $montante / $parcelas;

    return [$montante, $valorParcela, $taxa];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valorVista = 8654;
    $parcelas = $_POST["parcelas"];

    list($montante, $valorParcela, $taxa) = calcularParcelas($valorVista, $parcelas);

    header("Location: atividade8-pt2.php?vista=" . urlencode($valorVista) . "&parcelas=" . urlencode($parcelas) .
           "&montante=" . urlencode($montante) . "&valorParcela=" . urlencode($valorParcela) .
           "&taxa=" . urlencode($taxa));
    exit;
}
?>
