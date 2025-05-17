document.querySelector('.criar').addEventListener('click', function() {
    this.classList.toggle('ativo');
  });

  document.querySelector('.mais').addEventListener('click', function(e) {
    e.stopPropagation(); // Impede que o clique se propague para o document
    this.classList.toggle('ativo');
  });
  
  document.addEventListener('click', function(e) {
    const maisBtn = document.querySelector('.mais');
    const opCriar = document.querySelector('.criar');

    // Fecha se clicar fora do botão E fora do pop-up
    if (!e.target.closest('.criacao')) {
      opCriar.classList.remove('ativo');
    }

    if (!e.target.closest('.superior-direita') && !e.target.closest('.pop-mais')) {
      maisBtn.classList.remove('ativo');
    }
  });


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