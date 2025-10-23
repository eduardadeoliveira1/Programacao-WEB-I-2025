<?php
function calcularTotal($preco, $quantidade) {
    return $preco * $quantidade;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Calcula o total gasto em cada produto
    $maca = calcularTotal($_POST["preco_maca"], $_POST["qtd_maca"]);
    $melancia = calcularTotal($_POST["preco_melancia"], $_POST["qtd_melancia"]);
    $laranja = calcularTotal($_POST["preco_laranja"], $_POST["qtd_laranja"]);
    $repolho = calcularTotal($_POST["preco_repolho"], $_POST["qtd_repolho"]);
    $cenoura = calcularTotal($_POST["preco_cenoura"], $_POST["qtd_cenoura"]);
    $batatinha = calcularTotal($_POST["preco_batatinha"], $_POST["qtd_batatinha"]);

    // Soma o valor total da compra
    $totalCompra = $maca + $melancia + $laranja + $repolho + $cenoura + $batatinha;

    // Valor disponível de Joãozinho
    $dinheiro = 50.00;

    header("Location: ./Atividade6-pt2.php?total=" . urlencode($totalCompra) . "&saldo=" . urlencode($dinheiro));
    exit;
}
?>
