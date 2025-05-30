<?php
// Inicializar variáveis de pesquisa
$descricao = $idProduto = $dataValidade = "";
$resultados = [];
$pesquisaRealizada = false;
$totalEmEstoque = 0;
$totalForaEstoque = 0;
$quantidadeTotal = 0;

// Processar formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter e limpar os dados do formulário
    $descricao = trim(htmlspecialchars($_POST["descricao"] ?? ''));
    $idProduto = filter_var($_POST["idProduto"] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $dataValidade = $_POST["dataValidade"] ?? '';
    
    // Construir a consulta SQL base
    $sql = "SELECT * FROM produtos WHERE 1=1";
    $params = [];
    $types = "";
    
    // Adicionar filtros conforme preenchidos
    if (!empty($descricao)) {
        $sql .= " AND descricao LIKE ?";
        $descricaoBusca = "%$descricao%";
        $params[] = &$descricaoBusca;
        $types .= "s";
    }
    
    if (!empty($idProduto)) {
        $sql .= " AND idProduto = ?";
        $params[] = &$idProduto;
        $types .= "i";
    }
    
    if (!empty($dataValidade)) {
        $sql .= " AND dataValidade = ?";
        $params[] = &$dataValidade;
        $types .= "s";
    }
    
    // Ordenar por ID
    $sql .= " ORDER BY idProduto";
    
    // Preparar e executar a consulta
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Processar resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
            
            // Contabilizar estatísticas
            if ($row['qtdeEstoque'] > 0) {
                $totalEmEstoque++;
            } else {
                $totalForaEstoque++;
            }
            $quantidadeTotal += $row['qtdeEstoque'];
        }
    }
    
    $pesquisaRealizada = true;
    $stmt->close();
}
?>
<?php

include_once '../config.php'; 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa de Produtos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/pesquisar_produto.css" />

</head>
<body class="light-theme">
    <main class="container">
        <?php include_once(__DIR__ . '/../header.php');
 ?>

        <h1>Pesquisa de Produtos</h1>
        
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="search-form">
    <div class="form-row">
        <div class="form-group">
            <label for="idProduto">Código:</label>
            <input type="number" id="idProduto" name="idProduto" value="<?php echo $idProduto; ?>">
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" value="<?php echo $descricao; ?>">
        </div>

        <div class="form-group">
            <label for="dataValidade">Data de Validade:</label>
            <input type="date" id="dataValidade" name="dataValidade" value="<?php echo $dataValidade; ?>">
        </div>
    </div>

    <div class="form-group buttons">
        <button type="submit" class="btn-pesquisar">Pesquisar</button>
        <button type="reset" class="btn-limpar" onclick="window.location.href='pesquisar_produtos.php'">Limpar</button>
    </div>
</form>

        
        <?php if ($pesquisaRealizada): ?>
            <div class="resultados">
                <div class="estatisticas">
                    <div class="estatistica">
                        <span class="label">Em Estoque:</span>
                        <span class="valor"><?php echo $totalEmEstoque; ?> produtos</span>
                    </div>
                    <div class="estatistica">
                        <span class="label">Fora de Estoque:</span>
                        <span class="valor"><?php echo $totalForaEstoque; ?> produtos</span>
                    </div>
                    <div class="estatistica">
                        <span class="label">Quantidade Total:</span>
                        <span class="valor"><?php echo $quantidadeTotal; ?> unidades</span>
                    </div>
                </div>
                
                <?php if (count($resultados) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Status</th>
                                <th>Validade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $produto): ?>
                                <tr class="<?php echo $produto['qtdeEstoque'] > 0 ? 'em-estoque' : 'fora-estoque'; ?>">
                                    <td><?php echo $produto['idProduto']; ?></td>
                                    <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                                    <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                    <td><?php echo $produto['qtdeEstoque']; ?></td>
                                    <td>
                                        <?php if ($produto['qtdeEstoque'] > 0): ?>
                                            <span class="status em-estoque">Em Estoque</span>
                                        <?php else: ?>
                                            <span class="status fora-estoque">Fora de Estoque</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($produto['dataValidade'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="sem-resultados">Nenhum produto encontrado com os critérios informados.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Script para alternar entre temas claro e escuro
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
