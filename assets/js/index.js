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



function validaSenha (input){ 
  if (input.value != document.getElementById('txtSenha').value) {
    input.setCustomValidity('Repita a senha corretamente');
  } else {
    input.setCustomValidity('');
  }
} 
