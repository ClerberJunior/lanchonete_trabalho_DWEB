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

// Exclusão lógica (soft delete)
$sql = "UPDATE clientes SET ativo = 0 WHERE cpf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cpf);

if ($stmt->execute()) {
    header("Location: ../lista_clientes.php?msg=Cliente excluído com sucesso!");
} else {
    echo "Erro ao excluir: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
