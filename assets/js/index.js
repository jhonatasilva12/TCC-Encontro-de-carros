const postModal = document.getElementById("form-post");
const eventoModal = document.getElementById("form-evento");
const grupoModal = document.getElementById("form-grupo");
const maisBtn = document.querySelector('.mais');
const opCriar = document.querySelector('.criar');

document.querySelector('.criar').addEventListener('click', function(e) { //click = ativo
  e.stopPropagation(); // Impede que o clique se propague para o document
  this.classList.toggle('ativo');
});

document.querySelector('.criar-post').addEventListener('click', function(e) { //click = modal ativo
  e.stopPropagation(); // Impede que o clique se propague para o document
  postModal.classList.toggle('ativo');
  opCriar.classList.remove('ativo');
});

document.querySelector('.criar-evento').addEventListener('click', function(e) { //click = modal ativo
  e.stopPropagation(); // Impede que o clique se propague para o document
  eventoModal.classList.toggle('ativo');
  opCriar.classList.remove('ativo');
});

document.querySelector('.criar-grupo').addEventListener('click', function(e) { //click = modal ativo
  e.stopPropagation(); // Impede que o clique se propague para o document
  grupoModal.classList.toggle('ativo');
  opCriar.classList.remove('ativo');
});

document.querySelector('.mais').addEventListener('click', function(e) { //click = ativo
  e.stopPropagation(); // Impede que o clique se propague para o document
  this.classList.toggle('ativo');
});

document.querySelector('.fecha-modal').addEventListener('click', function(e) { // Fechar modais ao clicar no X
  e.stopPropagation(); // Impede que o clique se propague para o document
  postModal.classList.remove('ativo');
  eventoModal.classList.remove('ativo');
  grupoModal.classList.remove('ativo');
});

document.addEventListener('click', function(e) { //serve para que, ao clicar fora, torne os ativos em inativos (em relação a clicado ou não)

  if (!e.target.closest('.criacao')) {
    opCriar.classList.remove('ativo');
  }

  if (!e.target.closest('.form-modal')) {
    eventoModal.classList.remove('ativo');
    postModal.classList.remove('ativo');
    grupoModal.classList.remove('ativo');
}

  if (!e.target.closest('.superior-direita') && !e.target.closest('.pop-mais')) {
    maisBtn.classList.remove('ativo');
  }
});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////



/*atualiza a foto dos formularios*/
document.querySelector('.formFoto').addEventListener("change", readImage, false);
  function readImage() {
    if (this.files && this.files[0]) {
      //FileReader é usado para ler arquivos selecionados pelo usuário.
      var file = new FileReader();
      //Esta linha define um evento que será acionado quando o processo de leitura do arquivo estiver concluído
      file.onload = function(e) {
      //Nesta linha, estamos atribuindo o resultado da leitura do arquivo à propriedade src de um elemento HTML com o ID "preview
        document.getElementById("preview").src = e.target.result;
      };
      // Esta linha inicia a leitura do primeiro arquivo selecionado pelo usuário (representado por this.files[0]). O método readAsDataURL converte o arquivo em uma URL de dados, que será usada na linha anterior para definir a imagem de visualização.
      file.readAsDataURL(this.files[0]);
    }
  }





  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  document.addEventListener('DOMContentLoaded', function() {
    // Carrega as opções quando a página é carregada
    carregarOpcoesFormularios();
    
    // Configura os listeners para quando os modais são abertos
    document.getElementById('form-post').addEventListener('shown.bs.modal', carregarOpcoesFormularios);
    document.getElementById('form-grupo').addEventListener('shown.bs.modal', carregarOpcoesFormularios);
  });

  function carregarOpcoesFormularios() {
      fetch('buscar_opcoes.php')
          .then(response => response.json())
          .then(data => {
              if (data.error) {
                  console.error('Erro ao carregar opções:', data.error);
                  return;
              }
              
              // Preencher tipos de post
              const selectTipoPost = document.getElementById('tipo-post');
              if (selectTipoPost) {
                  selectTipoPost.innerHTML = '<option value="">Selecione um tipo</option>';
                  data.tiposPost.forEach(tipo => {
                      const option = document.createElement('option');
                      option.value = tipo.id_tipo_post;
                      option.textContent = tipo.nome_tipo_post;
                      // Adiciona estilo baseado nas cores do banco
                      option.style.backgroundColor = tipo.cor_fundo;
                      option.style.color = tipo.cor_letra ? '#fff' : '#000';
                      selectTipoPost.appendChild(option);
                  });
              }
              
              // Preencher temas de grupo
              const selectTemaGrupo = document.getElementById('group-theme');
              if (selectTemaGrupo) {
                  selectTemaGrupo.innerHTML = '<option value="">Selecione um tema</option>';
                  data.temasGrupo.forEach(tema => {
                      const option = document.createElement('option');
                      option.value = tema.id_temas_grupo;
                      option.textContent = tema.nome_temas;
                      // Adiciona estilo baseado nas cores do banco
                      option.style.backgroundColor = tema.cor_fundo;
                      option.style.color = tema.cor_letras ? '#fff' : '#000';
                      selectTemaGrupo.appendChild(option);
                  });
              }
          })
          .catch(error => console.error('Erro:', error));
  }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function atualizarTempos() {
    document.querySelectorAll('.p-tempo').forEach(elemento => {
        const dataPost = elemento.getAttribute('data-tempo');
        // Converter para formato ISO se necessário
        const dataISO = dataPost.replace(' ', 'T') + 'Z';
        elemento.textContent = calcularTempoDecorrido(dataISO);
    });
}

function calcularTempoDecorrido(dataString) {
    // Garantir que a data está no formato correto
    const dataPost = new Date(dataString);
    const agora = new Date();
    
    // Verificar se a data é válida
    if (isNaN(dataPost.getTime())) {
        console.error('Data inválida:', dataString);
        return '';
    }

    const diff = Math.floor((agora - dataPost) / 1000); // diferença em segundos
    
    if (diff < 60) return 'há poucos segundos';
    if (diff < 3600) {
        const mins = Math.floor(diff/60);
        return `há ${mins} minuto${mins !== 1 ? 's' : ''}`;
    }
    if (diff < 86400) {
        const horas = Math.floor(diff/3600);
        return `há ${horas} hora${horas !== 1 ? 's' : ''}`;
    }
    if (diff < 2592000) {
        const dias = Math.floor(diff/86400);
        return `há ${dias} dia${dias !== 1 ? 's' : ''}`;
    }
    if (diff < 31536000) {
        const meses = Math.floor(diff/2592000);
        return `há ${meses} mês${meses !== 1 ? 'es' : ''}`;
    }
    const anos = Math.floor(diff/31536000);
    return `há ${anos} ano${anos !== 1 ? 's' : ''}`;
}

// Inicialização quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    // Converter todos os tempos para o formato ISO no carregamento
    document.querySelectorAll('.p-tempo').forEach(el => {
        if (!el.getAttribute('data-tempo')) {
            // Se não houver data-tempo, usar o texto como fallback
            el.setAttribute('data-tempo', new Date().toISOString());
        } else {
            // Garantir que a data está no formato ISO
            const rawDate = el.getAttribute('data-tempo');
            if (!rawDate.includes('T')) {
                const isoDate = rawDate.replace(' ', 'T') + 'Z';
                el.setAttribute('data-tempo', isoDate);
            }
        }
    });
    
    // Atualizar imediatamente
    atualizarTempos();
    
    // Atualizar a cada minuto
    setInterval(atualizarTempos, 60000);
});