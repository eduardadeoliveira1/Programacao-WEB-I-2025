<?php
$materias = array("Estrutura de Dados","Engenharia de Software","Administração e SO",
"Programação Web","Banco de Dados");

$professores = array("Bastos","Jullian","Marciel",
"Cleber","Marco");

for ($i = 0; $i < 5; $i++) {
    echo "Disciplina: " . $materias[$i] . " e Professor: " . $professores[$i] . "<br>";
}
?>

