<?php
require_once __DIR__ . '/config/database.php';

$migrations = [
    "ALTER TABLE instituicoes ADD COLUMN logo_path VARCHAR(255) NULL",
    "ALTER TABLE oportunidades ADD COLUMN latitude DECIMAL(10, 8) NULL",
    "ALTER TABLE oportunidades ADD COLUMN longitude DECIMAL(11, 8) NULL",
    "ALTER TABLE inscricoes MODIFY COLUMN estado ENUM('submetida', 'aceite', 'recusada', 'cancelada', 'concluida') DEFAULT 'submetida'",
    "CREATE TABLE IF NOT EXISTS avaliacoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        inscricao_id INT NOT NULL,
        tipo ENUM('voluntario_para_oportunidade', 'instituicao_para_voluntario') NOT NULL,
        avaliador_id INT NOT NULL,
        estrelas TINYINT NOT NULL,
        comentario TEXT,
        data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (inscricao_id) REFERENCES inscricoes(id) ON DELETE CASCADE,
        FOREIGN KEY (avaliador_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_avaliacao (inscricao_id, tipo)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS noticias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(200) NOT NULL,
        conteudo TEXT NOT NULL,
        autor_id INT,
        publicada TINYINT(1) DEFAULT 1,
        criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (autor_id) REFERENCES users(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(64) NOT NULL UNIQUE,
        expira_em TIMESTAMP NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

$db = getDB();
$ok = 0;
$errors = [];

foreach ($migrations as $sql) {
    try {
        $db->exec($sql);
        $ok++;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') === false && strpos($e->getMessage(), 'already exists') === false) {
            $errors[] = $e->getMessage();
        } else {
            $ok++;
        }
    }
}

if (!is_dir(__DIR__ . '/uploads/logos')) {
    mkdir(__DIR__ . '/uploads/logos', 0755, true);
}
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0755, true);
}

echo "<h2>Atualização da Base de Dados</h2>";
echo "<p>Migrações aplicadas: $ok/" . count($migrations) . "</p>";
if ($errors) {
    echo "<ul style='color:red'>";
    foreach ($errors as $err) {
        echo "<li>" . htmlspecialchars($err) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:green'>Base de dados atualizada com sucesso!</p>";
}
echo "<p><a href='index.php'>Voltar à plataforma</a></p>";
