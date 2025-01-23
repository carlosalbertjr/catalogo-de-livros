<?php
require_once '../config/config.php';
require_once 'Database.php';

class Usuario {

    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function cadastrarUsuario($nome_usuario, $login, $senha, $email) {
        // Verificar se o login já está em uso
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        if ($stmt->rowCount() > 0) {
            echo "O login já está em uso!";
            return false;
        }

        // Inserir o usuário no banco de dados
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome_usuario, login, senha, email) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome_usuario, $login, password_hash($senha, PASSWORD_DEFAULT), $email]);
    }

    public function usuarioExistente($login) {
        try {
            $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE login = ?");
            $stmt->execute([$login]);
    
            // Retorna true se o login já existir no banco de dados, caso contrário, false
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Erro ao verificar usuário existente: " . $e->getMessage());
        }
    }

    public function login($login, $senha) {
        try {
            // Consulta para buscar o usuário pelo login
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
            $stmt->execute([$login]);
    
            // Verifica se o usuário foi encontrado
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if(is_string($senha) == is_string($usuario['senha'])){
                    print_r(password_verify($senha, $usuario['senha']));
                } else {
                    echo is_string($senha) . " " . is_string($usuario['senha']);
                }

                // Verifica se a senha está correta
                if (password_verify($senha, $usuario['senha'])) {
                    // Retorna os dados do usuário se o login for válido
                    return $usuario;
                } else {
                    // Senha incorreta
                    return false;
                }
            } else {
                // Usuário não encontrado
                return false;
            }
        } catch (PDOException $e) {
            die("Erro ao realizar login: " . $e->getMessage());
        }
    }    
    
    public function buscarPorId($usuarioId) {
        $stmt = $this->pdo->prepare("SELECT id, nome_usuario, login, email FROM usuarios WHERE id = ? ");
        $stmt->execute([$usuarioId]);

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function editarUsuario($id, $nome, $login, $email, $senha = null) {
        $sql = "UPDATE usuarios SET nome = ?, login = ?, email = ?";
        $params = [$nome, $login, $email];
    
        if ($senha) {
            $sql .= ", senha = ?";
            $params[] = $senha;
        }
    
        $sql .= " WHERE id = ?";
        $params[] = $id;
    
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
    public function atualizar($id, $nome, $login, $email, $senha = null) {
        // Preparar a SQL para atualização
        $sql = "UPDATE usuarios SET nome_usuario = ?, login = ?, email = ?";
        $params = [$nome, $login, $email];
    
        // Incluir a senha somente se for fornecida
        if (!empty($senha)) {
            $sql .= ", senha = ?";
            $params[] = password_hash($senha, PASSWORD_DEFAULT);
        }
    
        $sql .= " WHERE id = ?";
        $params[] = $id;
        echo $sql;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
    
}
?>
