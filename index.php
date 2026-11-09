<?php
$url = "http://localhost/API-PHP/api/tarefa.php";

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$resposta = curl_exec($curl);

curl_close($curl);

$tarefas = json_decode($resposta, true);

if (!is_array($tarefas)) {
    $tarefas = [];
}

$total = count($tarefas);
$pendentes = 0;
$concluidas = 0;
foreach ($tarefas as $tarefa) {

    if ($tarefa["status"] == "Pendente") {
        $pendentes++;
    } else {
        $concluidas++;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefafa - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <main class="container">

        <header>
            <h1>Tarefafa</h1>
            <span class="user">Meu espaço</span>
        </header>

        <section class="welcome">
            <h2>Minhas tarefas</h2>
            <p>Organize seu dia e acompanhe seu progresso.</p>
        </section>

        <section class="statistics">
    <div class="stat-card">
        <span>Total de tarefas</span>
        <h3><?php echo $total; ?></h3>
    </div>

    <div class="stat-card">
        <span>Pendentes</span>
        <h3><?php echo $pendentes; ?></h3>
    </div>

    <div class="stat-card">
        <span>Concluídas</span>
        <h3><?php echo $concluidas; ?></h3>
    </div>
</section>

        <section class="tasks">

            <div class="tasks-header">
                <h2>Lista de tarefas</h2>
                <button class="add-button" onclick="abrirFormulario()">
                    + Nova tarefa
                </button>
            </div>
            <div id="formulario-tarefa" style="display: none;">

    <h2>Nova tarefa</h2>

    <form id="form-tarefa">

        <input type="text" name="titulo" placeholder="Título da tarefa" required>

        <textarea name="descricao" placeholder="Descrição da tarefa"></textarea>

        <select name="prioridade">
            <option value="Baixa">Baixa</option>
            <option value="Média" selected>Média</option>
            <option value="Alta">Alta</option>
        </select>

        <input type="date" name="data_prazo">

        <div class="form-buttons">

    <button type="button" onclick="fecharFormulario()">
        Cancelar
    </button>

    <button type="submit">
        Adicionar tarefa
    </button>

</div>

    </form>

</div>

<?php foreach ($tarefas as $tarefa): ?>

    <div class="task-card">
        <div class="task-info">
            <h3><?php echo htmlspecialchars($tarefa["titulo"]); ?></h3>

            <p>
                Prazo:
                <?php echo date("d/m/Y", strtotime($tarefa["data_prazo"])); ?>
            </p>
        </div>

       <span class="priority <?php
            if ($tarefa["prioridade"] == "Alta") {
                echo "high";
            } elseif ($tarefa["prioridade"] == "Média") {
                echo "medium";
            }           
            else {
                echo "low";
                }
?>">
    <?php echo htmlspecialchars($tarefa["prioridade"]); ?>
        </span>
        <div class="button">
    <button class="btn-editar"
        onclick="editarTarefa(ID_DA_TAREFA)">
        EDITAR
    </button>

    <button class="btn-excluir"
        onclick="excluirTarefa(ID_DA_TAREFA)">
        DELETAR
    </button>
</div>
    </div>

<?php endforeach; ?>

        </section>

    </main>

<script>

    function abrirFormulario() {
        document.getElementById("formulario-tarefa").style.display = "block";
    }

    function fecharFormulario() {
        document.getElementById("formulario-tarefa").style.display = "none";
    }

    document.getElementById("form-tarefa").addEventListener("submit", function(event) {

    event.preventDefault();

    const formulario = new FormData(this);

    const titulo = formulario.get("titulo");
    const descricao = formulario.get("descricao");
    const prioridade = formulario.get("prioridade");
    const data_prazo = formulario.get("data_prazo");

    fetch("api/tarefa.php", {
    method: "POST",

    headers: {
        "Content-Type": "application/json"
    },

    body: JSON.stringify({
        titulo: titulo,
        descricao: descricao,
        prioridade: prioridade,
        data_prazo: data_prazo
    })

})

.then(resposta => resposta.json())

.then(dados => {

    alert(dados.mensagem);

    location.reload();

})

.catch(erro => {

    console.error("Erro:", erro);

    alert("Erro ao cadastrar tarefa!");

});

});

</script>
</body>
</html>