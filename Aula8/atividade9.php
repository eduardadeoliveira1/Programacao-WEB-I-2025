<?php
// Valor à vista
$capital = 8654;

// Taxas e prazos
$opcoes = [
    ["parcelas" => 24, "juros" => 0.02],
    ["parcelas" => 36, "juros" => 0.023],
    ["parcelas" => 48, "juros" => 0.026],
    ["parcelas" => 60, "juros" => 0.029],
];

echo "<h2>Resultado - Juquinha e os Juros Compostos</h2>";
echo "<p>Valor à vista: R$ " . number_format($capital, 2, ',', '.') . "</p>";

echo "<table border='1' cellpadding='8' cellspacing='0'>
        <tr>
            <th>Parcelas</th>
            <th>Taxa de Juros</th>
            <th>Valor Total (Montante)</th>
            <th>Valor da Parcela</th>
        </tr>";

foreach ($opcoes as $opcao) {
    $t = $opcao["parcelas"];
    $i = $opcao["juros"];

    // Fórmula: M = C * (1 + i)^t
    $montante = $capital * pow((1 + $i), $t);
    $valorParcela = $montante / $t;

    echo "<tr>
            <td>{$t}x</td>
            <td>" . ($i * 100) . "%</td>
            <td>R$ " . number_format($montante, 2, ',', '.') . "</td>
            <td>R$ " . number_format($valorParcela, 2, ',', '.') . "</td>
          </tr>";
}

echo "</table>";
?>
