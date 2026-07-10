<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$totalOportunidades = $db->query("SELECT COUNT(*) FROM oportunidades WHERE estado IN ('publicada','inscricoes_abertas')")->fetchColumn();
$totalInstituicoes = $db->query("SELECT COUNT(*) FROM instituicoes WHERE estado_validacao = 'aprovada'")->fetchColumn();
$totalVoluntarios = $db->query("SELECT COUNT(*) FROM voluntarios")->fetchColumn();

$oportunidades = $db->query("SELECT o.*, i.nome as inst_nome, c.nome as cat_nome FROM oportunidades o LEFT JOIN instituicoes i ON o.instituicao_id = i.id LEFT JOIN categorias c ON o.categoria_id = c.id WHERE o.estado IN ('publicada','inscricoes_abertas') ORDER BY o.criada_em DESC LIMIT 6")->fetchAll();

$pageTitle = 'Plataforma de Voluntariado Local';
include __DIR__ . '/includes/header.php';
?>

<div class="hero text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Plataforma de Voluntariado Local</h1>
        <p class="lead mb-4">Conectamos instituições com necessidades de voluntariado a cidadãos disponíveis para colaborar em ações de interesse comunitário.</p>
        <?php if (!isLoggedIn()): ?>
            <a href="pages/registo.php" class="btn btn-light btn-lg me-2">Registar como Voluntário</a>
            <a href="pages/registo.php?tipo=instituicao" class="btn btn-outline-light btn-lg">Registar Instituição</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <i class="bi bi-calendar-check text-primary" style="font-size:2rem"></i>
                <h3><?php echo $totalOportunidades; ?></h3>
                <p class="text-muted">Oportunidades Ativas</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <i class="bi bi-building text-success" style="font-size:2rem"></i>
                <h3><?php echo $totalInstituicoes; ?></h3>
                <p class="text-muted">Instituições Registadas</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card stat-card">
                <i class="bi bi-people text-warning" style="font-size:2rem"></i>
                <h3><?php echo $totalVoluntarios; ?></h3>
                <p class="text-muted">Voluntários Inscritos</p>
            </div>
        </div>
    </div>

    <h2 class="mb-4">Oportunidades em Destaque</h2>
    <div class="row">
        <?php foreach ($oportunidades as $o): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($o['cat_nome'] ?? 'Sem categoria'); ?></span>
                    <h5 class="card-title"><?php echo htmlspecialchars($o['titulo']); ?></h5>
                    <p class="text-muted small">
                        <i class="bi bi-building"></i> <?php echo htmlspecialchars($o['inst_nome']); ?><br>
                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($o['local'] ?? $o['freguesia'] ?? 'Portugal'); ?><br>
                        <?php if ($o['data_inicio']): ?>
                        <i class="bi bi-calendar"></i> <?php echo date('d/m/Y', strtotime($o['data_inicio'])); ?>
                        <?php endif; ?>
                    </p>
                    <p class="card-text"><?php echo htmlspecialchars(substr($o['descricao'] ?? '', 0, 100)); ?>...</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="pages/oportunidade_detalhe.php?id=<?php echo $o['id']; ?>" class="btn btn-outline-primary btn-sm">Ver Detalhes</a>
                    <?php if ($o['vagas'] > 0): ?>
                        <span class="badge bg-success float-end"><?php echo $o['vagas']; ?> vaga(s)</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($oportunidades)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size:3rem"></i>
            <p class="text-muted mt-3">Nenhuma oportunidade disponível de momento.</p>
        </div>
        <?php endif; ?>
    </div>

    <?php if (count($oportunidades) > 0): ?>
    <div class="text-center mb-5">
        <a href="pages/oportunidades.php" class="btn btn-primary">Ver Todas as Oportunidades</a>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
