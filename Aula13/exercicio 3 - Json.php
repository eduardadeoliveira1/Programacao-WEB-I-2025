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

    public function toJson() {
        return json_encode([
            "tipo"  => $this->tipo,
            "valor" => $this->valor
        ], JSON_PRETTY_PRINT);
    }
}

class Pessoa {

    private $nome;
    private $sobreNome;
    private $tipo;
    private $dataNascimento;
    private $dataInstancia;
    private $contatos = [];

    public function __construct() {
        $this->tipo = 1;
        $this->dataInstancia = new DateTime();
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

    public function getNome() {
        return $this->nome;
    }

    public function getNomeCompleto() {
        return $this->nome . " " . $this->sobreNome;
    }

    public function getContatoPeloTipo($tipo) {
        foreach ($this->contatos as $c) {
            if ($c->getTipo() == $tipo) return $c;
        }
        return null;
    }

    public function toJson() {
        $contatosArray = [];

        foreach ($this->contatos as $c) {
            $contatosArray[] = [
                "tipo"  => $c->getTipo(),
                "valor" => $c->getValor()
            ];
        }

        // Converter DateTime para string
        $dataNascimento = $this->dataNascimento ? $this->dataNascimento->format("Y-m-d") : null;
        $dataInstancia  = $this->dataInstancia->format("Y-m-d H:i:s");

        return json_encode([
            "nome"            => $this->nome,
            "sobrenome"       => $this->sobreNome,
            "tipo"            => $this->tipo,
            "dataNascimento"  => $dataNascimento,
            "dataInstancia"   => $dataInstancia,
            "contatos"        => $contatosArray
        ], JSON_PRETTY_PRINT);
    }
}

// INSTÂNCIAS DA FAMÍLIA

$pessoas = [];

// VOCÊ
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


// SALVAR CADA INSTÂNCIA EM UM ARQUIVO JSON


foreach ($pessoas as $p) {
    $fileName = "pessoa_" . strtolower(str_replace(" ", "_", $p->getNomeCompleto())) . ".json";
    file_put_contents($fileName, $p->toJson());
}

echo "Arquivos JSON gerados com sucesso!";

?>
