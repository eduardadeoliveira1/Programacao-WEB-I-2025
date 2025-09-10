// === Função para adicionar coluna com média de cada aluno ===
function adicionarColunaTotal() {
    const tabela = document.getElementById("tabelaNotas");
    const linhas = tabela.rows;

    // Evita a duplicação da coluna totalizadora
    const primeiraLinha = linhas[0];
    const ultimaCelula = primeiraLinha.cells[primeiraLinha.cells.length - 1];
    if (ultimaCelula.id === "colunaTotal") {
        alert("A coluna totalizadora já foi adicionada!");
        return;
    }

    // Adiciona o cabeçalho da nova coluna na primeira linha
    const celulaCabecalho1 = primeiraLinha.insertCell(-1);
    celulaCabecalho1.innerHTML = "Média do Aluno";
    celulaCabecalho1.id = "colunaTotal";
    celulaCabecalho1.classList.add("total");
    celulaCabecalho1.rowSpan = "2"; // O cabeçalho da média do aluno deve ter rowspan 2

    // Adiciona o cabeçalho da nova coluna na segunda linha
    linhas[1].insertCell(-1).classList.add("total");


    // Adiciona a célula de média para cada aluno
    for (let i = 2; i < linhas.length; i++) { // Começa da terceira linha (índice 2) para ignorar os cabeçalhos
        const linhaAtual = linhas[i];
        let soma = 0;
        let count = 0;
        // Percorre as células da linha atual a partir da segunda (índice 1) para ignorar o nome do aluno
        for (let j = 1; j < linhaAtual.cells.length; j++) {
            const valor = parseFloat(linhaAtual.cells[j].innerHTML);
            // Verifica se o valor é um número
            if (!isNaN(valor)) {
                soma += valor;
                count++;
            }
        }
        const media = count > 0 ? (soma / count).toFixed(1) : "-";
        const celula = linhaAtual.insertCell(-1);
        celula.innerHTML = media;
        celula.classList.add("total");
    }
}

// ---

// === Função para adicionar linha com média de cada nota (coluna) ===
function adicionarLinhaTotal() {
    const tabela = document.getElementById("tabelaNotas");
    const linhas = tabela.rows;
    const colunas = linhas[1].cells.length; // Usa a segunda linha para contar as colunas de notas

    // Evita a duplicação da linha totalizadora
    if (document.getElementById("linhaTotal")) {
        alert("A linha totalizadora já foi adicionada!");
        return;
    }

    const novaLinha = tabela.insertRow(-1);
    novaLinha.id = "linhaTotal";

    // Percorre cada coluna para calcular a média
    for (let j = 0; j < colunas; j++) {
        const celula = novaLinha.insertCell(j);
        celula.classList.add("total");

        // A primeira célula da nova linha é o rótulo "Média"
        if (j === 0) {
            celula.innerHTML = "Média das Notas";
        } else {
            let soma = 0;
            let count = 0;
            // Percorre as linhas de dados (a partir da terceira linha, índice 2)
            for (let i = 2; i < linhas.length; i++) {
                const celulaAtual = linhas[i].cells[j];
                const valor = parseFloat(celulaAtual.innerHTML);
                // Verifica se o valor é um número
                if (!isNaN(valor)) {
                    soma += valor;
                    count++;
                }
            }
            const media = count > 0 ? (soma / count).toFixed(1) : "-";
            celula.innerHTML = media;
        }
    }
}