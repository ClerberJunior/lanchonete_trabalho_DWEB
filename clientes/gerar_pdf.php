<?php
require_once('tcpdf/tcpdf.php');

// Conexão com o banco
$conn = new mysqli("127.0.0.1", "root", "root", "lanchecleber");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se vieram IDs
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID do cliente não registrado.");
}

$conn = new mysqli("localhost", "root", "root", "ProgWebBD");
$result = $conn->query("SELECT * FROM Clientes WHERE id = $id");
$cliente =  $result->fetch_assoc();
if (!$cliente) {
    die("Cliente inexistente.");
}

// Criando o PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Cadastro');
$pdf->SetTitle('Cadastros Selecionados');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

$html = "<h2>Cadastros Selecionados</h2>";

while ($row = $result->fetch_assoc()) {
    $musica = $row['musica'] ? 'Sim' : 'Não';
    $cinema = $row['cinema'] ? 'Sim' : 'Não';
    $informatica = $row['informatica'] ? 'Sim' : 'Não';

    $html .= <<<HTML
    <table border="1" cellpadding="5" cellspacing="0">
    <tr><td><b>Nome:</b></td><td>{$row['nome']}</td></tr>
    <tr><td><b>CPF:</b></td><td>{$row['cpf']}</td></tr>
    <tr><td><b>Endereço:</b></td><td>{$row['endereco']}</td></tr>
    <tr><td><b>Estado:</b></td><td>{$row['estado']}</td></tr>
    <tr><td><b>Data de Nascimento:</b></td><td>{$row['dtnasc']}</td></tr>
    <tr><td><b>Sexo:</b></td><td>{$row['sexo']}</td></tr>
    <tr><td><b>Login:</b></td><td>{$row['login']}</td></tr>
    <tr><td><b>Senha:</b></td><td>{$row['senha']}</td></tr>
    <tr><td><b>Interesses:</b></td>
    <td>
        Música: {$musica} <br>
        Cinema: {$cinema} <br>
        Informática: {$informatica}
    </td>
    </tr>
    </table><br><br>
HTML;
}

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('cadastros_selecionados.pdf', 'I');

header("Location: listar_clientes.php");
exit();

?>
