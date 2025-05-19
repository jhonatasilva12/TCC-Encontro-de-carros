<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="./assets/css/styles.css">
    <title>Criar Novo Grupo</title>
    
</head>

  <div class="bo">


<body>



    <div class="form-container">
        <h1>Criar Novo Grupo</h1>
        <form>



 <div class="form-group">
                <label for="group-image">Imagem do Grupo</label>
                <input type="file" id="group-image" name="imagem_grupo" accept="image/*">
                <div class="image-preview" id="imagePreview">
                    <img id="previewImage" src="#" alt="Pré-visualização da imagem">
                </div>
            </div>


            <div class="form-group">
                <label for="group-name">Nome do Grupo</label>
                <input type="text" id="group-name" name="nome_grupo" required placeholder="Digite o nome do grupo">
            </div>
            
            <div class="form-group">
                <label for="group-description">Descrição do Grupo</label>
                <textarea id="group-description" name="descricao_temas" required placeholder="Descreva o propósito do grupo"></textarea>
            </div>
            
           
          <div class="form-group">
                <label for="group-theme">Tema do Grupo</label>
                <select id="group-theme" name="nome_temas" required>
                    <option value="" disabled selected>Selecione um tema</option>
                    <option value="moto">Moto</option>
                    <option value="carro">Carro</option>
                    <option value="ambos">Moto e Carro</option>
                </select>
            </div>
            
            <button type="submit" class="submit-btn">Criar Grupo</button>
        </form>
    </div>

    <script>
        // Função para mostrar a pré-visualização da imagem
        document.getElementById('group-image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImage');
            const previewContainer = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.src = '#';
                previewContainer.style.display = 'none';
            }
        });
    </script>
</body>

</div>
</html>