<?php
require_once 'Database.php';

class Livro {
    private $conn;
    private $table_name = "livros";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Método para cadastrar um livro
    public function cadastrarLivro($titulo, $autor, $genero, $data_lancamento, $resumo, $imagem_capa) {
        $query = "INSERT INTO " . $this->table_name . " (titulo, autor, genero, data_lancamento, resumo, imagem_capa)
                  VALUES (:titulo, :autor, :genero, :data_lancamento, :resumo, :imagem_capa)";
        $stmt = $this->conn->prepare($query);

        // Bind dos parâmetros
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':data_lancamento', $data_lancamento);
        $stmt->bindParam(':resumo', $resumo);
        $stmt->bindParam(':imagem_capa', $imagem_capa);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para buscar todos os livros
    public function listarLivros($limite, $offset) {
        $query = "SELECT id, titulo, autor, genero, data_lancamento, imagem_capa FROM " . $this->table_name . " 
                  ORDER BY criado_em DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarFavorito($idUsuario, $idLivro, $dataAdicionado) {
        $query = "INSERT INTO favoritos (id_usuario, id_livro, data_adicionado) VALUES (:id_usuario, :id_livro, :data_adicionado)";
    
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_usuario', $idUsuario);
            $stmt->bindParam(':id_livro', $idLivro);
            $stmt->bindParam(':data_adicionado', $dataAdicionado);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
    
    public function buscarLivroPorId($id) {
        $sql = "SELECT id, titulo, autor, genero, data_lancamento, resumo, imagem_capa 
                FROM livros 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function adicionarComentario($idLivro, $idUsuario, $comentario) {
        $sql = "INSERT INTO comentarios (id_livro, id_usuario, comentario, data_comentario) VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
    
        return $stmt->execute([$idLivro, $idUsuario, $comentario]);
    }
    
    public function buscarComentariosPorLivroId($idLivro) {
        $sql = "SELECT c.id,    c.comentario, c.data_comentario, c.id_usuario, u.nome_usuario AS nome_usuario 
                FROM comentarios c
                INNER JOIN usuarios u ON c.id_usuario = u.id
                WHERE c.id_livro = ?
                ORDER BY c.data_comentario DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idLivro]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function verificarProprietarioComentario($idComentario, $idUsuario) {
        $sql = "SELECT id FROM comentarios WHERE id = ? AND id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idComentario, $idUsuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    public function excluirComentario($idComentario) {
        $sql = "DELETE FROM comentarios WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idComentario]);
    }
  
}
?>
