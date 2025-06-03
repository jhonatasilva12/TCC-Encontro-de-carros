<div class="criacao">
    <button class="criar"><i class="fas fa-plus"></i></button>
    <button class="criar-post"><i class="fas fa-edit"></i></button>
    <button class="criar-grupo"><i class="fas fa-users"></i></button>
    <button class="criar-evento"><i class="fas fa-calendar-plus"></i></button>

    <!------------ modal do Post ------------>
    <div id="form-post">
        <div class="form-modal">
            <div class="header-form-criacao">
                <button class="fecha-modal">X</button>
                <h2>Criar Novo Post</h2>
            </div>
            <form class="modal-container" method="post" action="./banco/insert_tb_post.php" enctype="multipart/form-data" autocomplete="off">
                
                <div class="form-group">
                    <label for="titulo-post">Título (opcional)</label>
                    <input type="text" id="titulo-post" name="titulo_post" maxlength="50">
                </div>
                
                <div class="form-group">
                    <label for="tipo-post">Tipo de Post*</label>
                    <select id="tipo-post" name="fk_id_tipo_post" required class="form-control">
                        <!-- opções carregadas pelo JavaScript -->
                    </select>
                    <div class="micro-form-container">
                        <button type="button" class="btn-micro-form">+ Criar novo tipo</button>
                        <div class="micro-form" style="display:none;">
                            <input type="text" class="micro-input" placeholder="Nome do novo tipo" maxlength="15">
                            <div class="color-picker">
                                <label>Cor de fundo:</label>
                                <input type="color" class="micro-color" value="#3498db" title="Escolha a cor de fundo">
                                
                                <label>Cor do texto:</label>
                                <div class="text-color-options">
                                    <label>
                                        <input type="radio" name="cor_texto" value="1" checked> Claro (branco)
                                    </label>
                                    <label>
                                        <input type="radio" name="cor_texto" value="0"> Escuro (preto)
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="btn-confirm">Criar</button>
                            <button type="button" class="btn-cancel">Cancelar</button>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="texto-post">Texto*</label>
                    <textarea id="texto-post" name="texto_post" maxlength="600" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="group-image">Imagem post</label>
                    <div class="image-preview" id="imagePreview">
                        <img id="postImage" src="#" alt="Pré-visualização da imagem">
                    </div>
                    <input type="file" id="post-image" name="imagem_post" accept="image/*">
                    
                </div>
                
                <button type="submit">Publicar</button>
            </form>
        </div>
    </div>

    <!------------ Modal do Grupo ------------>
    <div id="form-grupo">
        <div class="form-modal">
            <div class="header-form-criacao">
                <button class="fecha-modal">X</button>
                <h2>Criar Novo Grupo</h2>
            </div>
            <form class="modal-container" action="./banco/insert_tb_grupo.php" method="post" enctype="multipart/form-data" autocomplete="off">
                
                <div class="form-group">
                    <div class="image-preview" id="groupPreview">
                        <img id="previewGroup" src="#" alt="Pré-visualização da imagem">
                    </div>
                    <label for="group-image">Imagem do Grupo</label>
                    <input type="file" id="group-image" name="imagem_grupo" accept="image/jpg, image/png, image/jpeg">
                </div>
                
                <div class="form-group">
                    <label for="group-name">Nome do Grupo*</label>
                    <input type="text" id="group-name" name="nome_grupo" maxlength="50" required>
                </div>

                <div class="form-group">
                    <label for="group-theme">Tema do Grupo*</label>
                    <select id="group-theme" name="fk_id_temas_grupo" required class="form-control">
                        <!-- opções carregadas pelo JavaScript -->
                    </select>
                    <div class="micro-form-container">
                        <button type="button" class="btn-micro-form">+ Criar novo tema</button>
                        <div class="micro-form" style="display:none;">
                            <input type="text" class="micro-input" placeholder="Nome do novo tema" maxlength="15">
                            <div class="color-picker">
                                <label>Cor de fundo:</label>
                                <input type="color" class="micro-color" value="#3498db" title="Escolha a cor de fundo">
                                
                                <label>Cor do texto:</label>
                                <div class="text-color-options">
                                    <label>
                                        <input type="radio" name="cor_texto" value="1" checked> Claro (branco)
                                    </label>
                                    <label>
                                        <input type="radio" name="cor_texto" value="0"> Escuro (preto)
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="btn-confirm">Criar</button>
                            <button type="button" class="btn-cancel">Cancelar</button>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="group-description">Descrição</label>
                    <textarea id="group-description" name="descricao_grupo" maxlength="600"></textarea>
                </div>
                
                <button type="submit">Criar Grupo</button>
            </form>
        </div>
    </div>

    <!------------ modal do Evento ------------>
    <div id="form-evento">
        <div class="form-modal">
            <div class="header-form-criacao">
                <button class="fecha-modal">X</button>
                <h2>Criar Novo Evento</h2>
            </div>
            <form class="modal-container" action="./banco/insert_tb_evento.php" method="post" enctype="multipart/form-data" autocomplete="off">
                
                <div class="form-group">
                    <label for="event-name">Nome do Evento*</label>
                    <input type="text" id="event-name" name="nome_evento" maxlength="30" required>
                </div>
                
                <div class="form-group">
                    <label for="event-description">Descrição*</label>
                    <textarea id="event-description" name="descricao_evento" maxlength="300" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="event-start-datetime">Data/Hora de Início*</label>
                    <input type="datetime-local" id="event-start-datetime" name="data_inicio_evento" required>
                </div>
                
                <div class="form-group">
                    <label for="event-end-datetime">Data/Hora de Término (opcional)</label>
                    <input type="datetime-local" id="event-end-datetime" name="data_termino_evento">
                </div>
                
                <div class="form-group">
                    <label for="event-pedestrian-price">Valor para Pedestres*</label>
                    <input type="text" id="event-pedestrian-price" name="valor_pedestre" required>
                </div>
                
                <div class="form-group">
                    <label for="event-exhibition-price">Valor para Exposição*</label>
                    <input type="text" id="event-exhibition-price" name="valor_exposicao" required>
                </div>
                
                <div class="form-group-div">
                    <div class="form-group">
                        <label for="rua">rua / avenida*</label>
                        <input type="text" id="rua" name="rua" maxlength="32" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="numero">n°</label>
                        <input type="number" id="numero" name="numero" maxlength="5" id requied>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" maxlength="35" required>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <option selected="selected">Selecione um estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <div class="image-preview" id="eventPreview">
                        <img id="previewEvent" src="#" alt="Pré-visualização da imagem">
                    </div>
                    <label for="event-image">Imagem do evento</label>
                    <input type="file" id="event-image" name="imagem_evento" accept="image/*">
                </div>
                
                <button type="submit">Criar Evento</button>
            </form>
        </div>
    </div>
</div>
