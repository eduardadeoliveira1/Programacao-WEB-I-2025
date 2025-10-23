<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Soma</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado da Soma</h2>

        <?php
        $soma = $_GET["soma"] ?? 0;
        $cor = $_GET["cor"] ?? "black";
        ?>

        <p style="color: <?= htmlspecialchars($cor) ?>; font-size: 20px;">
            O resultado da soma é: <?= htmlspecialchars($soma) ?>
        </p>

        <a href="Atividade1.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
