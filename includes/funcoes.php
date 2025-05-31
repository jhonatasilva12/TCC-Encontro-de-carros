<?php
class MeetCarFunctions {
    private $conn;
    private $pdo;

    public function __construct() {
        // conexão MySQLi
        $this->conn = new mysqli('localhost', 'root', '', 'db_meetcar1');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // conexão PDO
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=db_meetcar1;charset=utf8mb4', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("PDO Connection failed: " . $e->getMessage());
        }
    }

    // busca posts com informações do usuário e tipo de post
    public function buscarPosts($userId = null) {
        $sql = "SELECT p.*, u.nome_user, u.sobrenome_user, u.img_user, tp.nome_tipo_post, tp.cor_fundo, tp.cor_letra,
                (SELECT COUNT(*) FROM likes_post WHERE fk_id_post = p.id_post) as likes_count,
                (SELECT COUNT(*) FROM tb_comentario WHERE fk_id_post = p.id_post) as comentarios_count,
                (SELECT EXISTS(SELECT 1 FROM likes_post WHERE fk_id_post = p.id_post AND fk_id_user = ?)) as user_liked
                FROM tb_post p
                JOIN tb_user u ON p.fk_id_user = u.id_user
                JOIN tb_tipo_post tp ON p.fk_id_tipo_post = tp.id_tipo_post
                ORDER BY p.data_post DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $posts = array();
        while($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        
        return $posts;
    }

    public function buscarEventos($userId = null) {
        $sql = "SELECT e.*, u.nome_user, u.sobrenome_user, u.img_user,
                (SELECT COUNT(*) FROM evento_user WHERE fk_id_evento = e.id_evento) as participantes_count,
                (SELECT EXISTS(SELECT 1 FROM evento_user WHERE fk_id_evento = e.id_evento AND fk_id_user = ?)) as user_participando
                FROM tb_evento e
                JOIN tb_user u ON e.fk_id_criador = u.id_user
                ORDER BY e.data_inicio_evento ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $eventos = array();
        while($row = $result->fetch_assoc()) {
            $eventos[] = $row;
        }
        
        return $eventos;
    }




// --- INÍCIO buscargrupo, sujeito a modificação
    public function buscarGrupos($userId = null) {
        $sql = "SELECT g.id_grupo, g.data_criacao, g.img_grupo, g.nome_grupo, g.descricao_grupo,
                       u.nome_user, u.sobrenome_user, u.img_user,
                       tg.nome_temas, tg.cor_fundo, tg.cor_letras,
                       (SELECT COUNT(*) FROM user_grupo WHERE fk_id_grupo = g.id_grupo) as membros_count,
                       (SELECT EXISTS(SELECT 1 FROM user_grupo WHERE fk_id_grupo = g.id_grupo AND fk_id_user = ?)) as user_participando
                FROM tb_grupo g
                JOIN tb_user u ON g.fk_id_user = u.id_user
                JOIN temas_grupo tg ON g.fk_id_tema_grupo = tg.id_temas_grupo
                ORDER BY g.nome_grupo ASC";

        $stmt = $this->conn->prepare($sql);

       
        if ($stmt === false) {
           
            die("Erro na preparação da consulta SQL para buscarGrupos: " . $this->conn->error . " | Query: " . $sql);
        }

        $dummyUserId = $userId ?? 0; // Se userId for null, usa 0
        $stmt->bind_param("i", $dummyUserId);

        $stmt->execute();
        $result = $stmt->get_result();

        $grupos = array();
        while($row = $result->fetch_assoc()) {
            $grupos[] = $row;
        }

        return $grupos;
    }
// --- FIM





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

    public function formatarDataEventoSimples($dataInicio, $dataTermino = null) {
        $inicio = new DateTime($dataInicio);
        $formatado = $inicio->format('d/m/Y H:i');
        
        if ($dataTermino) {
            $termino = new DateTime($dataTermino);
            if ($inicio->format('Y-m-d') === $termino->format('Y-m-d')) {
                $formatado .= ' - ' . $termino->format('H:i');
            } else {
                $formatado .= ' a ' . $termino->format('d/m/Y H:i');
            }
        }
        
        return $formatado;
    }

    public function tempoParaEvento($dataEvento) {
        $agora = new DateTime();
        $dataEvento = new DateTime($dataEvento);
        
        if ($dataEvento < $agora) {
            return 'Evento encerrado';
        }
        
        $diferenca = $agora->diff($dataEvento);
        
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

    // calcula tempo decorrido desde a postagem
    function tempoDecorrido($dataPost) {
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

    public function buscarPostPorId($id) {
        $userId = $_SESSION['user_id'] ?? null;
        
        $sql = "SELECT p.*, u.nome_user, u.sobrenome_user, u.img_user, tp.nome_tipo_post, tp.cor_fundo, tp.cor_letra,
                (SELECT COUNT(*) FROM likes_post WHERE fk_id_post = p.id_post) as likes_count,
                (SELECT COUNT(*) FROM tb_comentario WHERE fk_id_post = p.id_post) as comentarios_count,
                (SELECT EXISTS(SELECT 1 FROM likes_post WHERE fk_id_post = p.id_post AND fk_id_user = ?)) as user_liked
                FROM tb_post p
                JOIN tb_user u ON p.fk_id_user = u.id_user
                JOIN tb_tipo_post tp ON p.fk_id_tipo_post = tp.id_tipo_post
                WHERE p.id_post = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar post: " . $e->getMessage());
            return null;
        }
    }

    public function buscarComentariosPorPost($postId) {
        $sql = "SELECT c.*, u.nome_user, u.img_user
            FROM tb_comentario c
            JOIN tb_user u ON c.fk_id_user = u.id_user
            WHERE c.fk_id_post = ?
            ORDER BY c.data_comentario ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comentarios = [];
        while ($row = $result->fetch_assoc()) {
            $comentarios[] = $row;
        }
        
        return $comentarios;
    }

    public function __destruct() {
        // fecha a conexão MySQLi 
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
