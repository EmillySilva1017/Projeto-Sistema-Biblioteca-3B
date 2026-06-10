<?php
// Certifica que nenhuma saída HTML aconteça antes do JSON
header('Content-Type: application/json; charset=utf-8');

include('../includes/conexao.php');
/** @var mysqli $conn */

$itens_por_pagina = 5; 
$pagina = isset($_GET['pag_atrasos']) ? intval($_GET['pag_atrasos']) : 1;
if ($pagina < 1) $pagina = 1;
$offset = ($pagina - 1) * $itens_por_pagina;

// 1. QUERY DE CONTAGEM TOTAL
$sql_total_turmas = "SELECT COUNT(DISTINCT e.fk_id_turma) as total 
                     FROM emprestimos e 
                     WHERE e.status = 'Atrasado'";
$res_total = mysqli_query($conn, $sql_total_turmas);
$total_registros = 0;
if ($res_total) {
    $total_registros = mysqli_fetch_assoc($res_total)['total'];
}
$total_paginas = ceil($total_registros / $itens_por_pagina);

// 2. QUERY DA PÁGINA ATUAL
$sql_contagem_atrasos = "SELECT 
                            CONCAT(t.serie_atual, 'º ', t.identificador_curso, ' - ', t.curso) AS nome_turma,
                            COUNT(e.id_emprestimos) AS qtd_atrasados
                         FROM emprestimos e
                         INNER JOIN turmas t ON e.fk_id_turma = t.id_turma
                         WHERE e.status = 'Atrasado'
                         GROUP BY e.fk_id_turma
                         ORDER BY qtd_atrasados DESC
                         LIMIT $itens_por_pagina OFFSET $offset";

$res_dados = mysqli_query($conn, $sql_contagem_atrasos);
$html_linhas = '';

if (!$res_dados || mysqli_num_rows($res_dados) == 0) {
    $html_linhas .= '<tr><td colspan="2" class="text-center text-muted py-3"><i class="bi bi-emoji-smile text-success"></i> Nenhuma turma com livros atrasados!</td></tr>';
} else {
    while ($row = mysqli_fetch_assoc($res_dados)) {
        $html_linhas .= "<tr>
            <td class='fw-bold text-dark text-center'>{$row['nome_turma']}</td>
            <td class='text-center fw-bold'>
                <span class='badge bg-danger-subtle text-danger px-3 py-2 rounded-pill'>
                    {$row['qtd_atrasados']} aluno(s)
                </span>
            </td>
         </tr>";
    }
}

echo json_encode([
    'linhas' => $html_linhas,
    'pagina_atual' => $pagina,
    'total_paginas' => $total_paginas
], JSON_UNESCAPED_UNICODE);
exit;