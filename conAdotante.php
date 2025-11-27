<?php

// Configurações de exibição de erro
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão existente
require_once 'conexao.php';
// Define o conjunto de caracteres da conexão para UTF-8
$con->set_charset("utf8");

// Decodifica a entrada JSON (mantido do original, mas ignorado, já que é um GET/SELECT)
json_decode(file_get_contents('php://input'), true);

$sql = "SELECT idAdotante, nrTelefone, nmAdotante, deEndereco, flListaNegra, cdOngCadastro FROM adotante";

$result = $con->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    // Itera sobre os resultados e adiciona ao array de resposta
    while ($row = $result->fetch_assoc()) {
        $response[] = $row; // Usa como está (o charset já está definido)
    }
} else {
    // Caso não haja resultados, retorna uma estrutura vazia correspondente à tabela 'adotante'
    $response[] = [
        "idAdotante" => 0,
        "nrTelefone" => "",
        "nmAdotante" => "",
        "deEndereco" => "",
        "flListaNegra" => "", // char(1)
        "cdOngCadastro" => 0
    ];
}

// Define o cabeçalho como JSON com charset UTF-8
header('Content-Type: application/json; charset=utf-8');
// Envia a resposta JSON, garantindo que caracteres UTF-8 sejam preservados
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Fecha a conexão com o banco de dados
$con->close();

?>