<?php

class Contato {
    private $tipo;     
    private $valor;

    public function __construct($tipo, $valor) {
        $this->tipo = $tipo;
        $this->valor = $valor;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }
}

class Pessoa {

    private $nome;
    private $sobreNome;
    private $tipo;
    private $dataNascimento;
    private $dataInstancia;
    private $contatos = array();

    public function __construct() {
        $this->tipo = 1;
        $this->dataInstancia = new DateTime();
    }


    public function getNome() {
        return $this->nome;
    }

    public function getSobreNome() {
        return $this->sobreNome;
    }

    public function getNomeCompleto() {
        return $this->nome . " " . $this->sobreNome;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function getDataInstancia() {
        return $this->dataInstancia->format('d/m/Y H:i:s');
    }

    public function getIdade() {
        $hoje = new DateTime();
        $diff = $hoje->diff($this->dataNascimento);
        return $diff->y;
    }

    public function getContatoPeloTipo($tipo) {
        foreach ($this->contatos as $contato) {
            if ($contato->getTipo() == $tipo) {
                return $contato;
            }
        }
        return null;
    }


    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setSobreNome($sobreNome) {
        $this->sobreNome = $sobreNome;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }


    public function AddContato($contato) {
        array_push($this->contatos, $contato);
    }
}


$pessoa = new Pessoa();
$pessoa->setNome("Eduarda");
$pessoa->setSobreNome("de Oliveira");
$pessoa->setDataNascimento(new DateTime("2002-09-14")); 


$email = new Contato(1, "eduarda@email.com");
$telefone = new Contato(2, "(47) 99999-0000");


$pessoa->AddContato($email);
$pessoa->AddContato($telefone);


echo "Nome Completo: " . $pessoa->getNomeCompleto() . "<br>";
echo "Idade: " . $pessoa->getIdade() . " anos<br>";
echo "Data de Instanciação: " . $pessoa->getDataInstancia() . "<br>";
echo "Email: " . $pessoa->getContatoPeloTipo(1)->getValor() . "<br>";
echo "Telefone: " . $pessoa->getContatoPeloTipo(2)->getValor() . "<br>";

?>
