<?php foreach ($posts as $post): ?>
      <div class="post" <?php echo htmlspecialchars($post['cor_fundo']); ?>">
          <div class="p-superior">
              <div class="p-identifica">
                  <img src="assets/uploads/<?php echo !empty($post['img_user']) ? htmlspecialchars($post['img_user']) : 'default-user.png'; ?>" class="p-fotinha">
                  <div>
                      <p class="p-nome">
                          <?php echo htmlspecialchars($post['nome_user']) . ' ' . htmlspecialchars($post['sobrenome_user']); ?>
                      </p>
                      <span class="post-tag" style="background-color: <?php echo htmlspecialchars($post['cor_fundo']); ?>; color: <?php echo $post['cor_letra'] ? '#fff' : '#000'; ?>">
                          <?php echo htmlspecialchars($post['nome_tipo_post']); ?>
                      </span>
                  </div>
              </div>

              <div class="superior-direita">
                  <button class="mais"><i class="fas fa-ellipsis-v"></i></button>
                  <div class="pop-mais">
                      <ul class="pop-i-list">
                          <li><i class="fas fa-share"></i></li>
                          <li><i class="fas fa-exclamation-triangle"></i></li>
                      </ul>
                      <ul class="pop-list">
                          <li><a href="#">compartilhar</a></li>
                          <li><a href="#">denunciar</a></li>
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
              <img src="assets/uploads/<?php echo htmlspecialchars($post['imagem_post']); ?>" class="p-img" loading="lazy">
              <?php endif; ?>
          </div>

          <div class="p-inferior">
              <div class="inferior-esquerda">
                  <button class="p-vote" data-post-id="<?php echo $post['id_post']; ?>">
                      <i class="fas fa-thumbs-up"></i>
                      <span class="p-count"><?php echo $post['likes_count']; ?></span>
                  </button>
              </div>
              <div class="inferior-direita">
                  <span class="p-tempo" data-tempo="<?php echo date('Y-m-d H:i:s', strtotime($post['data_post'])); ?>">
                      <?php echo tempoDecorrido($post['data_post']); ?>
                  </span>
                  <button class="p-comentario">
                      <i class="fas fa-comment"></i>
                      <span class="p-count"><?php echo $post['comentarios_count']; ?></span>
                  </button>
              </div>
          </div>
      </div>
  <?php endforeach; ?>