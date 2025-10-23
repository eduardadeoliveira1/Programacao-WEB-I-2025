<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultado da Verificação</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Resultado</h2>

        <?php
        $resultado = $_GET["resultado"] ?? "Nenhum valor informado.";
        ?>

        <p style="color:#ff69b4; font-size:20px;">
            <?= htmlspecialchars($resultado) ?>
        </p>

        <a href="Atividade2.html" class="voltar">⬅ Voltar</a>
    </div>
</body>
</html>
