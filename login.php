<?php
require __DIR__ . '/includes/config.php';

if (usuario_logado()) { header('Location: index.php'); exit; }

$erro = '';
$identificador = '';
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $erro = 'Sessão expirada. Recarregue a página.';
    } else {
        $identificador = trim($_POST['identificador'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $st = db()->prepare('SELECT * FROM usuarios WHERE username = ? OR email = ?');
        $st->execute([$identificador, $identificador]);
        $u = $st->fetch();

        if ($u && password_verify($senha, $u['senha_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$u['id'];
            $_SESSION['username'] = $u['username'];
            header('Location: index.php');
            exit;
        }
        $erro = 'Usuário/e-mail ou senha incorretos.';
    }
}

$titulo = 'Login';
require __DIR__ . '/includes/header.php';
?>
<div class="card card-narrow">
    <h1>Entrar</h1>
    <p class="muted">Acesse sua conta para ver e cadastrar artigos.</p>

    <?php if ($flash): ?><div class="alert alert-ok"><?= e($flash) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alert alert-error"><?= e($erro) ?></div><?php endif; ?>

    <form method="post" data-validate novalidate>
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

        <label for="identificador">Usuário ou e-mail *</label>
        <input id="identificador" name="identificador" type="text" value="<?= e($identificador) ?>" required>

        <label for="senha">Senha *</label>
        <input id="senha" name="senha" type="password" required>

        <button class="btn btn-primary btn-block" type="submit">Entrar</button>
    </form>
    <p class="center muted">Não tem conta? <a href="register.php">Cadastre-se</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
