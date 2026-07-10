<?php
session_start();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['db_host'] ?? 'localhost';
    $name = $_POST['db_name'] ?? '';
    $user = $_POST['db_user'] ?? '';
    $pass = $_POST['db_pass'] ?? '';
    $admin_email = $_POST['admin_email'] ?? 'admin@voluntariado.pt';
    $admin_pass = $_POST['admin_pass'] ?? '';
    $admin_nome = $_POST['admin_nome'] ?? 'Administrador';

    try {
        $dsn = "mysql:host=$host;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$name`");

        $schema = file_get_contents(__DIR__ . '/sql/schema.sql');
        $pdo->exec($schema);

        $hash = password_hash($admin_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nome, email, password_hash, perfil) VALUES (?, ?, ?, 'administrador')");
        $stmt->execute([$admin_nome, $admin_email, $hash]);

        $admin_id = $pdo->lastInsertId();
        $stmt2 = $pdo->prepare("INSERT INTO voluntarios (user_id, localidade) VALUES (?, 'Portugal')");
        $stmt2->execute([$admin_id]);

        $config_content = "<?php\n";
        $config_content .= "define('DB_HOST', '$host');\n";
        $config_content .= "define('DB_NAME', '$name');\n";
        $config_content .= "define('DB_USER', '$user');\n";
        $config_content .= "define('DB_PASS', '$pass');\n";
        $config_content .= "define('DB_CHARSET', 'utf8mb4');\n\n";
        $config_content .= "function getDB() {\n";
        $config_content .= "    static \$pdo = null;\n";
        $config_content .= "    if (\$pdo === null) {\n";
        $config_content .= "        try {\n";
        $config_content .= "            \$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;\n";
        $config_content .= "            \$pdo = new PDO(\$dsn, DB_USER, DB_PASS, [\n";
        $config_content .= "                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n";
        $config_content .= "                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
        $config_content .= "                PDO::ATTR_EMULATE_PREPARES => false,\n";
        $config_content .= "            ]);\n";
        $config_content .= "        } catch (PDOException \$e) {\n";
        $config_content .= "            die('Erro na ligação: ' . \$e->getMessage());\n";
        $config_content .= "        }\n";
        $config_content .= "    }\n";
        $config_content .= "    return \$pdo;\n";
        $config_content .= "}\n";

        file_put_contents(__DIR__ . '/config/database.php', $config_content);

        $message = 'Instalação concluída! Podes fazer login com: ' . $admin_email;

    } catch (PDOException $e) {
        $error = 'Erro: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação - Plataforma de Voluntariado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">Instalação da Plataforma</h2>

                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                        <a href="index.php" class="btn btn-primary w-100">Ir para a Plataforma</a>
                    <?php else: ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <h5>Dados da Base de Dados</h5>
                        <div class="mb-3">
                            <label class="form-label">Host</label>
                            <input type="text" class="form-control" name="db_host" value="localhost" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nome da BD</label>
                            <input type="text" class="form-control" name="db_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Utilizador BD</label>
                            <input type="text" class="form-control" name="db_user" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password BD</label>
                            <input type="password" class="form-control" name="db_pass" required>
                        </div>

                        <hr>
                        <h5>Conta de Administrador</h5>
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" name="admin_nome" value="Administrador">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="admin_email" value="admin@voluntariado.pt" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="admin_pass" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Instalar</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
