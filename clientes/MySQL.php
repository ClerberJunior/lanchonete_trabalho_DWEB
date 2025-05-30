<?php
// MySQL.php

// Definir constantes para a conexão
define('DB_SERVER', '127.0.0.1');    // Endereço do servidor de banco de dados
define('DB_USERNAME', 'root');       // Nome de usuário do banco de dados
define('DB_PASSWORD', 'root');           // Senha do banco de dados
define('DB_NAME', 'lanchecleber');      // Nome do banco de dados

// Criar a conexão
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Configuração de charset para garantir que o banco lide com caracteres especiais corretamente
$conn->set_charset("utf8");

// Retorna a conexão para ser usada em outros arquivos PHP
return $conn;
?>
