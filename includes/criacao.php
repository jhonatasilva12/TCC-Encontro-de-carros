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
          
          <form class="modal-container" method="post" action="banco/insert_tb_post.php">

            <div class="form-group">
              <label for="titulo-post">Título (opcional)</label>
              <input type="text" id="post-title" maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="tipo-post">Tipo de Post*</label>
                <select id="tipo-post" name="fk_id_tipo_post" required class="form-control">

                </select>
            </div>
            
            <div class="form-group">
              <label for="texto-post">Texto*</label>
              <textarea id="post-text" maxlength="600" required></textarea>
            </div>
            
            <div class="form-group">
                <div class="image-upload-container">
                    <img id="preview-post" src="./assets/images/users/user_padrao.jpg" class="image-preview">
                    <label for="imagem-post" class="upload-label">
                        <i class="fas fa-camera"></i> Selecionar Imagem
                    </label>
                    <input type="file" id="imagem-post" name="imagem_post" accept="image/*" class="file-input">
                </div>
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
          
          <form class="modal-container" action="banco/insert_tb_grupo.php" method="post">
            
            <div class="form-group">
                <div class="image-upload-container">
                    <img id="preview-grupo" src="./assets/images/users/user_padrao.jpg" class="image-preview">
                    <label for="foto-grupo" class="upload-label">
                        <i class="fas fa-camera"></i> Selecionar Imagem
                    </label>
                    <input type="file" id="foto-grupo" name="foto" accept="image/*" class="file-input">
                </div>
            </div>
            
            <div class="form-group">
              <label for="group-name">Nome do Grupo*</label>
              <input type="text" id="group-name" maxlength="50" required>
            </div>

            <div class="form-group">
              <label for="group-theme">Tema do Grupo*</label>
              <select id="group-theme" name="fk_id_temas_grupo" required class="form-control">
              </select>
            </div>
            
            <div class="form-group">
              <label for="group-description">Descrição</label>
              <textarea id="group-description" maxlength="600"></textarea>
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
          
          <form class="modal-container" action="banco/insert_tb_evento.php" method="post">
            
            <div class="form-group">
              <label for="event-name">Nome do Evento*</label>
              <input type="text" id="event-name" maxlength="30" required>
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
            
            <div class="form-group">
                <div class="image-upload-container">
                    <img id="preview-evento" src="./assets/images/users/user_padrao.jpg" class="image-preview">
                    <label for="foto-evento" class="upload-label">
                        <i class="fas fa-camera"></i> Selecionar Imagem
                    </label>
                    <input type="file" id="foto-evento" name="foto" accept="image/*" class="file-input">
                </div>
            </div>
            
            <button type="submit">Criar Evento</button>
          </form>
        </div>
      </div>
<!----------------------------------->

    </div>
