<?php
class MeetCarFunctions {
    private $conn;
    private $pdo;

    public function __construct() {
        // conexão MySQLi
        $this->conn = new mysqli('localhost', 'root', '', 'db_meetcar');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // conexão PDO
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=db_meetcar;charset=utf8mb4', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("PDO Connection failed: " . $e->getMessage());
        }
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function getConn() {
        return $this->conn;
    }

    // busca posts com informações do usuário e tipo de post
    public function buscarPosts($userId = null) {
        $sql = "SELECT p.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user, tp.nome_tipo_post, tp.cor_fundo, tp.cor_letra,
                (SELECT COUNT(*) FROM likes_post WHERE fk_id_post = p.id_post) as likes_count,
                (SELECT COUNT(*) FROM tb_comentario WHERE fk_id_post = p.id_post) as comentarios_count,
                (SELECT EXISTS(SELECT 1 FROM likes_post WHERE fk_id_post = p.id_post AND fk_id_user = ?)) as user_liked
                FROM tb_post p
                JOIN tb_user u ON p.fk_id_user = u.id_user
                JOIN tb_tipo_post tp ON p.fk_id_tipo_post = tp.id_tipo_post
                ORDER BY p.data_post DESC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar posts: " . $e->getMessage());
            return [];
        }
    }

    public function buscarEventos($userId = null) {
        $sql = "SELECT e.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user,
                    (SELECT COUNT(*) FROM evento_user WHERE fk_id_evento = e.id_evento) as participantes_count,
                    (SELECT EXISTS(SELECT 1 FROM evento_user WHERE fk_id_evento = e.id_evento AND fk_id_user = ?)) as user_participando
                FROM tb_evento e
                JOIN tb_user u ON e.fk_id_criador = u.id_user
                ORDER BY e.data_inicio_evento ASC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar eventos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarGrupos($userId = null) {
        $sql = "SELECT g.id_grupo, g.data_criacao, g.img_grupo, g.nome_grupo, g.descricao_grupo,
                    u.nome_user, u.sobrenome_user, u.img_user,
                    tg.nome_temas, tg.cor_fundo, tg.cor_letras,
                    (SELECT COUNT(*) FROM user_grupo WHERE fk_id_grupo = g.id_grupo) as membros_count,
                    (SELECT EXISTS(SELECT 1 FROM user_grupo WHERE fk_id_grupo = g.id_grupo AND fk_id_user = ?)) as user_participando
                FROM tb_grupo g
                JOIN tb_user u ON g.fk_id_user = u.id_user
                JOIN grupo_tegru gt ON g.id_grupo = gt.fk_id_grupo
                JOIN temas_grupo tg ON gt.fk_id_temas_grupo = tg.id_temas_grupo
                ORDER BY membros_count DESC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar grupos: " . $e->getMessage());
            return [];
        }
    }

    public function buscaEventosJson($userId = null) {
        $sql = "SELECT e.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user,
                    (SELECT COUNT(*) FROM evento_user WHERE fk_id_evento = e.id_evento) as participantes_count,
                    (SELECT EXISTS(SELECT 1 FROM evento_user WHERE fk_id_evento = e.id_evento AND fk_id_user = ?)) as user_participando
                FROM tb_evento e
                JOIN tb_user u ON e.fk_id_criador = u.id_user
                ORDER BY e.data_inicio_evento ASC";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $eventosFormatados = array_map(function($evento) {
                return [
                    'id' => $evento['id_evento'],
                    'title' => $evento['nome_evento'],
                    'start' => date('Y-m-d\TH:i:s', strtotime($evento['data_inicio_evento'])),
                    'end' => !empty($evento['data_termino_evento']) ? date('Y-m-d\TH:i:s', strtotime($evento['data_termino_evento'])) : null
                ];
            }, $eventos);


            return json_encode($eventosFormatados);
        } catch (PDOException $e) {
            error_log("Erro ao buscar eventos: " . $e->getMessage());
            return json_encode([]);
        }
    }

    public function buscarEventosPorTermo($termo, $userId = null) {
        $sql = "SELECT e.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user,
                (SELECT COUNT(*) FROM evento_user WHERE fk_id_evento = e.id_evento) as participantes_count,
                (SELECT EXISTS(SELECT 1 FROM evento_user WHERE fk_id_evento = e.id_evento AND fk_id_user = ?)) as user_participando
                FROM tb_evento e
                JOIN tb_user u ON e.fk_id_criador = u.id_user
                WHERE e.nome_evento LIKE ? 
                OR e.descricao_evento LIKE ? 
                OR e.cidade_evento LIKE ?
                ORDER BY e.data_inicio_evento ASC";
        
        $termoLike = '%' . $termo . '%';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $termoLike, $termoLike, $termoLike]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar eventos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarGruposPorTermo($termo, $userId = null) {
        $sql = "SELECT g.id_grupo, g.data_criacao, g.img_grupo, g.nome_grupo, g.descricao_grupo,
                    u.nome_user, u.sobrenome_user, u.img_user,
                    tg.nome_temas, tg.cor_fundo, tg.cor_letras,
                    (SELECT COUNT(*) FROM user_grupo WHERE fk_id_grupo = g.id_grupo) as membros_count,
                    (SELECT EXISTS(SELECT 1 FROM user_grupo WHERE fk_id_grupo = g.id_grupo AND fk_id_user = ?)) as user_participando
                FROM tb_grupo g
                JOIN tb_user u ON g.fk_id_user = u.id_user
                JOIN grupo_tegru gt ON g.id_grupo = gt.fk_id_grupo
                JOIN temas_grupo tg ON gt.fk_id_temas_grupo = tg.id_temas_grupo
                WHERE g.nome_grupo LIKE ? 
                OR g.descricao_grupo LIKE ?
                ORDER BY g.nome_grupo ASC";
        
        $termoLike = '%' . $termo . '%';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $termoLike, $termoLike]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar grupos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarUsuariosPorTermo($termo) {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM tb_post WHERE fk_id_user = u.id_user) as posts_count
                FROM tb_user u
                WHERE u.nome_user LIKE ? 
                OR u.sobrenome_user LIKE ? 
                OR u.email_user LIKE ?
                ORDER BY u.nome_user";
        
        $termoLike = '%' . $termo . '%';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$termoLike, $termoLike, $termoLike]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuários: " . $e->getMessage());
            return [];
        }
    }

    public function buscarPostsPorTermo($termo, $userId = null) {
        $sql = "SELECT p.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user, tp.nome_tipo_post, tp.cor_fundo, tp.cor_letra,
                (SELECT COUNT(*) FROM likes_post WHERE fk_id_post = p.id_post) as likes_count,
                (SELECT COUNT(*) FROM tb_comentario WHERE fk_id_post = p.id_post) as comentarios_count,
                (SELECT EXISTS(SELECT 1 FROM likes_post WHERE fk_id_post = p.id_post AND fk_id_user = ?)) as user_liked
                FROM tb_post p
                JOIN tb_user u ON p.fk_id_user = u.id_user
                JOIN tb_tipo_post tp ON p.fk_id_tipo_post = tp.id_tipo_post
                WHERE p.titulo_post LIKE ? 
                OR p.texto_post LIKE ?
                ORDER BY p.data_post DESC";
        
        $termoLike = '%' . $termo . '%';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $termoLike, $termoLike]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar posts: " . $e->getMessage());
            return [];
        }
    }

    // busca opções para formulários (tipos de post e temas de grupo)
    public function buscarOpcoesFormularios() {
        try {
            // busca tipos de post
            $stmt = $this->pdo->query("SELECT * FROM tb_tipo_post");
            $tiposPost = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // busca temas de grupo
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

    public function buscarPostsPorGrupo($groupId, $userId = null) {
        $sql = "SELECT p.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user, tp.nome_tipo_post, tp.cor_fundo, tp.cor_letra,
                    (SELECT COUNT(*) FROM likes_post WHERE fk_id_post = p.id_post) as likes_count,
                    (SELECT COUNT(*) FROM tb_comentario WHERE fk_id_post = p.id_post) as comentarios_count,
                    (SELECT EXISTS(SELECT 1 FROM likes_post WHERE fk_id_post = p.id_post AND fk_id_user = ?)) as user_liked
                FROM tb_post p
                JOIN tb_user u ON p.fk_id_user = u.id_user
                JOIN tb_tipo_post tp ON p.fk_id_tipo_post = tp.id_tipo_post
                WHERE p.fk_id_grupo = ?
                ORDER BY p.data_post DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $groupId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar posts: " . $e->getMessage());
            return [];
        }
    }

    public function buscarEventosPorGrupo($groupId, $userId = null) {
        $sql = "SELECT e.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user,
                (SELECT COUNT(*) FROM evento_user WHERE fk_id_evento = e.id_evento) as participantes_count,
                (SELECT EXISTS(SELECT 1 FROM evento_user WHERE fk_id_evento = e.id_evento AND fk_id_user = ?)) as user_participando
                FROM tb_evento e
                JOIN tb_user u ON e.fk_id_criador = u.id_user
                WHERE fk_id_grupo = ?
                ORDER BY e.data_inicio_evento ASC";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $groupId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar eventos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarEventoPorId($userId = null, $eventId) {
        $sql = "SELECT e.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user,
                    (SELECT COUNT(*) FROM evento_user WHERE fk_id_evento = e.id_evento) as participantes_count,
                    (SELECT EXISTS(SELECT 1 FROM evento_user WHERE fk_id_evento = e.id_evento AND fk_id_user = ?)) as user_participando
                FROM tb_evento e
                JOIN tb_user u ON e.fk_id_criador = u.id_user
                WHERE e.id_evento = ?";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $eventId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar eventos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarUserPorId($userId) {
        $sql = "SELECT * FROM tb_user WHERE id_user = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar dados do usuário: " . $e->getMessage());
            return null;
        }
    }

    public function buscarGruposUsuario($userId) {
        $sql = "SELECT g.id_grupo, g.data_criacao, g.img_grupo, g.nome_grupo, g.descricao_grupo,
                    u.nome_user, u.sobrenome_user, u.img_user,
                    tg.nome_temas, tg.cor_fundo, tg.cor_letras,
                    (SELECT COUNT(*) FROM user_grupo WHERE fk_id_grupo = g.id_grupo) as membros_count,
                    (SELECT EXISTS(SELECT 1 FROM user_grupo WHERE fk_id_grupo = g.id_grupo AND fk_id_user = ?)) as user_participando
                FROM tb_grupo g
                JOIN tb_user u ON g.fk_id_user = u.id_user
                JOIN grupo_tegru gt ON g.id_grupo = gt.fk_id_grupo
                JOIN temas_grupo tg ON gt.fk_id_temas_grupo = tg.id_temas_grupo
                ORDER BY g.nome_grupo ASC
                WHERE ug.fk_id_user = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar grupos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarEventosParticipacao($userId) {
        $sql = "SELECT e.id_evento, e.nome_evento, e.img_evento, e.data_inicio_evento
                FROM evento_user eu
                JOIN tb_evento e ON eu.fk_id_evento = e.id_evento
                WHERE eu.fk_id_user = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar eventos: " . $e->getMessage());
            return [];
        }
    }

    public function buscarPostsPorUser($userId) {
        $sql = "SELECT p.*, u.id_user, u.nome_user, u.sobrenome_user, u.img_user, tp.nome_tipo_post, tp.cor_fundo, tp.cor_letra,
                (SELECT COUNT(*) FROM likes_post WHERE fk_id_post = p.id_post) as likes_count,
                (SELECT COUNT(*) FROM tb_comentario WHERE fk_id_post = p.id_post) as comentarios_count,
                (SELECT EXISTS(SELECT 1 FROM likes_post WHERE fk_id_post = p.id_post AND fk_id_user = ?)) as user_liked
                FROM tb_post p
                JOIN tb_user u ON p.fk_id_user = u.id_user
                JOIN tb_tipo_post tp ON p.fk_id_tipo_post = tp.id_tipo_post
                WHERE p.fk_id_user = ?
                ORDER BY p.data_post DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar posts: " . $e->getMessage());
            return [];
        }
    }

    public function buscarGrupoPorId($groupId, $userId = null) {
        $sql = "SELECT g.*, u.nome_user, u.sobrenome_user, u.img_user,
                tg.nome_temas, tg.cor_fundo, tg.cor_letras,
                (SELECT COUNT(*) FROM user_grupo WHERE fk_id_grupo = g.id_grupo) as membros_count,
                (SELECT EXISTS(SELECT 1 FROM user_grupo WHERE fk_id_grupo = g.id_grupo AND fk_id_user = ?)) as user_participando
            FROM tb_grupo g
            JOIN tb_user u ON g.fk_id_user = u.id_user
            JOIN grupo_tegru gt ON g.id_grupo = gt.fk_id_grupo
            JOIN temas_grupo tg ON gt.fk_id_temas_grupo = tg.id_temas_grupo
            WHERE g.id_grupo = ?";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $groupId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar grupo: " . $e->getMessage());
            return [];
        }
    }

    public function userParticipaGrupo($userId, $groupId) {
        $sql = "SELECT 1 FROM user_grupo WHERE fk_id_user = ? AND fk_id_grupo = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$userId, $groupId]);
            return $stmt->fetchColumn() !== false;
        } catch (PDOException $e) {
            error_log("Erro ao verificar participação no grupo: " . $e->getMessage());
            return false;
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
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar grupos: " . $e->getMessage());
            return [];
        }
    }

    public function __destruct() {
        // fecha a conexão MySQLi 
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
