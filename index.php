<?php
require_once 'classe-pessoa.php';
$p = new Pessoa("crudpdo", "localhost", "root", "");

if (isset($_POST['nome'])) {
    $nome = addslashes($_POST['nome']);
    $telefone = addslashes($_POST['telefone']);
    $email = addslashes($_POST['email']);
    $id = addslashes($_POST['id']); // Novo campo para armazenar o ID

    if (!empty($nome) && !empty($telefone) && !empty($email)) {
        if (empty($id)) {
            // Cadastro de nova pessoa
            if (!$p->cadastrarPessoa($nome, $telefone, $email)) {
                echo "Email já está cadastrado!";
            }
        } else {
            // Atualização de pessoa existente
            $p->editarPessoa($id, $nome, $telefone, $email);
        }
    } else {
        echo "Preencha todos os campos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Pessoa</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <section id="esquerda">
        <form method="post">
            <h2>Cadastrar Pessoa</h2>
            <input type="hidden" name="id" id="id">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" id="telefone">
            <label for="email">Email</label>
            <input type="text" name="email" id="email">
            <input type="submit" value="Cadastrar" id="cadastrarButton">
            <input type="submit" value="Atualizar Dados" id="atualizarButton" style="display:none;">
        </form>
    </section>
    <section id="direita">
        <table>
            <tr id="titulo">
                <td>Nome</td>
                <td>Telefone</td>
                <td>Email</td>
                <td>Ações</td>
            </tr>
            <?php
            $dados = $p->buscarDados();
            if (count($dados) > 0) {
                for ($i = 0; $i < count($dados); $i++) {
                    echo "<tr>";
                    foreach ($dados[$i] as $k => $v) {
                        if ($k != "id") {
                            echo "<td>" . $v . "</td>";
                        }
                    }
                    echo "<td><a href='#' onclick='carregarDados(" . $dados[$i]['id'] . ")'>Editar</a> <a href='excluir.php?id=" . $dados[$i]['id'] . "'>Excluir</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </section>
    <script>
        function carregarDados(id) {
            fetch('editar.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id').value = data.id;
                    document.getElementById('nome').value = data.nome;
                    document.getElementById('telefone').value = data.telefone;
                    document.getElementById('email').value = data.email;
                    document.getElementById('cadastrarButton').style.display = 'none';
                    document.getElementById('atualizarButton').style.display = 'inline';
                });
        }
    </script>
</body>
</html>
