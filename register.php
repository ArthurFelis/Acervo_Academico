<?php
require __DIR__ . '/includes/config.php';

if (usuario_logado()) { header('Location: index.php'); exit; }

$erros = [];
$username = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok()) {
        $erros[] = 'Sessão expirada. Recarregue a página.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $senha    = $_POST['senha'] ?? '';
        $senha2   = $_POST['senha2'] ?? '';

        if (!preg_match('/^[A-Za-z0-9_.]{3,30}$/', $username)) {
            $erros[] = 'Usuário deve ter 3 a 30 caracteres (letras, números, _ ou .).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'E-mail inválido.';
        }
        if (strlen($senha) < 6) {
            $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
        }
        if ($senha !== $senha2) {
            $erros[] = 'As senhas não coincidem.';
        }

        if (!$erros) {
            $st = db()->prepare('SELECT 1 FROM usuarios WHERE username = ? OR email = ?');
            $st->execute([$username, $email]);
            if ($st->fetch()) {
                $erros[] = 'Usuário ou e-mail já cadastrado.';
            } else {
                $st = db()->prepare('INSERT INTO usuarios (username, email, senha_hash) VALUES (?, ?, ?)');
                $st->execute([$username, $email, password_hash($senha, PASSWORD_DEFAULT)]);
                $_SESSION['flash'] = 'Cadastro realizado! Faça login.';
                header('Location: login.php');
                exit;
            }
        }
    }
}

$titulo = 'Cadastro';
require __DIR__ . '/includes/header.php';
?>
<div class="card card-narrow">
    <h1>Criar conta</h1>
    <p class="muted">Informe seus dados para acessar o acervo.</p>

    <?php foreach ($erros as $m): ?><div class="alert alert-error"><?= e($m) ?></div><?php endforeach; ?>

    <form method="post" data-validate novalidate>
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

        <label for="username">Nome de usuário *</label>
        <input id="username" name="username" type="text" value="<?= e($username) ?>" placeholder="Ex: joao.silva" required>

        <label for="email">E-mail *</label>
        <input id="email" name="email" type="email" value="<?= e($email) ?>" placeholder="Ex: joao@email.com" required>

        <label for="senha">Senha *</label>
        <input id="senha" name="senha" type="password" placeholder="Mínimo 6 caracteres" required>

        <label for="senha2">Confirmar senha *</label>
        <input id="senha2" name="senha2" type="password" placeholder="Repita a senha" required>

        <button class="btn btn-primary btn-block" type="submit">Cadastrar</button>
    </form>
    <p class="center muted">Já tem conta? <a href="login.php">Entrar</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
