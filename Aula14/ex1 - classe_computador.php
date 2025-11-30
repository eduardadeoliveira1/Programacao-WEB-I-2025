<?php

class Computador {
    private $estado; // Pode ser 'Ligado' ou 'Desligado'

    public function __construct() {
        $this->estado = "Desligado"; // Estado padrão
    }

    public function ligar() {
        $this->estado = "Ligado";
        echo "Ligado<br>";
    }

    public function desligar() {
        $this->estado = "Desligado";
        echo "Desligado<br>";
    }

    public function status() {
        return "Status atual: " . $this->estado;
    }
}

?>
