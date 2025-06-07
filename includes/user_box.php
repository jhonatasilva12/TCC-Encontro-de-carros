<?php 
$meetcar = new MeetCarFunctions();

$userId = $_SESSION['user_id'] ?? null; 

$user = $meetcar->buscarUserPorId($userId);
?>

<div class="tabs" style="display: none;">
    <div id="box-1" class="tab ativo" data-tab="1"><i class="fas fa-image"></i>Posts</div>
    <div id="box-2" class="tab" data-tab="2"><i class="fas fa-calendar-days"></i>Eventos</div>
    <div id="box-3" class="tab" data-tab="3"><i class="fas fa-users"></i>Grupos</div>
    <div id="box-4" class="tab" data-tab="4"><i class="fas fa-user"></i>Usuários</div>
</div>

<div class="user-box" data-id="<?= $user['id_user'] ?>">

    <div class="box-topo">
        <img src="assets/images/users/<?php echo ($user['img_user']); ?>" alt="imagem do user logado">
        <label><?php echo htmlspecialchars($user['nome_user']) . ' ' . htmlspecialchars($user['sobrenome_user']); ?></label>
    </div>
    <?php if (!empty($user['bio_user'])) { ?>
        <div class="box-centro">
            <p><?php echo htmlspecialchars($user['bio_user']); ?></p>
        </div>
    <?php } ?>

    <div class="box-inferior">
        <a href="user.php?id=<?= $user['id_user'] ?>"><i class="fas fa-user-alt">ver mais</i></a>
        <a href="banco/logout.php"><i class="fas fa-sign-out-alt">Logout</i></a>
    </div>
    
</div>
