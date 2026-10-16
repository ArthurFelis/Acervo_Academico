<?php
// Uso: $titulo = 'Página'; require 'includes/header.php';
$titulo = $titulo ?? 'Acervo Acadêmico';
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?> - Acervo Acadêmico</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php">
        <span class="brand-logo">A</span>
        <span>Acervo Acadêmico</span>
    </a>
    <nav>
        <?php if (usuario_logado()): ?>
            <span class="user"><?= e($_SESSION['username']) ?></span>
            <a class="btn btn-outline <?= $pagina === 'index.php' ? 'active' : '' ?>" href="index.php">Acervo</a>
            <a class="btn btn-primary-light" href="novo.php">+ Novo Artigo</a>
            <a class="btn btn-outline" href="logout.php">Sair</a>
        <?php else: ?>
            <a class="btn btn-outline" href="login.php">Entrar</a>
            <a class="btn btn-primary-light" href="register.php">Cadastrar</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
