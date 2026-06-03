<?php
session_start();
ob_start();

include('../includes/conexao.php');
/** @var mysqli $conn */ 

if (!isset($_SESSION['id_user'])) {
    die("Erro: Utilizador não autenticado. Por favor, faça login no sistema.");
}

date_default_timezone_set('America/Fortaleza');
$hoje_formatado = date('d/m/Y');

// Inclui o ficheiro principal da biblioteca FPDF
require('../includes/fpdf/fpdf.php');

function converterTexto($texto) {
    if (empty($texto)) return '';
    
    // Tenta converter usando ISO-8859-1 ignorando caracteres inválidos
    $resultado = iconv('UTF-8', 'ISO-8859-1//IGNORE', $texto);
    
    // Se o iconv falhar e retornar falso, usa o método alternativo seguro
    if ($resultado === false) {
        $resultado = mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
    }
    
    return $resultado;
}

// 3. CRIAÇÃO DA ESTRUTURA PERSONALIZADA DO PDF (CABEÇALHO E RODAPÉ)

// Herdamos a classe FPDF para poder customizar as funções Header() e Footer() que rodam em cada página
class PDF extends FPDF {
    
    // Esta função é executada automaticamente pelo FPDF sempre que uma nova página é criada
    function Header() {
        // Caminho para a logo da escola
        $logo = '../img/LogoEscola.png';
        // Verifica se a imagem realmente existe antes de tentar desenhar, evitando que o script quebre
        if (file_exists($logo)) {
            $largura_logo = 25;
            $posicao_logo = (210 - $largura_logo) / 2; // Centraliza a logo horizontalmente
            $this->Image($logo, $posicao_logo, 8, $largura_logo);
            
            $this->Ln(28);
        } else {
            // Se a imagem não existir por algum motivo, dá apenas um pequeno espaço inicial
            $this->Ln(5);
        }

        // Título Principal do Relatório
        $this->SetFont('Arial', 'B', 14); // Define fonte Arial, Negrito (Bold), tamanho 14
        $this->Cell(0, 10, converterTexto('MANOTECA - SISTEMA DE GESTÃO DE BIBLIOTECA'), 0, 1, 'C'); // Margem total, altura 10, centralizado

        // Subtítulo do Relatório
        $this->SetFont('Arial', '', 11); // Muda para estilo normal, tamanho 11
        $this->Cell(0, 6, converterTexto('Relatório de Alunos com Empréstimos em Atraso'), 0, 1, 'C');
        
        // Espaço em branco de 10mm antes de começar a tabela
        $this->Ln(8);
        
        // Configuração visual do cabeçalho da tabela de dados
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(22, 120, 30); // Verde Bootstrap
        $this->SetTextColor(255, 255, 255); // Texto Branco
        $this->SetLineWidth(0.3);
        
        // Parâmetros do Cell: (Largura, Altura, Texto, Borda[1=sim], PróximaLinha[0=não, 1=sim], Alinhamento, Preenchimento[true=usa cor de fundo])
        $this->Cell(55, 8, converterTexto(' Aluno'), 1, 0, 'L', true);
        $this->Cell(20, 8, converterTexto('Turma'), 1, 0, 'C', true);
        $this->Cell(65, 8, converterTexto(' Livro'), 1, 0, 'L', true);
        $this->Cell(25, 8, converterTexto('Data Saída'), 1, 0, 'C', true);
        $this->Cell(25, 8, converterTexto('Previsão'), 1, 1, 'C', true);
    }

    // Esta função é executada automaticamente sempre que o PDF chega perto do fim da página
    function Footer() {
        // Define a posição do rodapé para 15 milímetros antes do fim da folha
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8); // Fonte Arial, Itálico (Italic), tamanho 8
        $this->SetTextColor(128, 128, 128); // Cor cinzenta para o número da página
        
        // Imprime o número da página atual. O '{nb}' será substituído dinamicamente pelo total de páginas
        $this->Cell(0, 10, converterTexto('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// 4. INICIALIZAÇÃO E CONFIGURAÇÃO DO DOCUMENTO PDF

// Cria a instância do PDF: 'P' = Retrato (Portrait), 'mm' = Unidade em milímetros, 'A4' = Tamanho do papel
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// 5. CONSULTA À BASE DE DADOS

$sql = "SELECT e.nome_aluno, CONCAT(t.serie_atual, 'º ', t.identificador_curso) AS nome_turma, l.titulo_livro, e.data_saida, e.data_prevista 
        FROM emprestimos e
        INNER JOIN turmas t ON e.fk_id_turma = t.id_turma
        INNER JOIN livros l ON e.fk_id_livro = l.id
        WHERE e.status = 'Atrasado'
        ORDER BY nome_turma ASC, e.nome_aluno ASC";

$resultado = mysqli_query($conn, $sql);

// 6. RENDERIZAÇÃO DOS DADOS NO PDF

// Volta a cor do texto para Preto e remove o negrito para listar as linhas do relatório
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);

// Variável booleana utilizada para alternar a cor do fundo das linhas
$zebra = false;

// Verifica se a consulta retornou pelo menos 1 aluno em atraso
if (mysqli_num_rows($resultado) > 0) {
    
    // Percorre cada registo retornado da base de dados
    while ($row = mysqli_fetch_assoc($resultado)) {
        
        // Se a variável $zebra for verdadeira, pinta a linha de cinzento claro, caso contrário deixa branca
        if ($zebra) {
            $pdf->SetFillColor(245, 245, 245); // Cinzento bem suave
        } else {
            $pdf->SetFillColor(255, 255, 255); // Branco completo
        }
        
        // Formata as datas com segurança
        $dt_saida = (!empty($row['data_saida'])) ? date('d/m/Y', strtotime($row['data_saida'])) : '--/--/----';
        $dt_prevista = (!empty($row['data_prevista'])) ? date('d/m/Y', strtotime($row['data_prevista'])) : '--/--/----';

        // Captura e limpa os valores textuais
        $aluno_bruto = !empty($row['nome_aluno']) ? $row['nome_aluno'] : 'Não informado';
        $turma_bruta = !empty($row['nome_turma']) ? $row['nome_turma'] : '-';
        $livro_bruto = !empty($row['titulo_livro']) ? $row['titulo_livro'] : 'Não informado';

        // Truncagem de tamanho de string para evitar estouro de tabela
        $nome_aluno = mb_strlen($aluno_bruto, 'UTF-8') > 30 ? mb_substr($aluno_bruto, 0, 27, 'UTF-8') . '...' : $aluno_bruto;
        $titulo_livro = mb_strlen($livro_bruto, 'UTF-8') > 35 ? mb_substr($livro_bruto, 0, 32, 'UTF-8') . '...' : $livro_bruto;

        // Renderiza as células no PDF
        $pdf->Cell(55, 8, converterTexto(' ' . $nome_aluno), 1, 0, 'L', true);
        $pdf->Cell(20, 8, converterTexto($turma_bruta), 1, 0, 'C', true);
        $pdf->Cell(65, 8, converterTexto(' ' . $titulo_livro), 1, 0, 'L', true);
        $pdf->Cell(25, 8, $dt_saida, 1, 0, 'C', true);
        $pdf->Cell(25, 8, $dt_prevista, 1, 1, 'C', true);

        $zebra = !$zebra;
    }
} else {
    // Caso não exista nenhum aluno em atraso na base de dados, exibe uma linha informativa com aviso
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, converterTexto('Nenhum empréstimo em atraso encontrado no momento.'), 1, 1, 'C');
}

// 7. FINALIZAÇÃO DO DOCUMENTO

$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 5, converterTexto('Relatório gerado em: ' . $hoje_formatado), 0, 1, 'R'); // Alinhado à direita ('R')

ob_end_clean();
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="relatorio_atrasos.pdf"');

$pdf->Output('I', 'relatorio_atrasos_' . date('Ymd') . '.pdf');
exit;