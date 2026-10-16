<?php
require __DIR__ . '/includes/config.php';
exigir_login();

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $like = '%' . $q . '%';
    $st = db()->prepare(
        'SELECT a.*, u.username FROM artigos a
         JOIN usuarios u ON u.id = a.usuario_id
         WHERE a.titulo LIKE ? OR a.descricao LIKE ? OR a.autores LIKE ?
         ORDER BY a.criado_em DESC'
    );
    $st->execute([$like, $like, $like]);
} else {
    $st = db()->query(
        'SELECT a.*, u.username FROM artigos a
         JOIN usuarios u ON u.id = a.usuario_id
         ORDER BY a.criado_em DESC'
    );
}
$artigos = $st->fetchAll();
$total = count($artigos);

$titulo = 'Acervo';
require __DIR__ . '/includes/header.php';
?>
<form class="searchbar" method="get" action="index.php">
    <div class="search-input">
        <span class="search-icon">&#128269;</span>
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Pesquisar por título, descrição ou autor...">
    </div>
    <button class="btn btn-primary" type="submit">Buscar</button>
    <a class="btn btn-primary" href="novo.php">+ Novo Artigo</a>
</form>

<p class="count"><?= $total ?> artigo<?= $total === 1 ? '' : 's' ?> encontrado<?= $total === 1 ? '' : 's' ?>
    <?php if ($q !== ''): ?> para "<strong><?= e($q) ?></strong>" &middot; <a href="index.php">limpar</a><?php endif; ?>
</p>

<?php if ($total === 0): ?>
    <div class="empty">
        <div class="empty-icon">&#128218;</div>
        <p>Nenhum artigo ainda.<br>Clique em <strong>+ Novo Artigo</strong> para começar.</p>
    </div>
<?php else: ?>
    <div class="grid">
    <?php foreach ($artigos as $a): ?>
        <article class="article">
            <h2><?= e($a['titulo']) ?></h2>
            <p class="authors"><?= e($a['autores']) ?></p>
            <p class="desc"><?= e($a['descricao']) ?></p>
            <ul class="meta">
                <?php if ($a['ano']): ?><li><span>Ano</span> <?= (int)$a['ano'] ?></li><?php endif; ?>
                <?php if ($a['paginas']): ?><li><span>Páginas</span> <?= (int)$a['paginas'] ?></li><?php endif; ?>
                <li><span>Enviado por</span> <?= e($a['username']) ?></li>
            </ul>
            <a class="btn btn-primary btn-block" href="download.php?id=<?= (int)$a['id'] ?>">&#11015; Baixar PDF</a>
        </article>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
