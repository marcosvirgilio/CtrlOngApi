<?php

// Configuração de Erros (manter para debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o tipo de conteúdo como JSON
header('Content-Type: application/json');

// Inclui a conexão com o banco de dados (assume-se que $con é a variável de conexão)
require_once 'conexao.php';
$con->set_charset("utf8");

// Obtém e decodifica o input JSON do corpo da requisição
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!$jsonParam) {
    echo json_encode(['success' => false, 'message' => 'Dados JSON inválidos ou ausentes.']);
    exit;
}

// 1. Extrair e Validar Dados para a Tabela 'adotante'
// Nota: idAdotante é AUTO_INCREMENT, então não precisa ser fornecido.
$nrTelefone    = trim($jsonParam['nrTelefone'] ?? '');
$nmAdotante    = trim($jsonParam['nmAdotante'] ?? '');
$deEndereco    = trim($jsonParam['deEndereco'] ?? '');
// flListaNegra é CHAR(1), pode ser 'S' ou 'N', padronizando para 'N' se não for enviado 'S'
$flListaNegra  = strtoupper(trim($jsonParam['flListaNegra'] ?? 'N'));
// cdOngCadastro é INT, convertendo para inteiro
$cdOngCadastro = intval($jsonParam['cdOngCadastro'] ?? 0);

// Garante que flListaNegra seja 'S' ou 'N'
$flListaNegra = ($flListaNegra === 'S') ? 'S' : 'N';


// 3. Preparar a instrução SQL de INSERÇÃO na tabela 'adotante'
$stmt = $con->prepare("INSERT INTO adotante (nrTelefone, nmAdotante, deEndereco, flListaNegra, cdOngCadastro)
    VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    // 4. Tratamento de erro na preparação da consulta
    echo json_encode(['success' => false, 'message' => 'Erro ao preparar a consulta: ' . $con->error]);
    exit;
}

// 4. Vincular os parâmetros (Bind)
// Tipos de dados (referente à ordem na query):
// nrTelefone: s (string)
// nmAdotante: s (string)
// deEndereco: s (string)
// flListaNegra: s (string)
// cdOngCadastro: i (integer)
$stmt->bind_param("ssssi", $nrTelefone, $nmAdotante, $deEndereco, $flListaNegra, $cdOngCadastro);

// 5. Executar e retornar o resultado
if ($stmt->execute()) {
    // Retorna o ID do novo adotante inserido
    $novoId = $stmt->insert_id; 
    echo json_encode([
        'success' => true, 
        'message' => 'Adotante registrado com sucesso!',
        'idAdotante' => $novoId // Opcional, mas útil
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro no registro do adotante: ' . $stmt->error]);
}

// 6. Fechar a instrução e a conexão
$stmt->close();
$con->close();

?>