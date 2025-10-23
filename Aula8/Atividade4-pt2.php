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
        $area = $_GET["area"] ?? 0;
        ?>

        <?php if ($area > 10): ?>
            <h1 style="color:#ff69b4;"><?= htmlspecialchars($frase) ?></h1>
        <?php else: ?>
            <h3 style="color:#ff69b4;"><?= htmlspecialchars($frase) ?></h3>
        <?php endif; ?>

        <a href="Atividade4.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
