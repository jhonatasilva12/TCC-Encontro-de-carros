<?php 
$meetcar = new MeetCarFunctions();

$userId = $_SESSION['user_id'] ?? null; 

$user = $meetcar->buscarUserPorId($userId);
?>
<div class="user-box" data-id="<?= $user['id_user'] ?>">
    <div class="box-topo">
        <img src="assets/images/users/user_padrao.jpg" alt="imagem do user logado">
        <label><?php echo htmlspecialchars($user['nome_user']) . ' ' . htmlspecialchars($user['sobrenome_user']); ?></label>
    </div>

    <div class="box-centro">
        
    </div>

    <div class="box-inferior">
    <a href="user.php?id=<?= $user['id_user'] ?>" class="letter-list"><i class="fas fa-user-alt">perfil completo</i></a>
    <a href="banco/logout.php" class="letter-list"><i class="fas fa-sign-out-alt">Logout</i></a>
    </div>
    
</div>
