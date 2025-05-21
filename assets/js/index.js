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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function atualizarTempos() {
  document.querySelectorAll('.p-tempo').forEach(elemento => {
      const dataPost = elemento.getAttribute('data-tempo');
      elemento.textContent = calcularTempoDecorrido(dataPost);
  });
}

function calcularTempoDecorrido(dataString) {
  const dataPost = new Date(dataString);
  const agora = new Date();
  const diff = Math.floor((agora - dataPost) / 1000); // diferença em segundos
  
  if (diff < 60) return 'há poucos segundos';
  if (diff < 3600) return `há ${Math.floor(diff/60)} minuto${Math.floor(diff/60) !== 1 ? 's' : ''}`;
  if (diff < 86400) return `há ${Math.floor(diff/3600)} hora${Math.floor(diff/3600) !== 1 ? 's' : ''}`;
  if (diff < 2592000) return `há ${Math.floor(diff/86400)} dia${Math.floor(diff/86400) !== 1 ? 's' : ''}`;
  if (diff < 31536000) return `há ${Math.floor(diff/2592000)} mês${Math.floor(diff/2592000) !== 1 ? 'es' : ''}`;
  return `há ${Math.floor(diff/31536000)} ano${Math.floor(diff/31536000) !== 1 ? 's' : ''}`;
}

// Atualize a cada minuto
setInterval(atualizarTempos, 60000);

// Inicialize quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
  // Adicione data-tempo aos elementos
  document.querySelectorAll('.p-tempo').forEach(el => {
      if (!el.getAttribute('data-tempo')) {
          el.setAttribute('data-tempo', el.textContent);
      }
  });
  atualizarTempos();
});
