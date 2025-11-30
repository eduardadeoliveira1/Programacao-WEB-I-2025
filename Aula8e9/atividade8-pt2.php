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
        $valorVista = $_GET["vista"] ?? 8654;
        $parcelas = $_GET["parcelas"] ?? 0;
        $montante = $_GET["montante"] ?? 0;
        $valorParcela = $_GET["valorParcela"] ?? 0;
        $taxa = $_GET["taxa"] ?? 0;

        $juros = $montante - $valorVista;

        echo "<p style='font-size:20px; color:#ff69b4;'>
            Valor à vista da moto: R$ " . number_format($valorVista, 2, ',', '.') . "<br>
            Quantidade de parcelas: $parcelas vezes<br>
            Taxa de juros simples: " . ($taxa * 100) . "%<br>
            Valor total pago: R$ " . number_format($montante, 2, ',', '.') . "<br>
            Valor de cada parcela: R$ " . number_format($valorParcela, 2, ',', '.') . "<br>
            Valor total de juros pagos: <strong>R$ " . number_format($juros, 2, ',', '.') . "</strong>
        </p>";
        ?>

        <a href="atividade8.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
