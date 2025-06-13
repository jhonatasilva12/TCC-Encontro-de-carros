const body = document.body;
const postModal = document.getElementById("form-post");
const eventoModal = document.getElementById("form-evento");
const grupoModal = document.getElementById("form-grupo");
const opCriar = document.querySelector(".criar");
const search = document.querySelector(".search-box");
const tabs = document.querySelectorAll('.tab');
const tabContent = document.querySelectorAll('.tab-content');
const post = document.querySelectorAll('.post');
const evento = document.querySelectorAll('.evento');
const url = window.location.pathname;

if (url.includes("search.php")) {
  document.querySelector('.tabs').style.display = "flex";
}

if (search) {
  search.addEventListener("mouseover", function (e) {
    this.classList.add("ativo")
  });
}

if (opCriar) {
  opCriar.addEventListener("click", function (e) {
    //click = ativo
    e.stopPropagation(); // Impede que o clique se propague para o document
    this.classList.toggle("ativo");
  });

  document.querySelector(".criar-post").addEventListener("click", function (e) {
    //click = modal ativo
    e.stopPropagation(); // Impede que o clique se propague para o document
    postModal.classList.toggle("ativo");
    opCriar.classList.remove("ativo");
    document.body.style.overflowY = "hidden";
    carregarOpcoesFormularios();
  });

  document.querySelector(".criar-evento").addEventListener("click", function (e) {
    //click = modal ativo
    e.stopPropagation(); // Impede que o clique se propague para o document
    eventoModal.classList.toggle("ativo");
    opCriar.classList.remove("ativo");
    document.body.style.overflowY = "hidden";
    carregarOpcoesFormularios();
  });

  document.querySelector(".criar-grupo").addEventListener("click", function (e) {
    //click = modal ativo
    e.stopPropagation(); // Impede que o clique se propague para o document
    grupoModal.classList.toggle("ativo");
    opCriar.classList.remove("ativo");
    document.body.style.overflowY = "hidden";
    carregarOpcoesFormularios();
  });
}

window.addEventListener("scroll", function() {
  if(document.querySelector(".separa-sub").getBoundingClientRect().bottom >= 0) {
    document.querySelector(".mini-sub").classList.remove("ativo");
  } else {
    document.querySelector(".mini-sub").classList.add("ativo");
  }
})

if (tabs) {
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      // Primeiro ele remove a classe das outras tabs
      tabs.forEach(t => t.classList.remove('ativo'));
      tabContent.forEach(c => c.classList.remove('ativo'));

      tab.classList.add('ativo');
      const tabId = tab.getAttribute('data-tab');
      document.getElementById(`tab-${tabId}`).classList.add('ativo');
    });
  });
}

document.addEventListener('keydown', (e) => {
  if (e.ctrlKey && e.altKey && e.key.toLowerCase() === 'd') {
    const userBox = document.querySelector('.user-box');
    userBox.classList.toggle('danca');
  }
});

document.querySelectorAll(".mais").forEach((botao) => {
  botao.addEventListener("click", function (e) {
    this.classList.toggle("ativo");
  });
});

document.querySelectorAll(".p-img").forEach(img => {
    img.addEventListener('click', function() {
        const fullImg = document.querySelector(".img-full");
        const fundo = document.querySelector(".fundo-img");
        
        fullImg.src = this.src; // pega a imagem clicada
        panzoom(fullImg);
        
        
        fundo.style.display = "flex"; // ativa o modal (faz ele aparecer)
        document.body.style.overflowY = "hidden";
    });
});

document.addEventListener("click", function (e) { // Serve para que, ao clicar fora, torne os ativos em inativos (em relação a clicado ou não)
  if (opCriar) {
    if (!e.target.closest(".criacao")) {
      opCriar.classList.remove("ativo");
    }

    if (!e.target.closest(".form-modal") || e.target.closest(".fecha-modal")) {
      [eventoModal, postModal, grupoModal].forEach((modal) => {
        modal.classList.remove("ativo");
      });
      document.querySelectorAll(".modal-container").forEach((form) => form.reset());
      document.body.style.overflowY = "scroll";
    }
  }

  if (!e.target.closest(".search-box")) {
    search.classList.remove("ativo");
    search.reset();
  }

  if(e.target === document.querySelector(".fundo-img") || e.target === document.querySelector(".fecha-img")) {
    document.querySelector(".fundo-img").style.display = 'none';
    document.body.style.overflowY = "scroll";
  }

  if (!e.target.closest(".superior-direita") && !e.target.closest(".pop-mais")) {
    document.querySelectorAll(".mais").forEach((btn) => {
      btn.classList.remove("ativo");
    });
  }
});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// configuração única para todos os previews
function setupImagePreviews() {
    const fileUploads = {
        'group-image': { preview: 'previewGroup', container: 'groupPreview' },
        'post-image': { preview: 'postImage', container: 'imagePreview' },
        'event-image': { preview: 'previewEvent', container: 'eventPreview' },
        'event-video': { maxSize: 50 * 1024 * 1024 }, //50mb para eventos
        'post-video': { maxSize: 30 * 1024 * 1024 } //30mb pra posts
    };

    Object.keys(fileUploads).forEach(inputId => {
        const config = fileUploads[inputId];
        const input = document.getElementById(inputId);

        if (input) {
            input.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (config.maxSize && file && file.type.startsWith("video/")) {
                    if (file.size > config.maxSize) {
                        alert(`O vídeo é muito grande! O limite para ${inputId.includes("event") ? "eventos" : "posts"} é ${config.maxSize / (1024 * 1024)}MB.`);
                        this.value = "";
                        return;
                    }
                }

                if (config.preview && config.container && file && file.type.startsWith("image/")) {
                    const preview = document.getElementById(config.preview);
                    const container = document.getElementById(config.container);

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        container.style.display = "flex";
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', setupImagePreviews);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
document.querySelectorAll('.delete-content').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        
        const contentType = this.getAttribute('data-type');
        const contentId = this.getAttribute('data-id');
        
        if (confirm(`Tem certeza que deseja excluir este ${contentType}?`)) {
            try {
                const response = await fetch(`banco/deletar.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        type: contentType,
                        id: contentId
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Remove o elemento da interface
                    document.querySelector(`.${contentType}[data-id="${contentId}"]`).remove();
                    
                    // Mostra mensagem de sucesso
                    showAlert(`${contentType.charAt(0).toUpperCase() + contentType.slice(1)} excluído com sucesso!`, 'success');
                } else {
                    throw new Error(data.message || 'Erro desconhecido');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert(`Erro ao excluir ${contentType}: ${error.message}`, 'error');
            }
        }
    });
});

function showAlert(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    document.body.appendChild(alert);
    
    setTimeout(() => alert.remove(), 5000);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

async function carregarOpcoesFormularios() {
  //Autoexplicativo
  try {
    console.log("Iniciando carregamento de opções...");
    const response = await fetch("banco/buscar_opcoes.php");

    if (!response.ok) {
      throw new Error(`Erro HTTP! status: ${response.status}`);
    }

    const data = await response.json();
    console.log("Dados recebidos:", data);

    if (data.status !== "success") {
      throw new Error(data.message || "Erro ao carregar opções");
    }

    // Verifica se há dados
    console.log(
      `Encontrados ${data.countTipos} tipos de post e ${data.countTemas} temas`
    );

    // Preencher tipos de post
    const selectTipoPost = document.getElementById("tipo-post");
    if (selectTipoPost) {
      selectTipoPost.innerHTML = "";

      // Option padrão
      const optionPadrao = document.createElement("option");
      optionPadrao.value = "";
      optionPadrao.textContent = "Selecione um tipo";
      optionPadrao.selected = true;
      optionPadrao.disabled = true;
      selectTipoPost.appendChild(optionPadrao);

      // Preencher com dados
      data.tiposPost.forEach((tipo) => {
        const option = document.createElement("option");
        option.value = tipo.id_tipo_post;
        option.textContent = tipo.nome_tipo_post;

        // Aplicar estilo se existir
        if (tipo.cor_fundo) {
          option.style.backgroundColor = tipo.cor_fundo;
          option.style.color = tipo.cor_letra ? "#fff" : "#000";
        }

        selectTipoPost.appendChild(option);
      });
    }

    // Preencher temas de grupo
    const selectTemaGrupo = document.getElementById("group-theme");
    if (selectTemaGrupo) {
      selectTemaGrupo.innerHTML = "";

      // Option padrão
      const optionPadrao = document.createElement("option");
      optionPadrao.value = "";
      optionPadrao.textContent = "Selecione um tema";
      optionPadrao.selected = true;
      optionPadrao.disabled = true;
      selectTemaGrupo.appendChild(optionPadrao);

      // Preencher com dados
      data.temasGrupo.forEach((tema) => {
        const option = document.createElement("option");
        option.value = tema.id_temas_grupo;
        option.textContent = tema.nome_temas;

        // Aplicar estilo se existir
        if (tema.cor_fundo) {
          option.style.backgroundColor = tema.cor_fundo;
          option.style.color = tema.cor_letras ? "#fff" : "#000";
        }

        selectTemaGrupo.appendChild(option);
      });
    }
  } catch (error) {
    console.error("Erro ao carregar opções:", error);
    // Mostrar feedback visual para o usuário
    alert(
      "Não foi possível carregar as opções. Por favor, recarregue a página."
    );
  }
}

// Chamar a função quando os modais são abertos
document
  .querySelector(".criar-post")
  ?.addEventListener("click", carregarOpcoesFormularios);
document
  .querySelector(".criar-grupo")
  ?.addEventListener("click", carregarOpcoesFormularios);

document.addEventListener("DOMContentLoaded", function () {
  if (
    document.getElementById("tipo-post") ||
    document.getElementById("group-theme")
  ) {
    carregarOpcoesFormularios();
  }

  setupMicroForms();
});

// Ainda um tanto relacionado
function setupMicroForms() {
  document.querySelectorAll(".micro-form-container").forEach((container) => {
    const btnOpen = container.querySelector(".btn-micro-form");
    const form = container.querySelector(".micro-form");
    const input = form.querySelector(".micro-input");
    const btnConfirm = form.querySelector(".btn-confirm");
    const btnCancel = form.querySelector(".btn-cancel");

    btnOpen.addEventListener("click", () => {
      form.style.display = "block";
      btnOpen.style.display = "none";
      input.focus();
    });

    btnCancel.addEventListener("click", resetMicroForm);

    btnConfirm.addEventListener("click", async () => {
      try {
        const isTipoPost = container.closest("#form-post") !== null;
        const tipo = isTipoPost ? "tipo_post" : "tema_grupo";
        const nome = input.value.trim();
        const corFundo = form.querySelector(".micro-color").value;
        const corTexto = form.querySelector(
          'input[name="cor_texto"]:checked'
        ).value;

        if (!nome) {
          throw new Error("Por favor, insira um nome");
        }

        console.log("Enviando:", { tipo, nome, corFundo, corTexto });

        const response = await fetch("banco/insert_opcoes.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            tipo: tipo,
            nome: nome,
            cor_fundo: corFundo,
            cor_texto: corTexto,
          }),
        });

        const result = await response.json();
        console.log("Resposta:", result);

        if (!result.success) {
          throw new Error(result.message);
        }

        await carregarOpcoesFormularios();
        resetMicroForm();

        const selectId = isTipoPost ? "tipo-post" : "group-theme";
        document.getElementById(selectId).value = result.id;
      } catch (error) {
        console.error("Erro completo:", error);
        alert("Erro: " + error.message);
      }
    });

    function resetMicroForm() {
      form.style.display = "none";
      btnOpen.style.display = "block";
      input.value = "";
      form.querySelector('input[name="cor_texto"][value="1"]').checked = true; // reseta para a opção padrão (texto claro)
    }
  });
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

async function handleLike(postId, button) {
    button.classList.add('processing');
    
    try {
        const response = await fetch(`./banco/insert_like_post.php?id=${postId}`);
        const data = await response.json();
        
        if (data.success) {
            const icon = button.querySelector('i');
            const count = button.querySelector('.p-count');
            
            button.classList.toggle('liked', data.liked);
            button.setAttribute('data-user-liked', data.liked ? '1' : '0');
            icon.style.color = data.liked ? '#4285f4' : '';
            count.textContent = data.total;
        }
    } catch (error) {
        console.error('Erro ao dar like:', error);
    } finally {
        button.classList.remove('processing');
    }
}

// Função para participação
async function handleParticipacao(eventoId, button) {
    button.classList.add('processing');
    
    try {
        const response = await fetch(`./banco/insert_participar_evento.php?id=${eventoId}`);
        const data = await response.json();
        
        if (data.success) {
            const icon = button.querySelector('i');
            const count = button.querySelector('.participantes-count');
            
            button.classList.toggle('inscrito', data.participando);
            button.setAttribute('data-user-participando', data.participando ? '1' : '0');
            icon.style.color = data.participando ? '#4CAF50' : '';
            count.textContent = data.total;
        }
    } catch (error) {
        console.error('Erro ao participar:', error);
    } finally {
        button.classList.remove('processing');
    }
}

// Event listeners
document.querySelectorAll('.p-vote').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        handleLike(this.getAttribute('data-post-id'), this);
    });
});

document.querySelectorAll('.e-participar').forEach(button => {
    button.addEventListener('click', function() {
        handleParticipacao(this.getAttribute('data-evento-id'), this);
    });
});

// Verificação inicial baseada nos atributos data-*
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.p-vote[data-user-liked="1"]').forEach(button => {
        button.classList.add('liked');
        button.querySelector('i').style.color = '#4285f4';
    });
    
    document.querySelectorAll('.e-participar[data-user-participando="1"]').forEach(button => {
        button.classList.add('inscrito');
        button.querySelector('i').style.color = '#4CAF50';
    });
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function atualizarTempos() {
  document.querySelectorAll(".p-tempo").forEach((elemento) => {
    const dataPost = elemento.getAttribute("data-tempo");
    // converter para formato ISO se necessário
    const dataISO = dataPost.replace(" ", "T") + "Z";
    elemento.textContent = calcularTempoDecorrido(dataISO);
  });
}

function calcularTempoDecorrido(dataString) {
  // converter data do MySQL/PostgreSQL para formato ISO
  const dataISO = dataString.replace(" ", "T") + "Z";
  const dataPost = new Date(dataISO);
  const agora = new Date();

  // verificar se a data é válida
  if (isNaN(dataPost.getTime())) {
    console.error("Data inválida:", dataString, "Convertido para:", dataISO);
    return "há algum tempo";
  }

  const diff = Math.floor((agora - dataPost) / 1000); // diferença em segundos

  if (diff < 60) return "há poucos segundos";
  if (diff < 3600) {
    const mins = Math.floor(diff / 60);
    return `há ${mins} minuto${mins !== 1 ? "s" : ""}`;
  }
  if (diff < 86400) {
    const horas = Math.floor(diff / 3600);
    return `há ${horas} hora${horas !== 1 ? "s" : ""}`;
  }
  if (diff < 2592000) {
    const dias = Math.floor(diff / 86400);
    return `há ${dias} dia${dias !== 1 ? "s" : ""}`;
  }
  if (diff < 31536000) {
    const meses = Math.floor(diff / 2592000);
    return `há ${meses} mês${meses !== 1 ? "es" : ""}`;
  }
  const anos = Math.floor(diff / 31536000);
  return `há ${anos} ano${anos !== 1 ? "s" : ""}`;
}

if (document.getElementById('event-start-datetime')) {

document.getElementById('event-start-datetime').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const endDateInput = document.getElementById('event-end-datetime');
    
    if (endDateInput.value) {
        const endDate = new Date(endDateInput.value);
        if (endDate < startDate) {
            alert('A data de término não pode ser anterior à data de início');
            endDateInput.value = '';
        }
    }
    
    endDateInput.min = this.value;
});
}

// Validação de formulários
function setupFormValidations() {
  // Validação do formulário de post
  document
    .querySelector("#form-post form")
    ?.addEventListener("submit", function (e) {
      if (!validatePostForm()) {
        e.preventDefault();
      }
    });

  // Validação do formulário de grupo
  document
    .querySelector("#form-grupo form")
    ?.addEventListener("submit", function (e) {
      if (!validateGroupForm()) {
        e.preventDefault();
      }
    });

  // Validação do formulário de evento
  document
    .querySelector("#form-evento form")
    ?.addEventListener("submit", function (e) {
      if (!validateEventForm()) {
        e.preventDefault();
      }
    });
}

function validatePostForm() {
  const form = document.querySelector("#form-post form");
  if (!form.texto_post.value.trim()) {
    alert("O texto do post é obrigatório");
    return false;
  }
  if (!form.fk_id_tipo_post.value) {
    alert("Selecione um tipo de post");
    return false;
  }
  return true;
}

function validateGroupForm() {
  const form = document.querySelector("#form-grupo form");
  if (!form.nome_grupo.value.trim()) {
    alert("O nome do grupo é obrigatório");
    return false;
  }
  if (!form.fk_id_temas_grupo.value) {
    alert("Selecione um tema para o grupo");
    return false;
  }
  return true;
}

function validateEventForm() {
  const form = document.querySelector("#form-evento form");
  const startDate = new Date(form.data_inicio.value);
  const endDate = form.data_termino.value
    ? new Date(form.data_termino.value)
    : null;

  if (!form.nome_evento.value.trim()) {
    alert("O nome do evento é obrigatório");
    return false;
  }
  if (!form.descricao_evento.value.trim()) {
    alert("A descrição do evento é obrigatória");
    return false;
  }
  if (!form.data_inicio.value) {
    alert("A data de início é obrigatória");
    return false;
  }
  if (endDate && endDate < startDate) {
    alert("A data de término deve ser após a data de início");
    return false;
  }
  if (!form.valor_pedestre.value) {
    alert("O valor para pedestres é obrigatório");
    return false;
  }
  if (!form.valor_exposicao.value) {
    alert("O valor para exposição é obrigatório");
    return false;
  }
  return true;
}
