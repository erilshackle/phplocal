<!-- CREATE FOLDER HERES AND UPLOAD FILES IN THEIRS FOLDER TO NOT MESS UP
     REQUEST SHOULD BE LIKE /uploads/folder/file.extendsion  
 -->
<?php //** */  THIS FILE HANDLE UPLOADS REQUES. DO NOT DELETE IT
include_once '../core.php';

// Verifique a autenticação do usuário (adapte isso conforme necessário)
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] != 'allowed') {
    header("HTTP/1.1 403 Forbidden");
    echo "Você não tem permissão para acessar este arquivo.";
    exit;
}

// Nome do arquivo solicitado
$filename = $_GET['name'] ?? null;

// Caminho completo do arquivo
$filePath = __DIR__ . '/' . $filename;

// Verifica se o arquivo existe
if (file_exists($filePath)) {
    // Parâmetro para forçar download
    $forceDownload = isset($_GET['download']);

    // Cabeçalhos comuns
    header('Content-Type: ' . mime_content_type($filePath));
    header('Content-Length: ' . filesize($filePath));

    if ($forceDownload) {
        // Força o download se download=1 for passado
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    } else {
        // Exibe o arquivo inline (para imagens, vídeos, etc.)
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
    }

    // Envia o arquivo
    readfile($filePath);
    exit;
} else {
    // Se o arquivo não existir, exiba uma mensagem de erro
    header("HTTP/1.1 404 Not Found");
    echo "Arquivo não encontrado.";
    exit;
}
?>
