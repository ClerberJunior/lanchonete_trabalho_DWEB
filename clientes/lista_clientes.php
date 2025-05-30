<?php
$conn = new mysqli("127.0.0.1", "root", "root", "lanchecleber");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT c.nome, c.cpf, c.endereco, e.nome AS estado, c.data_nasc, c.sexo, c.login 
        FROM clientes c
        JOIN estados e ON c.estado = e.sigla
        WHERE c.ativo = 1";  // só mostra clientes ativos

$result = $conn->query($sql);
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>
<?php

include_once '../config.php'; 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/lista.css" />

</head>
<body class="light-theme">
    <?php
    if (isset($_GET['msg'])) {
        echo "<p style='color:green;'>" . htmlspecialchars($_GET['msg']) . "</p>";
    }
    ?>
    <main class="container">
        <?php include_once(__DIR__ . '/../header.php');
 ?>

        <h1>Clientes Cadastrados</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Endereço</th>
                        <th>Estado</th>
                        <th>Data de Nasc.</th>
                        <th>Sexo</th>
                        <th>Login</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['cpf']) ?></td>
                            <td><?= htmlspecialchars($row['endereco']) ?></td>
                            <td><?= htmlspecialchars($row['estado']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['data_nasc'])) ?></td>
                            <td>
                                <?php 
                                    if ($row['sexo'] == 'M') {
                                        echo 'Masculino';
                                    } elseif ($row['sexo'] == 'F') {
                                        echo 'Feminino';
                                    } else {
                                        echo 'Outro';
                                    }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($row['login']) ?></td>
                            <td>
                                <a href="editar_cliente.php?cpf=<?= urlencode($row['cpf']) ?>" class="btn editar">Editar</a>
                                <a href="cliente\excluir_cliente.php?cpf=<?= urlencode($row['cpf']) ?>" class="btn excluir"
                                onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum cliente cadastrado.</p>
        <?php endif; ?>

    </main>

<script>
  const toggleBtn = document.getElementById('toggle-theme');
  const body = document.body;

  window.onload = () => {
    const savedTheme = localStorage.getItem('theme') || 'light-theme';
    body.classList.add(savedTheme);
  }

  toggleBtn.addEventListener('click', () => {
    if (body.classList.contains('dark-theme')) {
      body.classList.replace('dark-theme', 'light-theme');
      localStorage.setItem('theme', 'light-theme');
    } else {
      body.classList.replace('light-theme', 'dark-theme');
      localStorage.setItem('theme', 'dark-theme');
    }
  });
</script>

</body>
</html>

<?php
$conn->close();
?>
