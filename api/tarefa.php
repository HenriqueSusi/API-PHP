<?php

require_once "config.php";

header("Content-Type: application/json; charset=UTF-8");

$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo == "GET") {

    $sql = "SELECT * FROM tarefa ORDER BY id DESC";

    $comando = $conexao->prepare($sql);
    $comando->execute();
 
    $tarefas = $comando->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tarefas);

}

elseif ($metodo == "POST") {

    // Recebe os dados enviados em JSON
    $dados = json_decode(file_get_contents("php://input"), true);

    // Verifica se o título foi informado
    if (empty($dados["titulo"])) {

        http_response_code(400);

        echo json_encode([
            "erro" => "O título da tarefa é obrigatório!"
        ]);

        exit;
    }


    $sql = "INSERT INTO tarefa
            (titulo, descricao, prioridade, data_prazo)
            VALUES
            (:titulo, :descricao, :prioridade, :data_prazo)";

    $comando = $conexao->prepare($sql);


    $comando->execute([
        ":titulo" => $dados["titulo"],
        ":descricao" => $dados["descricao"] ?? null,
        ":prioridade" => $dados["prioridade"] ?? "Média",
        ":data_prazo" => $dados["data_prazo"] ?? null
    ]);

    http_response_code(201);

    echo json_encode([
        "mensagem" => "Tarefa cadastrada com sucesso!",
        "id" => $conexao->lastInsertId()
    ]);

} 

elseif ($metodo == "PUT") {

    $id = $_GET["id"] ?? null;

    $dados = json_decode(file_get_contents("php://input"), true);

    if (!$id) {

        http_response_code(400);

        echo json_encode([
            "erro" => "Informe o ID da tarefa!"
        ]);

        exit;
    }

    $verificar = $conexao->prepare(
        "SELECT id FROM tarefa WHERE id = :id"
    );

    $verificar->execute([":id" => $id]);

    if (!$verificar->fetch()) {

        http_response_code(404);

        echo json_encode([
            "erro" => "Tarefa não encontrada!"
        ]);

        exit;
    }

    $sql = "UPDATE tarefa SET
            titulo = :titulo,
            descricao = :descricao,
            prioridade = :prioridade,
            data_prazo = :data_prazo,
            status = :status
            WHERE id = :id";

    $comando = $conexao->prepare($sql);

    $comando->execute([
        ":titulo" => $dados["titulo"],
        ":descricao" => $dados["descricao"] ?? null,
        ":prioridade" => $dados["prioridade"] ?? "Média",
        ":data_prazo" => $dados["data_prazo"] ?? null,
        ":status" => $dados["status"] ?? "Pendente",
        ":id" => $id
    ]);

    echo json_encode([
        "mensagem" => "Tarefa atualizada com sucesso!"
    ]);

}

else {

    echo json_encode([
        "erro" => "Método não permitido para esta operação."
    ]);

}
?>