<?php
    $salario1= 1000;
    $salario2= 2000;
    $salario1= $salario2;
    ++$salario2;
    $salario1 = $salario1 * 1.10;
    echo "Valor Salário 1: $salario1 e Valor Salário 2: $salario2"."<br>";
    for ($x = 0; $x < 100; $x++){
        $salario1++; 
        if($x == 50){
            break;
        }
        }
    if($salario1 > $salario2) { 
        echo "O Valor da variável 1 é maior que o valor da variável 2";
    }
    else if($salario1 < $salario2){
       echo "O Valor da variável 1 é menor que o valor da variável 2";
    }
    else {"Os valores são iguais";}
?>