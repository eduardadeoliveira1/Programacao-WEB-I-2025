<?php
/**
 * Formulário de Avaliação - Página Principal
 * Sistema de Avaliação de Qualidade
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/config.php';

// Identificador do dispositivo (pode ser passado via GET ou configurado)
$dispositivoId = $_GET['dispositivo'] ?? 1;
$dispositivo = getDispositivoPorIdentificador($dispositivoId);

if (!$dispositivo) {
    die('Dispositivo não configurado. Entre em contato com o administrador.');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Avaliação</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header class="header">
  <img src="img/logo_pousada.png" alt="Logo da Pousada" class="logo">
  <h1> Avaliação da Sua Estadia</h1>
  <p>Queremos saber como foi sua experiência na <strong>Pousada do Sol</strong>.<br>
  Suas respostas nos ajudam a oferecer momentos ainda mais especiais!</p>
</header>



        <main class="main-content">
            <!-- Formulário de Avaliação -->
            <form id="form-avaliacao" class="form-avaliacao">
                <input type="hidden" id="dispositivo-id" value="<?php echo htmlspecialchars($dispositivo['id_dispositivo']); ?>">
                <input type="hidden" id="setor-id" value="<?php echo htmlspecialchars($dispositivo['id_setor']); ?>">
                
                <!-- Perguntas serão carregadas dinamicamente aqui -->
                <div id="perguntas-container" class="perguntas-container">
                    <div class="loading">Carregando perguntas...</div>
                </div>

                <!-- Campo de feedback opcional -->
                <div class="feedback-section">
                    <label for="feedback">
                        <strong>Comentários ou Sugestões (Opcional)</strong>
                    </label>
                    <textarea 
                        id="feedback" 
                        name="feedback" 
                        rows="4" 
                        maxlength="<?php echo MAX_FEEDBACK_LENGTH; ?>"
                        placeholder="Deixe aqui seus comentários, sugestões ou elogios..."></textarea>
                    <small class="char-count">0/<?php echo MAX_FEEDBACK_LENGTH; ?> caracteres</small>
                </div>

                <!-- Botão de envio -->
                <div class="button-container">
                    <button type="submit" class="btn-submit" id="btn-enviar">
                        Enviar Avaliação
                    </button>
                </div>

                <!-- Mensagens de erro/sucesso -->
                <div id="mensagem" class="mensagem" style="display: none;"></div>
            </form>

            <!-- Mensagem de agradecimento (oculta inicialmente) -->
            <div id="agradecimento" class="agradecimento" style="display: none;">
                <div class="success-icon">✓</div>
                <h2>Avaliação Enviada!</h2>
                <p><?php echo THANK_YOU_TEXT; ?></p>
                <button onclick="location.reload()" class="btn-nova-avaliacao">
                    Nova Avaliação
                </button>
            </div>
        </main>

        <footer class="footer">
            <p class="privacy-text">
                <svg class="icon-lock" viewBox="0 0 24 24" width="16" height="16">
                    <path d="M12 1C8.676 1 6 3.676 6 7v2H5c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V11c0-1.1-.9-2-2-2h-1V7c0-3.324-2.676-6-6-6zm0 2c2.276 0 4 1.724 4 4v2H8V7c0-2.276 1.724-4 4-4z"/>
                </svg>
                <?php echo PRIVACY_TEXT; ?>
            </p>
        </footer>
    </div>

    <script src="js/avaliacao.js"></script>
</body>
</html>