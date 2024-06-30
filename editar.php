<?php
require_once 'classe-pessoa.php';
$p = new Pessoa("crudpdo", "localhost", "root", "");

if (isset($_GET['id'])) {
    $id = addslashes($_GET['id']);
    $dados = $p->buscarDadosPessoa($id);
    echo json_encode($dados);
}
?>

