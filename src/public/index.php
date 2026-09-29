<?php
$environment = getenv('APP_ENV') ?: 'unknown';
$dbHost = getenv('DB_HOST') ?: 'db';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: '';
$dbUser = getenv('DB_USER') ?: '';
$dbPass = getenv('DB_PASSWORD') ?: '';

$connected = false;
$error = null;
$version = null;
$items = [];

try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $connected = true;
    $version = $pdo->query('SELECT VERSION()')->fetchColumn();
    $items = $pdo->query('SELECT id, label FROM demo_items ORDER BY id')->fetchAll();
} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Codespaces Compose demo</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 850px; margin: 3rem auto; padding: 0 1rem; line-height: 1.5; }
        code { background: #eee; padding: .15rem .35rem; border-radius: .25rem; }
        .ok { color: #18794e; }
        .bad { color: #b42318; }
    </style>
</head>
<body>
    <h1>PHP + MariaDB in Codespaces</h1>
    <p>Environment: <strong><?= htmlspecialchars($environment) ?></strong></p>
    <p>PHP: <strong><?= htmlspecialchars(PHP_VERSION) ?></strong></p>

    <?php if ($connected): ?>
        <p class="ok">Database connection: <strong>OK</strong></p>
        <p>MariaDB/MySQL server: <strong><?= htmlspecialchars((string)$version) ?></strong></p>
        <p>PHP connects to the database using <code>db:3306</code>.</p>
        <h2>Seed data</h2>
        <ul>
            <?php foreach ($items as $item): ?>
                <li><?= (int)$item['id'] ?> — <?= htmlspecialchars($item['label']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="bad">Database connection: <strong>FAILED</strong></p>
        <pre><?= htmlspecialchars((string)$error) ?></pre>
    <?php endif; ?>

    <?php if ($environment === 'teaching'): ?>
        <p>Teaching mode also starts phpMyAdmin on the forwarded <strong>8081</strong> port.</p>
    <?php elseif ($environment === 'development'): ?>
        <p>Development mode enables Xdebug and the VS Code PHP Debug extension.</p>
    <?php endif; ?>
</body>
</html>
