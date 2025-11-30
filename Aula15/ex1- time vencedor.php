<?php

class Time {
    private $nome;
    private $quantidadeJogadores;

    public function __construct($nome, $quantidadeJogadores) {
        $this->nome = $nome;
        $this->quantidadeJogadores = $quantidadeJogadores;
    }

    public function getNome() {
        return $this->nome;
    }
}

class Jogador {
    private $nome;
    private $numeroCamisa;

    public function __construct($nome, $numeroCamisa) {
        $this->nome = $nome;
        $this->numeroCamisa = $numeroCamisa;
    }

    public function getNome() {
        return $this->nome;
    }
}

class Jogo {
    private $timeA;
    private $timeB;
    private $golsA;
    private $golsB;

    public function __construct(Time $timeA, Time $timeB, $golsA, $golsB) {
        $this->timeA = $timeA;
        $this->timeB = $timeB;
        $this->golsA = $golsA;
        $this->golsB = $golsB;
    }

    public function descobrirVencedor() {
        if ($this->golsA > $this->golsB) {
            return $this->timeA;
        } elseif ($this->golsB > $this->golsA) {
            return $this->timeB;
        } else {
            return null; // Empate
        }
    }
}

class Partida {
    private $jogo;
    private $jogadorDestaque;

    public function __construct(Jogo $jogo, Jogador $jogadorDestaque) {
        $this->jogo = $jogo;
        $this->jogadorDestaque = $jogadorDestaque;
    }

    public function resultadoFinal() {
        $vencedor = $this->jogo->descobrirVencedor();

        if ($vencedor === null) {
            return "A partida terminou empatada.";
        }

        return "O time vencedor foi: " . $vencedor->getNome();
    }
}

?>
