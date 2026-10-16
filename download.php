<?php
require __DIR__ . '/includes/config.php';
exigir_login();

$id = (int)($_GET['id'] ?? 0);
$st = db()->prepare('SELECT arquivo, nome_original FROM artigos WHERE id = ?');
$st->execute([$id]);
$a = $st->fetch();

$caminho = $a ? UPLOAD_DIR . basename($a['arquivo']) : '';
if (!$a || !is_file($caminho)) {
    http_response_code(404);
    exit('Arquivo não encontrado.');
}

$nome = preg_replace('/[^A-Za-z0-9._ -]/', '_', $a['nome_original']);
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $nome . '"');
header('Content-Length: ' . filesize($caminho));
readfile($caminho);
exit;
