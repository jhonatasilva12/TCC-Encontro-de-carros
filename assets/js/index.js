document.getElementById('criar').addEventListener('click', function() {
    this.classList.toggle('ativo');
  });

  document.getElementById('mais').addEventListener('click', function() {
    this.classList.toggle('ativo');
  });



function validaSenha (input){ 
  if (input.value != document.getElementById('txtSenha').value) {
    input.setCustomValidity('Repita a senha corretamente');
  } else {
    input.setCustomValidity('');
  }
} 
