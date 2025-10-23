<?php
    $connectionString = "host=localhost port=5432 dbname=local user=postgres password=Dudaduda01#";
    
    $connection = pg_connect($connectionString);
    
    if (!$connection) {
        echo "Erro na conexão com o banco de dados.";
    } else {
        echo "Conexão bem-sucedida!<br>";

        $result = pg_query($connection, "SELECT COUNT(*) AS qtdtabs FROM pg_tables");

        if (!$result) {
            echo "Erro na consulta.";
        } else {
            $row = pg_fetch_assoc($result);
            echo "Quantidade de tabelas no database: " . $row['qtdtabs'];
        }
    }
?>
