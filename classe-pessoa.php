<?php
class Pessoa {
    private $pdo;

    // Construtor
    public function __construct($dbname, $host, $user, $password) {
        try {
            $this->pdo = new PDO("mysql:dbname=".$dbname.";host=".$host, $user, $password);
        } catch (PDOException $e) {
            echo "Erro com banco de dados: ".$e->getMessage();
            exit();
        } catch (Exception $e) {
            echo "Erro genérico: ".$e->getMessage();
            exit();
        }
    }

    // Função para buscar dados e colocar no canto direito
    public function buscarDados() {
        $res = array();
        $cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
        $res = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    // Buscar dados de uma pessoa específica
    public function buscarDadosPessoa($id) {
        $cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
        return $cmd->fetch(PDO::FETCH_ASSOC);
    }

    // Cadastrar pessoa no banco de dados
    public function cadastrarPessoa($nome, $telefone, $email) {
        // Antes de cadastrar, verificar se já está cadastrado
        $cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
        $cmd->bindValue(":e", $email);
        $cmd->execute();
        if ($cmd->rowCount() > 0) { // Email já existe
            return false;
        } else { // Não foi encontrado o email
            $cmd = $this->pdo->prepare("INSERT INTO pessoa (nome, telefone, email) VALUES(:n, :t, :e)");
            $cmd->bindValue(":n", $nome);
            $cmd->bindValue(":t", $telefone);
            $cmd->bindValue(":e", $email);
            $cmd->execute();
            return true;
        }
    }

    // Editar pessoa no banco de dados
    public function editarPessoa($id, $nome, $telefone, $email) {
        $cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
        $cmd->bindValue(":n", $nome);
        $cmd->bindValue(":t", $telefone);
        $cmd->bindValue(":e", $email);
        $cmd->bindValue(":id", $id);
        $cmd->execute();
    }

    // Excluir pessoa do banco de dados
    public function excluirPessoa($id) {
        $cmd = $this->pdo->prepare("DELETE FROM pessoa WHERE id = :id");
        $cmd->bindValue(":id", $id);
        $cmd->execute();
    }
}
?>
