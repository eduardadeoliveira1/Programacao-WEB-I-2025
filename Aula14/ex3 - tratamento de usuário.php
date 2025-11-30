<?php

class Endereco {
    private $rua;
    private $bairro;
    private $cidade;

    public function __construct($rua, $bairro, $cidade) {
        $this->rua = $rua;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
    }

    public function getRua() {
        return $this->rua;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function toArray() {
        return [
            "rua" => $this->rua,
            "bairro" => $this->bairro,
            "cidade" => $this->cidade
        ];
    }
}

class Pessoa {
    private $nome;
    private $cpf;
    private $email;
    private $endereco;
    private $telefone;
    private $dataNascimento;
    private $estadoCivil;
    private $genero;

    public function __construct(
        $nome,
        $cpf,
        $email,
        Endereco $endereco,
        $telefone,
        $dataNascimento,
        $estadoCivil,
        $genero
    ) {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->dataNascimento = $dataNascimento;
        $this->estadoCivil = $estadoCivil;
        $this->genero = $genero;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($novoNome) {
        $this->nome = $novoNome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function getGenero() {
        return $this->genero;
    }

    public function toArray() {
        return [
            "nome" => $this->nome,
            "cpf" => $this->cpf,
            "email" => $this->email,
            "telefone" => $this->telefone,
            "dataNascimento" => $this->dataNascimento,
            "estadoCivil" => $this->estadoCivil,
            "genero" => $this->genero,
            "endereco" => $this->endereco->toArray()
        ];
    }
}

?>
