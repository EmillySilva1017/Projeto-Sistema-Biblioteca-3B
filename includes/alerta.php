<?php
// Verifica se existe alguma mensagem guardada na sessão
if (isset($_SESSION['msg']) || isset($_SESSION['mensagem'])): 

    // Padroniza o texto e o tipo do alerta
    $texto_mensagem = isset($_SESSION['msg']) ? $_SESSION['msg'] : $_SESSION['mensagem'];
    $tipo_alerta = isset($_SESSION['msg_tipo']) ? $_SESSION['msg_tipo'] : 'info';

    // Associa o ícone correspondente do Bootstrap Icons
    $icone = 'bi-info-circle-fill';
    if ($tipo_alerta === 'success') $icone = 'bi-check-circle-fill';
    if ($tipo_alerta === 'danger')  $icone = 'bi-exclamation-triangle-fill';
    if ($tipo_alerta === 'warning') $icone = 'bi-exclamation-circle-fill';
?>

<div class="alert alert-<?= $tipo_alerta; ?> alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" 
     role="alert" 
     style="border-radius: 12px; font-size: 0.9rem;">
    <i class="bi <?= $icone; ?> fs-5"></i>
    <div>
        <?= $texto_mensagem; ?>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<?php 
    // Limpa as variáveis da sessão para a mensagem não se repetir ao recarregar a página
    unset($_SESSION['msg']);
    unset($_SESSION['mensagem']);
    unset($_SESSION['msg_tipo']);
endif; 
?>