<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado do Financiamento</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado do Financiamento</h2>

        <?php
        $juros = $_GET["juros"] ?? 0;
        $totalPago = $_GET["total"] ?? 0;
        $valorVista = $_GET["vista"] ?? 0;

        $juros = (float)$juros;
        $totalPago = (float)$totalPago;
        $valorVista = (float)$valorVista;

        echo "<p style='font-size:20px; color:#ff69b4;'>
            Valor do carro à vista: R$ " . number_format($valorVista, 2, ',', '.') . "<br>
            Valor total pago no financiamento: R$ " . number_format($totalPago, 2, ',', '.') . "<br>
            Valor gasto apenas com juros: <strong>R$ " . number_format($juros, 2, ',', '.') . "</strong>
        </p>";
        ?>

        <a href="atividade7.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
