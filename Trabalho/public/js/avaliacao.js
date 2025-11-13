/**
 * JavaScript para Formulário de Avaliação
 * Sistema de Avaliação de Qualidade
 */

document.addEventListener('DOMContentLoaded', function() {
    carregarPerguntas();
    configurarFeedback();
    configurarFormulario();
});

/**
 * Carrega perguntas do banco de dados
 */
async function carregarPerguntas() {
    const container = document.getElementById('perguntas-container');
    
    try {
        const response = await fetch('obter_perguntas.php');
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Erro ao carregar perguntas');
        }
        
        container.innerHTML = '';
        
        // Filtrar perguntas válidas antes de renderizar
        const perguntasValidas = (data.perguntas || []).filter(p => 
            p && p.id_pergunta && p.tipo_resposta
        );
        
        perguntasValidas.forEach((pergunta, index) => {
            const perguntaHTML = criarPerguntaHTML(pergunta, index);
            if (perguntaHTML) {
                container.insertAdjacentHTML('beforeend', perguntaHTML);
            }
        });
        
        configurarEscalas();
        
    } catch (error) {
        console.error('Erro:', error);
        container.innerHTML = `
            <div class="error-message">
                Erro ao carregar perguntas. Por favor, recarregue a página.
            </div>
        `;
    }
}

/**
 * Cria HTML para uma pergunta
 * @param {Object} pergunta Dados da pergunta
 * @param {number} index Índice da pergunta
 * @returns {string} HTML da pergunta
 */
function criarPerguntaHTML(pergunta, index) {
    // Validações para evitar undefined
    if (!pergunta || !pergunta.tipo_resposta || !pergunta.id_pergunta) {
        console.warn('Pergunta inválida:', pergunta);
        return '';
    }

    const tipoResposta = String(pergunta.tipo_resposta || '').toLowerCase().trim();
    const obrigatoria = pergunta.obrigatoria === 't' || pergunta.obrigatoria === true || pergunta.obrigatoria === 1;

    switch (tipoResposta) {
        case 'escala_0_10':
            return criarEscalaHTML(pergunta, index, 10, obrigatoria);
        case 'escala_0_5':
            return criarEscalaHTML(pergunta, index, 5, obrigatoria);
        case 'texto_livre':
        case 'texto':
            return criarTextoHTML(pergunta, index, obrigatoria);
        default:
            console.warn('Tipo de pergunta não reconhecido:', tipoResposta);
            return '';
    }
}

/**
 * Cria HTML para pergunta tipo escala
 */
function criarEscalaHTML(pergunta, index, max, obrigatoria) {
    const required = obrigatoria ? 'required' : '';
    const asterisco = obrigatoria ? '<span class="required">*</span>' : '';
    const textoPergunta = String(pergunta.texto_pergunta || 'Pergunta sem texto').trim();
    const idPergunta = pergunta.id_pergunta;
    
    let opcoesHTML = '';
    for (let i = 0; i <= max; i++) {
        opcoesHTML += `
            <label class="escala-option">
                <input 
                    type="radio" 
                    name="pergunta_${idPergunta}" 
                    value="${i}"
                    data-pergunta-id="${idPergunta}"
                    ${required}>
                <span class="escala-value">${i}</span>
            </label>
        `;
    }
    
    return `
        <div class="pergunta-item" data-pergunta-id="${idPergunta}">
            <div class="pergunta-texto">
                ${index + 1}. ${textoPergunta} ${asterisco}
            </div>
            <div class="escala-container">
                <div class="escala-labels">
                    <span class="label-min">Muito Insatisfeito</span>
                    <span class="label-max">Completamente Satisfeito</span>
                </div>
                <div class="escala-opcoes">
                    ${opcoesHTML}
                </div>
            </div>
        </div>
    `;
}

/**
 * Cria HTML para pergunta tipo texto
 */
function criarTextoHTML(pergunta, index, obrigatoria) {
    const required = obrigatoria ? 'required' : '';
    const asterisco = obrigatoria ? '<span class="required">*</span>' : '';
    const textoPergunta = String(pergunta.texto_pergunta || 'Pergunta sem texto').trim();
    const idPergunta = pergunta.id_pergunta;
    
    return `
        <div class="pergunta-item" data-pergunta-id="${idPergunta}">
            <div class="pergunta-texto">
                ${index + 1}. ${textoPergunta} ${asterisco}
            </div>
            <textarea 
                name="pergunta_texto_${idPergunta}"
                data-pergunta-id="${idPergunta}"
                rows="3"
                maxlength="500"
                ${required}
                placeholder="Digite sua resposta aqui..."></textarea>
        </div>
    `;
}

/**
 * Configura efeitos visuais nas escalas
 */
function configurarEscalas() {
    const radios = document.querySelectorAll('.escala-option input[type="radio"]');
    
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const name = this.name;
            if (!name) return;
            
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.parentElement.classList.remove('selected');
            });
            this.parentElement.classList.add('selected');
            this.parentElement.classList.add('pulse');
            setTimeout(() => this.parentElement.classList.remove('pulse'), 300);
        });
    });
}

/**
 * Configura contador de caracteres do feedback
 */
function configurarFeedback() {
    const textarea = document.getElementById('feedback');
    const counter = document.querySelector('.char-count');
    
    if (textarea && counter) {
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            const max = this.maxLength || 500;
            counter.textContent = `${length}/${max} caracteres`;
            counter.classList.toggle('warning', length > max * 0.9);
        });
    }
}

/**
 * Configura envio do formulário
 */
function configurarFormulario() {
    const form = document.getElementById('form-avaliacao');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validarFormulario()) return;
        
        const dados = coletarDadosFormulario();
        await enviarAvaliacao(dados);
    });
}

/**
 * Valida formulário antes de enviar
 */
function validarFormulario() {
    const requiredRadios = document.querySelectorAll('.pergunta-item input[type="radio"][required]');
    const grupos = new Set();
    
    requiredRadios.forEach(radio => {
        if (radio.name) grupos.add(radio.name);
    });
    
    for (let grupo of grupos) {
        const selecionado = document.querySelector(`input[name="${grupo}"]:checked`);
        if (!selecionado) {
            mostrarMensagem('Por favor, responda todas as perguntas obrigatórias.', 'error');
            const perguntaNaoRespondida = document.querySelector(`input[name="${grupo}"]`)?.closest('.pergunta-item');
            if (perguntaNaoRespondida) {
                perguntaNaoRespondida.scrollIntoView({ behavior: 'smooth', block: 'center' });
                perguntaNaoRespondida.classList.add('highlight-error');
                setTimeout(() => perguntaNaoRespondida.classList.remove('highlight-error'), 2000);
            }
            return false;
        }
    }
    return true;
}

/**
 * Coleta dados do formulário
 */
function coletarDadosFormulario() {
    const dispositivoId = document.getElementById('dispositivo-id')?.value || '';
    const setorId = document.getElementById('setor-id')?.value || '';
    const feedback = document.getElementById('feedback')?.value?.trim() || '';
    const respostas = [];

    // Coleta respostas de escala
    document.querySelectorAll('.escala-option input[type="radio"]:checked').forEach(radio => {
        const perguntaId = radio.dataset.perguntaId;
        const valor = radio.value;
        
        if (perguntaId && valor !== undefined) {
            respostas.push({
                id_pergunta: perguntaId,
                resposta_numerica: parseInt(valor)
            });
        }
    });

    // Coleta respostas de texto
    document.querySelectorAll('textarea[data-pergunta-id]').forEach(textarea => {
        const perguntaId = textarea.dataset.perguntaId;
        const valor = textarea.value?.trim();
        
        if (perguntaId && valor) {
            respostas.push({
                id_pergunta: perguntaId,
                resposta_texto: valor
            });
        }
    });

    return {
        id_dispositivo: dispositivoId,
        id_setor: setorId,
        feedback_textual: feedback,
        respostas
    };
}

/**
 * Envia avaliação para o servidor
 */
async function enviarAvaliacao(dados) {
    const btnEnviar = document.getElementById('btn-enviar');
    if (!btnEnviar) return;
    
    btnEnviar.disabled = true;
    btnEnviar.textContent = 'Enviando...';
    
    try {
        const response = await fetch('submeter_avaliacao.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dados)
        });
        
        const result = await response.json();
        
        if (result.success) {
            mostrarAgradecimento();
        } else {
            throw new Error(result.message || 'Erro ao enviar avaliação');
        }
    } catch (error) {
        console.error('Erro:', error);
        mostrarMensagem('Erro ao enviar avaliação. Por favor, tente novamente.', 'error');
        btnEnviar.disabled = false;
        btnEnviar.textContent = 'Enviar Avaliação';
    }
}

/**
 * Mostra mensagem ao usuário
 */
function mostrarMensagem(texto, tipo = 'info') {
    const mensagem = document.getElementById('mensagem');
    if (!mensagem) return;
    
    mensagem.textContent = String(texto || 'Mensagem');
    mensagem.className = `mensagem ${tipo}`;
    mensagem.style.display = 'block';
    setTimeout(() => mensagem.style.display = 'none', 5000);
}

/**
 * Mostra tela de agradecimento
 */
function mostrarAgradecimento() {
    const formAvaliacao = document.getElementById('form-avaliacao');
    const agradecimento = document.getElementById('agradecimento');
    
    if (formAvaliacao) formAvaliacao.style.display = 'none';
    if (agradecimento) agradecimento.style.display = 'block';
    
    setTimeout(() => location.reload(), 10000);
}