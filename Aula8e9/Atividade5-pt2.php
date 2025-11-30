<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Área</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado</h2>

        <?php
        $frase = $_GET["frase"] ?? "Nenhum valor informado.";
        ?>

        <p style="color:#ff69b4; font-size:20px;">
            <?= htmlspecialchars($frase) ?>
        </p>

        <a href="Atividade5.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
