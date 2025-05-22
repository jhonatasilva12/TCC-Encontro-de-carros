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
    setupImagePreviews(); // atualiza os previews quando o modal abre
});

document.querySelector('.criar-evento').addEventListener('click', function(e) { //click = modal ativo
    e.stopPropagation(); // Impede que o clique se propague para o document
    eventoModal.classList.toggle('ativo');
    opCriar.classList.remove('ativo');
    setupImagePreviews(); // atualiza os previews quando o modal abre
});

document.querySelector('.criar-grupo').addEventListener('click', function(e) { //click = modal ativo
    e.stopPropagation(); // Impede que o clique se propague para o document
    grupoModal.classList.toggle('ativo');
    opCriar.classList.remove('ativo');
    setupImagePreviews(); // atualiza os previews quando o modal abre
});

document.querySelector('.mais').addEventListener('click', function(e) { //click = ativo
    e.stopPropagation(); // Impede que o clique se propague para o document
    this.classList.toggle('ativo');
});

document.addEventListener('click', function(e) { //serve para que, ao clicar fora, torne os ativos em inativos (em relação a clicado ou não)

    if (!e.target.closest('.criacao')) {
        opCriar.classList.remove('ativo');
    }

    if (!e.target.closest('.form-modal') || e.target.closest('.fecha-modal')) {
            [eventoModal, postModal, grupoModal].forEach(modal => {
                modal.classList.remove('ativo');
            });
            document.querySelectorAll('form').forEach(form => form.reset());
        }

    if (!e.target.closest('.superior-direita') && !e.target.closest('.pop-mais')) {
        maisBtn.classList.remove('ativo');
    }
});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*function setupImagePreviews() {
    document.querySelectorAll('input[type="file"][accept="image/*"]').forEach(input => {
        const container = input.closest('.form-group');
        if (!container) return;
        
        const preview = container.querySelector('img');
        if (!preview) return;
        
        // Remove event listeners antigos para evitar duplicação
        input.removeEventListener('change', handleImageUpload);
        input.addEventListener('change', handleImageUpload);
        
        function handleImageUpload(e) {
            if (this.files && this.files[0]) {
                // Verifica tamanho do arquivo (opcional)
                if (this.files[0].size > 5 * 1024 * 1024) { // 5MB
                    alert('A imagem deve ter menos de 5MB');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.add('has-image');
                };
                reader.readAsDataURL(this.files[0]);
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setupImagePreviews();
});*/

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  // Função para carregar opções nos formulários
async function carregarOpcoesFormularios() {
    console.log('Iniciando carregamento de opções...');
    
    try {
        const response = await fetch('banco/buscar_opcoes.php');
        
        if (!response.ok) {
            throw new Error(`Erro HTTP! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Dados recebidos:', data);
        
        // Popula tipos de post
        const selectTipoPost = document.getElementById('tipo-post');
        if (selectTipoPost) {
            selectTipoPost.innerHTML = ''; // Limpa options existentes
            
            // Option padrão
            const optionPadrao = document.createElement('option');
            optionPadrao.value = '';
            optionPadrao.textContent = 'Selecione um tipo';
            optionPadrao.selected = true;
            optionPadrao.disabled = true;
            selectTipoPost.appendChild(optionPadrao);
            
            // Popula com dados
            data.tiposPost.forEach(tipo => {
                const option = document.createElement('option');
                option.value = tipo.id_tipo_post;
                option.textContent = tipo.nome_tipo_post;
                
                // Estilo se existir
                if (tipo.cor_fundo) {
                    option.style.backgroundColor = tipo.cor_fundo;
                    option.style.color = tipo.cor_letra ? '#fff' : '#000';
                }
                
                selectTipoPost.appendChild(option);
            });
        }
        
        // Popula temas de grupo
        const selectTemaGrupo = document.getElementById('group-theme');
        if (selectTemaGrupo) {
            selectTemaGrupo.innerHTML = '';
            
            // Option padrão
            const optionPadrao = document.createElement('option');
            optionPadrao.value = '';
            optionPadrao.textContent = 'Selecione um tema';
            optionPadrao.selected = true;
            optionPadrao.disabled = true;
            selectTemaGrupo.appendChild(optionPadrao);
            
            // Popula com dados
            data.temasGrupo.forEach(tema => {
                const option = document.createElement('option');
                option.value = tema.id_temas_grupo;
                option.textContent = tema.nome_temas;
                
                // Estilo se existir
                if (tema.cor_fundo) {
                    option.style.backgroundColor = tema.cor_fundo;
                    option.style.color = tema.cor_letras ? '#fff' : '#000';
                }
                
                selectTemaGrupo.appendChild(option);
            });
        }
        
    } catch (error) {
        console.error('Erro ao carregar opções:', error);
        // Pode adicionar um alerta visual para o usuário aqui
    }
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