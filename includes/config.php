<?php
// ============================================================
// CONFIGURAÇÃO - altere com os dados do seu MySQL na InfinityFree
// (Painel > MySQL Databases)
// ============================================================
define('DB_HOST', 'sqlXXX.infinityfree.com');   // MySQL Hostname
define('DB_NAME', 'if0_XXXXXXXX_acervo');       // MySQL DB Name
define('DB_USER', 'if0_XXXXXXXX');              // MySQL User Name
define('DB_PASS', 'SUA_SENHA_AQUI');            // Senha do painel (vPanel)

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_PDF_BYTES', 10 * 1024 * 1024);      // 10 MB

session_start();

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER, DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function usuario_logado(): bool {
    return isset($_SESSION['user_id']);
}

function exigir_login(): void {
    if (!usuario_logado()) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_ok(): bool {
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}
