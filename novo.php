<?php
require __DIR__ . '/includes/config.php';
exigir_login();

$erros = [];
$v = ['titulo' => '', 'descricao' => '', 'ano' => '', 'paginas' => '', 'autores' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $erros[] = 'Sessão expirada. Recarregue a página.';
    } else {
        foreach ($v as $k => $_) { $v[$k] = trim($_POST[$k] ?? ''); }

        if ($v['titulo'] === '' || mb_strlen($v['titulo']) > 200) $erros[] = 'Informe o título (até 200 caracteres).';
        if ($v['descricao'] === '') $erros[] = 'Informe a descrição.';
        if ($v['autores'] === '' || mb_strlen($v['autores']) > 255) $erros[] = 'Informe o(s) autor(es).';

        $ano = null;
        if ($v['ano'] !== '') {
            if (!ctype_digit($v['ano']) || (int)$v['ano'] < 1900 || (int)$v['ano'] > (int)date('Y') + 1) {
                $erros[] = 'Ano inválido.';
            } else { $ano = (int)$v['ano']; }
        }
        $paginas = null;
        if ($v['paginas'] !== '') {
            if (!ctype_digit($v['paginas']) || (int)$v['paginas'] < 1 || (int)$v['paginas'] > 9999) {
                $erros[] = 'Quantidade de páginas inválida.';
            } else { $paginas = (int)$v['paginas']; }
        }

        $f = $_FILES['pdf'] ?? null;
        if (!$f || $f['error'] === UPLOAD_ERR_NO_FILE) {
            $erros[] = 'Selecione o arquivo PDF.';
        } elseif ($f['error'] !== UPLOAD_ERR_OK) {
            $erros[] = 'Falha no upload (código ' . (int)$f['error'] . '). Verifique o tamanho do arquivo.';
        } elseif ($f['size'] > MAX_PDF_BYTES) {
            $erros[] = 'O PDF excede 10 MB.';
        } else {
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            $cabecalho = file_get_contents($f['tmp_name'], false, null, 0, 5);
            if ($ext !== 'pdf' || $cabecalho !== '%PDF-') {
                $erros[] = 'O arquivo enviado não é um PDF válido.';
            }
        }

        if (!$erros) {
            $nomeSalvo = bin2hex(random_bytes(16)) . '.pdf';
            if (!is_dir(UPLOAD_DIR)) { mkdir(UPLOAD_DIR, 0755, true); }
            if (!move_uploaded_file($f['tmp_name'], UPLOAD_DIR . $nomeSalvo)) {
                $erros[] = 'Não foi possível salvar o arquivo no servidor.';
            } else {
                $st = db()->prepare(
                    'INSERT INTO artigos (usuario_id, titulo, descricao, ano, paginas, autores, arquivo, nome_original)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );
                $st->execute([
                    $_SESSION['user_id'], $v['titulo'], $v['descricao'], $ano, $paginas,
                    $v['autores'], $nomeSalvo, mb_substr(basename($f['name']), 0, 200),
                ]);
                header('Location: index.php');
                exit;
            }
        }
    }
}

$titulo = 'Novo artigo';
require __DIR__ . '/includes/header.php';
?>
<div class="card card-form">
    <h1>Cadastrar novo artigo</h1>
    <p class="muted">Preencha os dados do artigo e envie o arquivo PDF.</p>

    <?php foreach ($erros as $m): ?><div class="alert alert-error"><?= e($m) ?></div><?php endforeach; ?>

    <form method="post" enctype="multipart/form-data" data-validate novalidate>
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

        <label for="titulo">Título do Artigo *</label>
        <input id="titulo" name="titulo" type="text" maxlength="200" value="<?= e($v['titulo']) ?>" placeholder="Ex: Métodos de pesquisa científica" required>

        <label for="descricao">Descrição do Artigo *</label>
        <textarea id="descricao" name="descricao" rows="4" placeholder="Resumo e principais informações do artigo..." required><?= e($v['descricao']) ?></textarea>

        <div class="row">
            <div>
                <label for="ano">Ano de Realização</label>
                <input id="ano" name="ano" type="number" min="1900" max="<?= (int)date('Y') + 1 ?>" value="<?= e($v['ano']) ?>" placeholder="Ex: 2024">
            </div>
            <div>
                <label for="paginas">Quantidade de Páginas</label>
                <input id="paginas" name="paginas" type="number" min="1" max="9999" value="<?= e($v['paginas']) ?>" placeholder="Ex: 25">
            </div>
        </div>

        <label for="autores">Nome dos Autores *</label>
        <input id="autores" name="autores" type="text" maxlength="255" value="<?= e($v['autores']) ?>" placeholder="Ex: Maria Souza, João Lima" required>

        <label for="pdf">Arquivo PDF * <span class="muted">(máx. 10 MB)</span></label>
        <input id="pdf" name="pdf" type="file" accept="application/pdf,.pdf" required>

        <div class="actions">
            <a class="btn btn-outline-dark" href="index.php">Cancelar</a>
            <button class="btn btn-primary" type="submit">Salvar artigo</button>
        </div>
    </form>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
