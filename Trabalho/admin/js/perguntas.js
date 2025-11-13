document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-pergunta");
    const tabela = document.getElementById("tabelaPerguntas");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const dados = Object.fromEntries(new FormData(form).entries());
        const response = await fetch("salvar_pergunta.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(dados)
        });

        const result = await response.json();
        alert(result.message);
        if (result.success) location.reload();
    });

    tabela.addEventListener("click", async (e) => {
        if (e.target.classList.contains("btnEditar")) {
            const id = e.target.dataset.id;
            const response = await fetch("buscar_pergunta.php?id=" + id);
            const p = await response.json();

            document.getElementById("id_pergunta").value = p.id_pergunta;
            document.getElementById("texto_pergunta").value = p.texto_pergunta;
            document.getElementById("tipo_resposta").value = p.tipo_resposta;
            document.getElementById("ordem_exibicao").value = p.ordem_exibicao;
            document.getElementById("obrigatoria").value = p.obrigatoria ? "true" : "false";
        }

        if (e.target.classList.contains("btnExcluir")) {
            if (!confirm("Deseja realmente excluir esta pergunta?")) return;
            const id = e.target.dataset.id;

            const response = await fetch("excluir_pergunta.php?id=" + id, { method: "DELETE" });
            const result = await response.json();

            alert(result.message);
            if (result.success) location.reload();
        }
    });
});
