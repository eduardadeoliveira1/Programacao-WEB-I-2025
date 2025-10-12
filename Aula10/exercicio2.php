<?php
$cookieUsuario = "usuario";
$cookieTime = "time";
if(!isset($_COOKIE[$cookieUsuario]) || !isset($_COOKIE[$cookieTime])) {
    echo "Cookies não estão definidos.";
} else {
    echo "Cookie 'usuario' está definido!<br>";
    echo "O valor é: " . $_COOKIE[$cookieUsuario] . "<br>";
    echo "Cookie 'time' está definido!<br>";
    echo "O valor é: " . $_COOKIE[$cookieTime];
}
?>