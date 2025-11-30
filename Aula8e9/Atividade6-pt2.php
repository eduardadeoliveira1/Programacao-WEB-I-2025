<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Compra</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado da Compra</h2>

        <?php
        $totalCompra = $_GET["total"] ?? 0;
        $dinheiro = $_GET["saldo"] ?? 50.00;

        $totalCompra = (float)$totalCompra;
        $dinheiro = (float)$dinheiro;

        if ($totalCompra > $dinheiro) {
            $falta = $totalCompra - $dinheiro;
            echo "<p style='color:red; font-size:20px;'>O valor total da compra foi R$ " . number_format($totalCompra, 2, ',', '.') . ".<br>
            Joãozinho não tem dinheiro suficiente! Faltaram R$ " . number_format($falta, 2, ',', '.') . ".</p>";
        } elseif ($totalCompra < $dinheiro) {
            $sobra = $dinheiro - $totalCompra;
            echo "<p style='color:blue; font-size:20px;'>O valor total da compra foi R$ " . number_format($totalCompra, 2, ',', '.') . ".<br>
            Joãozinho ainda pode gastar R$ " . number_format($sobra, 2, ',', '.') . ".</p>";
        } else {
            echo "<p style='color:green; font-size:20px;'>O valor total da compra foi R$ " . number_format($totalCompra, 2, ',', '.') . ".<br>
            O saldo para compras foi esgotado!</p>";
        }
        ?>

        <a href="Atividade6.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
