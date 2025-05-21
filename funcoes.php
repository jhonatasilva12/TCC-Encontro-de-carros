<?php
class MeetCarFunctions {
    private $conn;
    private $pdo;

    public function __construct() {
        // Conexão MySQLi (para compatibilidade com seu código existente)
        $this->conn = new mysqli('localhost', 'root', '', 'db_meetcar');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Conexão PDO (para as novas funções)
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=db_meetcar;charset=utf8mb4', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("PDO Connection failed: " . $e->getMessage());
        }
    }

    // Busca posts com informações do usuário e tipo de post
    public function buscarPosts() {
        $sql = "SELECT p.*, u.nome_user, u.sobrenome_user, u.img_user, tp.nome_tipo_post, tp.cor_fundo, tp.cor_letra,
                (SELECT COUNT(*) FROM likes_post WHERE fk_id_post = p.id_post) as likes_count,
                (SELECT COUNT(*) FROM tb_comentario WHERE fk_id_post = p.id_post) as comentarios_count
                FROM tb_post p
                JOIN tb_user u ON p.fk_id_user = u.id_user
                JOIN tb_tipo_post tp ON p.fk_id_tipo_post = tp.id_tipo_post
                ORDER BY p.data_post DESC";
        
        $result = $this->conn->query($sql);
        $posts = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $posts[] = $row;
            }
        }
        
        return $posts;
    }

    // Busca eventos com informações do criador
    public function buscarEventos() {
        $sql = "SELECT e.*, u.nome_user, u.sobrenome_user, u.img_user,
                (SELECT COUNT(*) FROM evento_user WHERE fk_id_evento = e.id_evento) as participantes_count
                FROM tb_evento e
                JOIN tb_user u ON e.fk_id_criador = u.id_user
                ORDER BY e.data_inicio_evento ASC";
        
        $result = $this->conn->query($sql);
        $eventos = array();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $eventos[] = $row;
            }
        }
        
        return $eventos;
    }

    // Busca opções para formulários (tipos de post e temas de grupo)
    public function buscarOpcoesFormularios() {
        try {
            // Busca tipos de post
            $stmt = $this->pdo->query("SELECT * FROM tb_tipo_post");
            $tiposPost = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Busca temas de grupo
            $stmt = $this->pdo->query("SELECT * FROM temas_grupo");
            $temasGrupo = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'tiposPost' => $tiposPost,
                'temasGrupo' => $temasGrupo
            ];
        } catch (PDOException $e) {
            error_log("Erro ao buscar opções: " . $e->getMessage());
            return [
                'tiposPost' => [],
                'temasGrupo' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    // Formata data do evento para exibição
    public static function formatarDataEvento($dataInicio, $dataTermino = null, $horaInicio = null, $horaTermino = null) {
        $dataInicioObj = new DateTime($dataInicio);
        $dataFormatada = $dataInicioObj->format('d/m/Y');
        
        if ($horaInicio) {
            $dataFormatada .= ' às ' . $horaInicio;
        }
        
        if ($dataTermino) {
            $dataTerminoObj = new DateTime($dataTermino);
            if ($dataTerminoObj->format('Y-m-d') != $dataInicioObj->format('Y-m-d')) {
                $dataFormatada .= ' até ' . $dataTerminoObj->format('d/m/Y');
                if ($horaTermino) {
                    $dataFormatada .= ' às ' . $horaTermino;
                }
            } elseif ($horaTermino) {
                $dataFormatada .= ' - ' . $horaTermino;
            }
        }
        
        return $dataFormatada;
    }

    // Calcula tempo restante para o evento
    public static function tempoParaEvento($dataEvento) {
        $agora = new DateTime();
        $dataEventoObj = new DateTime($dataEvento);
        
        if ($dataEventoObj < $agora) {
            return 'Evento encerrado';
        }
        
        $diferenca = $agora->diff($dataEventoObj);
        
        if ($diferenca->y > 0) {
            return $diferenca->y == 1 ? 'em 1 ano' : 'em ' . $diferenca->y . ' anos';
        } elseif ($diferenca->m > 0) {
            return $diferenca->m == 1 ? 'em 1 mês' : 'em ' . $diferenca->m . ' meses';
        } elseif ($diferenca->d > 0) {
            return $diferenca->d == 1 ? 'amanhã' : 'em ' . $diferenca->d . ' dias';
        } elseif ($diferenca->h > 0) {
            return $diferenca->h == 1 ? 'em 1 hora' : 'em ' . $diferenca->h . ' horas';
        } else {
            return 'em breve';
        }
    }

    // Calcula tempo decorrido desde a postagem
    public static function tempoDecorrido($dataPost) {
        $agora = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $dataPost = new DateTime($dataPost, new DateTimeZone('America/Sao_Paulo'));
        $diferenca = $agora->diff($dataPost);
        
        if ($diferenca->y > 0) {
            return $diferenca->y == 1 ? 'há 1 ano' : 'há ' . $diferenca->y . ' anos';
        } elseif ($diferenca->m > 0) {
            return $diferenca->m == 1 ? 'há 1 mês' : 'há ' . $diferenca->m . ' meses';
        } elseif ($diferenca->d > 0) {
            return $diferenca->d == 1 ? 'há 1 dia' : 'há ' . $diferenca->d . ' dias';
        } elseif ($diferenca->h > 0) {
            return $diferenca->h == 1 ? 'há 1 hora' : 'há ' . $diferenca->h . ' horas';
        } elseif ($diferenca->i > 0) {
            return $diferenca->i == 1 ? 'há 1 minuto' : 'há ' . $diferenca->i . ' minutos';
        } else {
            return 'há poucos segundos';
        }
    }

    public function __destruct() {
        // Fecha a conexão MySQLi
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>