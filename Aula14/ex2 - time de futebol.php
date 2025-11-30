<?php

class Jogador {
    private $nome;
    private $posicao;
    private $dataNascimento;

    public function __construct($nome, $posicao, $dataNascimento) {
        $this->nome = $nome;
        $this->posicao = $posicao;
        $this->dataNascimento = $dataNascimento;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getPosicao() {
        return $this->posicao;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function toArray() {
        return [
            "nome" => $this->nome,
            "posicao" => $this->posicao,
            "dataNascimento" => $this->dataNascimento
        ];
    }
}

class Time {
    private $nome;
    private $anoFundacao;
    private $jogadores = [];
    private $delegacoes = [];

    public function __construct($nome, $anoFundacao) {
        $this->nome = $nome;
        $this->anoFundacao = $anoFundacao;
    }

    public function adicionarJogador(Jogador $jogador) {
        $this->jogadores[] = $jogador;
    }

    public function getJogadores() {
        return $this->jogadores;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getAnoFundacao() {
        return $this->anoFundacao;
    }

    public function toArray() {
        return [
            "nome" => $this->nome,
            "anoFundacao" => $this->anoFundacao,
            "jogadores" => array_map(
                fn($j) => $j->toArray(),
                $this->jogadores
            ),
            "delegacoes" => $this->delegacoes
        ];
    }
}

?>
