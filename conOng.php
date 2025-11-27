<?php

// Configurações de exibição de erro
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão existente
require_once 'conexao.php';

// Define o conjunto de caracteres da conexão para UTF-8
// É recomendável usar utf8mb4 se o seu banco de dados suportar, mas mantive utf8 para consistência.
$con->set_charset("utf8");

// Decodifica a entrada JSON (mantido do original, mas ignorado, já que é um GET/SELECT)
// Esta linha é desnecessária para um SELECT/GET simples, mas a mantive como estava no original,
// apenas para fins de contexto, mas não afeta a lógica do SELECT.
json_decode(file_get_contents('php://input'), true);

$sql = "SELECT cdOng, nmOng, nmResponsavel, nrContato FROM ong ORDER BY nmOng";

$result = $con->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    // Itera sobre os resultados e adiciona ao array de resposta
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    // Caso não haja resultados, retorna uma estrutura vazia correspondente à tabela 'ong'
    $response[] = [
        "cdOng" => 0,
        "nmOng" => "",
        "nmResponsavel" => "",
        "nrContato" => ""
    ];
}

// Define o cabeçalho como JSON com charset UTF-8
header('Content-Type: application/json; charset=utf-8');

// Envia a resposta JSON, garantindo que caracteres UTF-8 sejam preservados
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// Fecha a conexão com o banco de dados
$con->close();

?>