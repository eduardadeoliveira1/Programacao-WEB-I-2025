<?php
function calcularJuros($valorVista, $valorParcela, $qtdParcelas) {
    $totalPago = $valorParcela * $qtdParcelas;
    return $totalPago - $valorVista;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valorVista = $_POST["valor_vista"];
    $valorParcela = $_POST["valor_parcela"];
    $qtdParcelas = $_POST["qtd_parcelas"];

    $juros = calcularJuros($valorVista, $valorParcela, $qtdParcelas);
    $totalPago = $valorParcela * $qtdParcelas;

    header("Location: atividade7-pt2.php?juros=" . urlencode($juros) . "&total=" . urlencode($totalPago) . "&vista=" . urlencode($valorVista));
    exit;
}
?>
