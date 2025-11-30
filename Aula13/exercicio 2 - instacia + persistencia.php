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

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function getIdade() {
        $hoje = new DateTime();
        $diff = $hoje->diff($this->dataNascimento);
        return $diff->y;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setSobreNome($sobreNome) {
        $this->sobreNome = $sobreNome;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function AddContato($contato) {
        array_push($this->contatos, $contato);
    }

    public function getContatoPeloTipo($tipo) {
        foreach ($this->contatos as $c) {
            if ($c->getTipo() == $tipo) return $c;
        }
        return null;
    }
}

// CRIA INSTÂNCIAS PARA OS MEMBROS DA SUA FAMÍLIA

$pessoas = [];  // array que vai guardar todas as instâncias

// EU MESMO 
$eduarda = new Pessoa();
$eduarda->setNome("Eduarda");
$eduarda->setSobreNome("de Oliveira");
$eduarda->setDataNascimento(new DateTime("2002-09-14"));
$eduarda->AddContato(new Contato(1, "eduarda@email.com"));
$pessoas[] = $eduarda;

// PAI
$pai = new Pessoa();
$pai->setNome("Carlos");
$pai->setSobreNome("de Oliveira");
$pai->setDataNascimento(new DateTime("1975-03-10"));
$pai->AddContato(new Contato(1, "carlos@email.com"));
$pessoas[] = $pai;

// MÃE 
$mae = new Pessoa();
$mae->setNome("Maria");
$mae->setSobreNome("de Oliveira");
$mae->setDataNascimento(new DateTime("1978-07-22"));
$mae->AddContato(new Contato(1, "maria@email.com"));
$pessoas[] = $mae;

// IRMÃO
$irmao = new Pessoa();
$irmao->setNome("João");
$irmao->setSobreNome("de Oliveira");
$irmao->setDataNascimento(new DateTime("2005-11-02"));
$irmao->AddContato(new Contato(1, "joao@email.com"));
$pessoas[] = $irmao;

//SALVAR O ARRAY EM UM ARQUIVO .TXT


$conteudo = "=== LISTA DE PESSOAS DA FAMÍLIA ===\n\n";

foreach ($pessoas as $p) {
    $conteudo .= "Nome: " . $p->getNomeCompleto() . "\n";
    $conteudo .= "Idade: " . $p->getIdade() . "\n";
    $conteudo .= "Data de Nascimento: " . $p->getDataNascimento()->format('d/m/Y') . "\n";

    $email = $p->getContatoPeloTipo(1);
    if ($email !== null) {
        $conteudo .= "E-mail: " . $email->getValor() . "\n";
    }

    $conteudo .= "-------------------------------\n";
}

$file = "familia.txt";
file_put_contents($file, $conteudo);

echo "Arquivo 'familia.txt' salvo com sucesso!";

?>
