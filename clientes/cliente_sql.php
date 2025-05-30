<?php
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) return false;

    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;

}

$nome     = trim($_POST["txtNome"] ?? '');
$cpf      = trim($_POST['cpf'] ?? '');
$ender    = trim($_POST["ender"] ?? '');
$estado   = trim($_POST["listEstados"] ?? '');
$dtNasc   = trim($_POST["cxcData"] ?? '');
$sexo     = trim($_POST["sexo"] ?? '');
$login    = trim($_POST["cxtLogin"] ?? '');
$senha    = $_POST["cxcSenha"] ?? '';
$senha2   = $_POST["cxcSenha2"] ?? '';

$musica = isset($_POST["checkMusica"]) ? 1 : 0;
$cinema = isset($_POST["checkCinema"]) ? 1 : 0;
$info   = isset($_POST["checkInfo"]) ? 1 : 0;

$erros = [];

// Validações
if ($nome === '') $erros[] = "Informe o NOME.";
if ($cpf === '') $erros[] = "Informe o CPF.";

if ($ender === '') {
    $erros[] = "Informe o ENDEREÇO.";
} else {
    if (strlen($ender) < 5) {
        $erros[] = "Endereço muito curto (mínimo 5 caracteres).";
    }
    if (!preg_match('/^[a-zA-Z0-9\s,.#-]+$/', $ender)) {
        $erros[] = "Endereço contém caracteres inválidos.";
    }
}

if ($dtNasc === '') $erros[] = "Informe a DATA DE NASCIMENTO.";
if ($login === '') $erros[] = "Informe o LOGIN.";
if ($senha === '' || $senha2 === '') {
    $erros[] = "Informe a SENHA e a CONFIRMAÇÃO.";
} elseif ($senha !== $senha2) {
    $erros[] = "As senhas não conferem.";
}

if (!empty($cpf)) {
    if (!validarCPF($cpf)) {
        $erros[] = "CPF inválido!";
    }
}

if (!empty($dtNasc)) {
    if (strlen($dtNasc) == 10) {
        list($ano, $mes, $dia) = explode('-', $dtNasc);
        if (!checkdate((int)$mes, (int)$dia, (int)$ano)) {
            $erros[] = "DATA inválida.";
        } else {
            $dataInformada = strtotime($dtNasc);
            $dataAtual = strtotime(date('Y-m-d'));
            if ($dataInformada > $dataAtual) {
                $erros[] = "Data de nascimento não pode ser futura.";
            }
        }
    } else {
        $erros[] = "DATA inválida.";
    }
}

if (!empty($erros)) {
    echo "<script>alert('" . implode("\\n", $erros) . "'); history.back();</script>";
    exit();
}


// Preparar dados para banco
$cpf = preg_replace('/[^0-9]/', '', $cpf);
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$conn = new mysqli("127.0.0.1", "root", "root", "lanchecleber");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica login duplicado
$sqlCheckLogin = "SELECT id FROM clientes WHERE login = ?";
$stmtCheckLogin = $conn->prepare($sqlCheckLogin);
$stmtCheckLogin->bind_param("s", $login);
$stmtCheckLogin->execute();
$stmtCheckLogin->store_result();

if ($stmtCheckLogin->num_rows > 0) {
    echo "Erro: O LOGIN já está em uso! <br>";
    $stmtCheckLogin->close();
    $conn->close();
    exit();
}
$stmtCheckLogin->close();

// Inserção no banco
$stmt = $conn->prepare(
    "INSERT INTO clientes 
    (nome, cpf, endereco, estado, data_nasc, sexo, cinema, musica, informatica, login, senha) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
    "ssssssiiiss",
    $nome,
    $cpf,
    $ender,
    $estado,
    $dtNasc,
    $sexo,
    $cinema,
    $musica,
    $info,
    $login,
    $senhaHash
);

if ($stmt->execute()) {
    header("Location: /lista_clientes.php");
    exit();
} else {
    echo "Erro ao inserir no banco de dados: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
