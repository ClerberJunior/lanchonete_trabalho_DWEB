<?php require_once __DIR__ . '/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Lanches</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/menu.css">
</head>
<body>
    <header class="topbar">
        <nav>
            <ul>
                <li><a href="<?= BASE_URL ?>/index.php">Cadastrar Cliente</a></li>
                <li><a href="<?= BASE_URL ?>/clientes/lista_clientes.php">Listar Clientes</a></li>
                <li><a href="<?= BASE_URL ?>/produtos/cadastro_produto.php">Cadastrar Produto</a></li>
                <li><a href="<?= BASE_URL ?>/produtos/pesquisar_produto.php">Pesquisar Produtos</a></li>
            </ul>
            <button id="toggle-theme">🌗 Mudar</button>
        </nav>
    </header>
    
    <script> 
    const toggleBtn = document.getElementById('toggle-theme');

    toggleBtn.addEventListener('click', () => {
    if(document.body.classList.contains('dark-theme')) {
    document.body.classList.replace('dark-theme', 'light-theme');
    } else {
    document.body.classList.replace('light-theme', 'dark-theme');
    }
    });
    </script>