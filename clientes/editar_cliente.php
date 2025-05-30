<?php
if (!isset($_GET['cpf'])) {
    die("CPF não informado.");
}

$cpf = $_GET['cpf'];

// Conexão com o banco
$conn = new mysqli("127.0.0.1", "root", "root", "lanchecleber");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar dados do cliente
$sql = "SELECT * FROM clientes WHERE cpf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cpf);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Cliente não encontrado.");
}

$cliente = $result->fetch_assoc();
$stmt->close();

// Atualização
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $endereco = $_POST["endereco"];
    $estado = $_POST["estado"];
    $data_nasc = $_POST["data_nasc"];
    $sexo = $_POST["sexo"];
    $login = $_POST["login"];

    $sqlUpdate = "UPDATE clientes SET nome=?, endereco=?, estado=?, data_nasc=?, sexo=?, login=? WHERE cpf=?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("sssssss", $nome, $endereco, $estado, $data_nasc, $sexo, $login, $cpf);

    if ($stmt->execute()) {
        header("Location: lista_clientes.php?msg=Cliente atualizado com sucesso!");
        exit();
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="css/edicao.css">
</head>
<body class="light-theme">

    <form method="POST">
        <h1>Editar Cliente</h1>

        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($cliente['nome']) ?>" required>

        <label>Endereço:</label>
        <input type="text" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>" required>

        <label>Estado:</label>
        <select name="estado" required>
            <?php
            // Buscar estados
            $conn = new mysqli("127.0.0.1", "root", "root", "lanchonete");
            $estados = $conn->query("SELECT sigla, nome FROM estados");
            while ($estado = $estados->fetch_assoc()):
                $selected = $estado['sigla'] === $cliente['estado'] ? "selected" : "";
                echo "<option value='{$estado['sigla']}' $selected>{$estado['nome']}</option>";
            endwhile;
            $conn->close();
            ?>
        </select>

        <label>Data de Nascimento:</label>
        <input type="date" name="data_nasc" value="<?= $cliente['data_nasc'] ?>" required>

        <label>Sexo:</label>
        <select name="sexo" required>
            <option value="M" <?= $cliente['sexo'] == 'M' ? 'selected' : '' ?>>Masculino</option>
            <option value="F" <?= $cliente['sexo'] == 'F' ? 'selected' : '' ?>>Feminino</option>
            <option value="O" <?= $cliente['sexo'] == 'O' ? 'selected' : '' ?>>Outro</option>
        </select>

        <label>Login:</label>
        <input type="text" name="login" value="<?= htmlspecialchars($cliente['login']) ?>" required>

        <input type="submit" value="Salvar">
        <input type="reset" value="Cancelar" onclick="window.location.href='lista_clientes.php'; return false;">
    </form>

</body>
</html>
