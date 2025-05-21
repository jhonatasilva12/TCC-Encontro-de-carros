<?php
require_once 'funcoes.php';
require_once 'banco/autentica.php';
require_once 'banco/procura.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/5d7149073d.js" crossorigin="anonymous"></script>
    <title>Navbar</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    
  </head>
  <body>

    <script defer src="assets/js/index.js"></script>

  <div class="geral">

    <header class="header">
      <nav class="nav">

        <div class="nav-list">
          <a href="cadastro.html" class="icon-list"><i class="fas fa-sign-in-alt"></i></a>
          <a href="login.html" class="icon-list"><i class="fas fa-user-plus"></i></a>
          <a href="index.html" class="icon-list"><i class="fas fa-home"></i></a>
          <a href="#" class="icon-list"><i class="fas fa-users"></i></a>
          <a href="#" class="icon-list"><i class="fas fa-shopping-cart"></i></a>
          <a href="#" class="icon-list"><i class="fas fa-info-circle"></i></a>
        </div>
        
        <ul class="nav-list">
          <img src="assets/images/logo.png" alt="logotipo de um carro" class="logo">
          <li><a href="cadastro.html" class="letter-list">Cadastro</a></li>
          <li><a href="login.html" class="letter-list">Login</a></li>
          <li><a href="index.html" class="letter-list">Home</a></li>
          <li><a href="#" class="letter-list">Grupos</a></li>
          <li><a href="#" class="letter-list">Vendas</a></li>
          <li><a href="#" class="letter-list">Sobre</a></li>
        </ul>

      </nav>
    </header>

      
    <main class="hero">

<!---------------------------------------------------post-------------------------------------------------------------------->
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="p-superior">
                <div class="p-identifica">
                    <!-- Você pode adicionar uma imagem de perfil do usuário aqui -->
                    <img src="assets/images/verde.png" class="p-fotinha">
                    <p class="p-nome">
                        <?php echo htmlspecialchars($post['nome_user']) . ' ' . htmlspecialchars($post['sobrenome_user']); ?>
                    </p>
                </div>

                <div class="superior-direita">
                    <button class="mais"><i class="fas fa-ellipsis-v"></i></button>
                    <div class="pop-mais">
                        <ul class="pop-i-list">
                            <li><i class="fas fa-share"></i></li>
                            <li><i class="fas fa-exclamation-triangle"></i></li>
                        </ul>
                        <ul class="pop-list">
                            <li><a href="#" class="letter-list">compartilhar</a></li>
                            <li><a href="#" class="letter-list">denunciar</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-centro">
                <?php if (!empty($post['titulo_post'])): ?>
                <h3 class="p-titulo"><?php echo htmlspecialchars($post['titulo_post']); ?></h3>
                <?php endif; ?>
                <p class="p-texto"><?php echo htmlspecialchars($post['texto_post']); ?></p>
                <?php if (!empty($post['imagem_post'])): ?>
                <img src="assets/uploads/<?php echo htmlspecialchars($post['imagem_post']); ?>" class="p-img">
                <?php endif; ?>
            </div>

            <div class="p-inferior">
                <div class="inferior-esquerda">
                    <button class="p-vote"><i class="fas fa-thumbs-up"></i></button>
                </div>
                <div class="inferior-direita">
                    <span class="p-tempo" data-tempo="<?php echo $post['data_post']; ?>">
                        <?php echo tempoDecorrido($post['data_post']); ?>
                    </span>
                    <button class="p-comentario"><i class="fas fa-comment"></i></button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
<!-------------------------------------------fim post principal--------------------------------------------------------------->

    </main>

    <div class="search-box">
      <form action="login.html" method="get"></form>
        <input class="search-txt" type="search" name="pesquisa" placeholder="faça a sua pesquisa">
        <button type="submit" class="search-btn">
          <i class="fas fa-search"></i>
        </button>
      </form>
    </div>
  
  </div> <!--fim geral-->


  

    <div class="criacao">

      <button class="criar"><i class="fas fa-plus"></i></button>

      <button class="criar-post"><i class="fas fa-edit"></i></button>
      <button class="criar-grupo"><i class="fas fa-users"></i></button>
      <button class="criar-evento"><i class="fas fa-calendar-plus"></i></button>

<!----------------------------------->

      <div id="form-post" class="fundo-modal">

        <div class="form-modal">
          
          <div class="header-form-criacao">
            <button class="fecha-modal">X</button>
            <h2>Criar Novo Post</h2>
          </div>
          
          <form class="modal-container" method="post" action="banco/insert_tb_post">

            <div class="form-group">
              <label for="titulo-post">Título (opcional)</label>
              <input type="text" id="post-title" maxlength="50">
            </div>
            
            <div class="form-group">
              <label for="texto-post">Texto*</label>
              <textarea id="post-text" maxlength="600" required></textarea>
            </div>
            
            <div class="form-group">
              <label for="imagem-post">Imagem</label>
              <input type="file" id="imagem-post" accept="image/*">
            </div>
            
            <div class="form-group">
              <label for="tipo-post">Tipo de Post</label>
              <select id="tipo-post" required>
                <!-- Opções serão preenchidas via JavaScript -->
              </select>
            </div>
            
            <button type="submit">Publicar</button>
          </form>
        </div>
      </div>

<!----------------------------------->

      <div id="form-grupo" class="fundo-modal">

        <div class="form-modal">
          
          <div class="header-form-criacao">
            <button class="fecha-modal">X</button>
            <h2>Criar Novo Grupo</h2>
          </div>
          
          <form class="modal-container" action="banco/insert_tb_grupo" method="post">
            
            <div class="form-group">
              <label for="group-name">Nome do Grupo*</label>
              <input type="text" id="group-name" maxlength="50" required>
            </div>
            
            <div class="form-group">
              <img id="preview-grupo" src="./../img/userPadrao.png" style="height:250px; object-fit: cover;" >
              <div>
                <label for="form-foto"> Carregar Imagem</label>
                <input type="file" id="foto-grupo" name="foto" accept="image/*" class="custom-file-input" style="display: none">
              </div>
            </div>
            
            <div class="form-group">
              <label for="group-description">Descrição</label>
              <textarea id="group-description" maxlength="600"></textarea>
            </div>
            
            <div class="form-group">
              <label for="group-theme">Tema do Grupo</label>
              <select id="group-theme">
                <!-- Opções serão preenchidas via JavaScript -->
              </select>
            </div>
            
            <button type="submit">Criar Grupo</button>
          </form>
        </div>
      </div>

<!----------------------------------->

      <div id="form-evento" class="fundo-modal">

        <div class="form-modal">
          
          <div class="header-form-criacao">
            <button class="fecha-modal">X</button>
            <h2>Criar Novo Evento</h2>
          </div>
          
          <form class="modal-container" action="banco/insert_tb_evento" method="post">
            
            <div class="form-group">
              <label for="event-name">Nome do Evento*</label>
              <input type="text" id="event-name" maxlength="30" required>
            </div>
            
            <div class="form-group">
              <img id="preview-evento" src="./../img/userPadrao.png" style="height:250px; object-fit: cover;" >
              <div>
                <label for="form-foto"> Carregar Imagem</label>
                <input type="file" id="foto-evento" name="foto" accept="image/*" class="custom-file-input" style="display: none">
              </div>
            </div>
            
            <div class="form-group">
              <label for="event-description">Descrição*</label>
              <textarea id="event-description" maxlength="300" required></textarea>
            </div>
            
            <div class="form-group">
              <label for="event-start-date">Data de Início*</label>
              <input type="date" id="event-start-date" required>
            </div>
            
            <div class="form-group">
              <label for="event-end-date">Data de Término (opcional)</label>
              <input type="date" id="event-end-date">
            </div>
            
            <div class="form-group">
              <label for="event-start-time">Horário de Início</label>
              <input type="time" id="event-start-time">
            </div>
            
            <div class="form-group">
              <label for="event-end-time">Horário de Término (opcional)</label>
              <input type="time" id="event-end-time">
            </div>
            
            <div class="form-group">
              <label for="event-pedestrian-price">Valor para Pedestres*</label>
              <input type="text" id="event-pedestrian-price" required>
            </div>
            
            <div class="form-group">
              <label for="event-exhibition-price">Valor para Exposição*</label>
              <input type="text" id="event-exhibition-price" required>
            </div>
            
            <button type="submit">Criar Evento</button>
          </form>
        </div>
      </div>
<!----------------------------------->

    </div>

    
  </body>
</html>
