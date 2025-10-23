<?php
include 'atividade10.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Árvore Recursiva</title>
    <link rel="stylesheet" href="ArquivoLayout.css">
</head>
<body>
    <div class="container">
        <h2>Exemplo de Árvore Recursiva</h2>
        <div style="text-align:left; display:inline-block; background:#fff; padding:15px; border-radius:8px; box-shadow:0 0 5px rgba(0,0,0,0.1);">
            <?php exibirArvore($pastas); ?>
        </div>
    </div>
</body>
</html>
