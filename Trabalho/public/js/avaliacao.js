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
        
        data.perguntas.forEach((pergunta, index) => {
            const perguntaHTML = criarPerguntaHTML(pergunta, index);
            container.insertAdjacentHTML('beforeend', perguntaHTML);
        });
        
        // Adicionar eventos aos inputs de escala
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
    const tipoResposta = pergunta.tipo_resposta;
    const obrigatoria = pergunta.obrigatoria === 't' || pergunta.obrigatoria === true;
    
    if (tipoResposta === 'escala_0_10') {
        return criarEscalaHTML(pergunta, index, 10, obrigatoria);
    } else if (tipoResposta === 'escala_0_5') {
        return criarEscalaHTML(pergunta, index, 5, obrigatoria);
    } else if (tipoResposta === 'texto') {
        return criarTextoHTML(pergunta, index, obrigatoria);
    }
}

/**
 * Cria HTML para pergunta tipo escala
 * @param {Object} pergunta Dados da pergunta
 * @param {number} index Índice
 * @param {number} max Valor máximo da escala
 * @param {boolean} obrigatoria Se é obrigatória
 * @returns {string} HTML
 */
function criarEscalaHTML(pergunta, index, max, obrigatoria) {
    const required = obrigatoria ? 'required' : '';
    const asterisco = obrigatoria ? '<span class="required">*</span>' : '';
    
    let opcoesHTML = '';
    for (let i = 0; i <= max; i++) {
        opcoesHTML += `
            <label class="escala-option">
                <input 
                    type="radio" 
                    name="pergunta_${pergunta.id_pergunta}" 
                    value="${i}"
                    data-pergunta-id="${pergunta.id_pergunta}"
                    ${required}>
                <span class="escala-value">${i}</span>
            </label>
        `;
    }
    
    return `
        <div class="pergunta-item" data-pergunta-id="${pergunta.id_pergunta}">
            <div class="pergunta-texto">
                ${index + 1}. ${pergunta.texto_pergunta} ${asterisco}
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
 * @param {Object} pergunta Dados da pergunta
 * @param {number} index Índice
 * @param {boolean} obrigatoria Se é obrigatória
 * @returns {string} HTML
 */
function criarTextoHTML(pergunta, index, obrigatoria) {
    const required = obrigatoria ? 'required' : '';
    const asterisco = obrigatoria ? '<span class="required">*</span>' : '';
    
    return `
        <div class="pergunta-item" data-pergunta-id="${pergunta.id_pergunta}">
            <div class="pergunta-texto">
                ${index + 1}. ${pergunta.texto_pergunta} ${asterisco}
            </div>
            <textarea 
                name="pergunta_texto_${pergunta.id_pergunta}"
                data-pergunta-id="${pergunta.id_pergunta}"
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
            // Remove seleção anterior do mesmo grupo
            const name = this.name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.parentElement.classList.remove('selected');
            });
            
            // Adiciona classe ao selecionado
            this.parentElement.classList.add('selected');
            
            // Animação de feedback
            this.parentElement.classList.add('pulse');
            setTimeout(() => {
                this.parentElement.classList.remove('pulse');
            }, 300);
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
            const max = this.maxLength;
            counter.textContent = `${length}/${max} caracteres`;
            
            if (length > max * 0.9) {
                counter.classList.add('warning');
            } else {
                counter.classList.remove('warning');
            }
        });
    }
}

/**
 * Configura envio do formulário
 */
function configurarFormulario() {
    const form = document.getElementById('form-avaliacao');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validarFormulario()) {
            return;
        }
        
        const dados = coletarDadosFormulario();
        await enviarAvaliacao(dados);
    });
}

/**
 * Valida formulário antes de enviar
 * @returns {boolean} True se válido
 */
function validarFormulario() {
    const requiredRadios = document.querySelectorAll('.pergunta-item input[type="radio"][required]');
    const grupos = new Set();
    
    requiredRadios.forEach(radio => {
        grupos.add(radio.name);
    });
    
    for (let grupo of grupos) {
        const selecionado = document.querySelector(`input[name="${grupo}"]:checked`);
        if (!selecionado) {
            mostrarMensagem('Por favor, responda todas as perguntas obrigatórias.', 'error');
            
            // Scroll até a primeira pergunta não respondida
            const perguntaNaoRespondida = document.querySelector(`input[name="${grupo}"]`).closest('.pergunta-item');
            perguntaNaoRespondida.scrollIntoView({ behavior: 'smooth', block: 'center' });
            perguntaNaoRespondida.classList.add('highlight-error');
            
            setTimeout(() => {
                perguntaNaoRespondida.classList.remove('highlight-error');
            }, 2000);
            
            return false;
        }
    }
    
    return true;
}

/**
 * Coleta dados do formulário
 * @returns {Object} Dados formatados
 */
function coletarDadosFormulario() {
    const dispositivoId = document.getElementById('dispositivo-id').value;
    const setorId = document.getElementById('setor-id').value;
    const feedback = document.getElementById('feedback').value;
    
    const respostas = [];
    
    // Coletar respostas de escala
    const radiosChecked = document.querySelectorAll('.escala-option input[type="radio"]:checked');
    radiosChecked.forEach(radio => {
        respostas.push({
            id_pergunta: radio.dataset.perguntaId,
            resposta_numerica: parseInt(radio.value)
        });
    });
    
    // Coletar respostas de texto
    const textareas = document.querySelectorAll('textarea[data-pergunta-id]');
    textareas.forEach(textarea => {
        if (textarea.value.trim()) {
            respostas.push({
                id_pergunta: textarea.dataset.perguntaId,
                resposta_texto: textarea.value.trim()
            });
        }
    });
    
    return {
        id_dispositivo: dispositivoId,
        id_setor: setorId,
        feedback_textual: feedback,
        respostas: respostas
    };
}

/**
 * Envia avaliação para o servidor
 * @param {Object} dados Dados da avaliação
 */
async function enviarAvaliacao(dados) {
    const btnEnviar = document.getElementById('btn-enviar');
    btnEnviar.disabled = true;
    btnEnviar.textContent = 'Enviando...';
    
    try {
        const response = await fetch('submeter_avaliacao.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
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
 * @param {string} texto Texto da mensagem
 * @param {string} tipo Tipo (success, error, info)
 */
function mostrarMensagem(texto, tipo = 'info') {
    const mensagem = document.getElementById('mensagem');
    mensagem.textContent = texto;
    mensagem.className = `mensagem ${tipo}`;
    mensagem.style.display = 'block';
    
    setTimeout(() => {
        mensagem.style.display = 'none';
    }, 5000);
}

/**
 * Mostra tela de agradecimento
 */
function mostrarAgradecimento() {
    document.getElementById('form-avaliacao').style.display = 'none';
    document.getElementById('agradecimento').style.display = 'block';
    
    // Reiniciar após 10 segundos
    setTimeout(() => {
        location.reload();
    }, 10000);
}