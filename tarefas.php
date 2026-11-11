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
        </header>

        <section class="welcome">
            <h2>Todas as suas tarefas</h2>
            <p>Gerencie e acompanhe tudo o que você tem para fazer.</p>

            <a href="index.php" class="back-button">
                Voltar ao início
            </a>
        </section>

        <section class="statistics">
            <div class="stat-card">
                <span>Total</span>
                <h3><?php echo $total; ?></h3>
            </div>

            <div class="stat-card">
                <span>Pendentes</span>
                <h3><?php echo $pendentes; ?></h3>
            </div>

            <div class="stat-card">
                <span>Feitas</span>
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

            <div class="filtros">
                <select id="filtro-status">
                    <option value="">Todos os status</option>
                    <option value="Pendente">Pendentes</option>
                    <option value="Concluída">Concluídas</option>
                </select>

                <select id="filtro-prioridade">
                    <option value="">Todas as prioridades</option>
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>

            <!-- Formulário de cadastro -->
            <div id="formulario-tarefa" style="display: none;">
                <h2>Nova tarefa</h2>

                <form id="form-tarefa">
                    <input type="text" name="titulo" placeholder="O que você precisa fazer?" required>

                    <textarea name="descricao" placeholder="Detalhes (opcional)"></textarea>

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
                            Salvar tarefa
                        </button>
                    </div>
                </form>
            </div>

            <!-- Formulário de edição -->
            <div id="formulario-editar" style="display: none;">
                <h2>Editar tarefa</h2>

                <form id="form-editar">
                    <input type="hidden" name="id" id="editar-id">

                    <input type="text" name="titulo" id="editar-titulo" placeholder="Título" required>

                    <textarea name="descricao" id="editar-descricao" placeholder="Descrição"></textarea>

                    <select name="prioridade" id="editar-prioridade">
                        <option value="Baixa">Baixa</option>
                        <option value="Média">Média</option>
                        <option value="Alta">Alta</option>
                    </select>

                    <input type="date" name="data_prazo" id="editar-data">

                    <select name="status" id="editar-status">
                        <option value="Pendente">Pendente</option>
                        <option value="Concluída">Concluída</option>
                    </select>

                    <div class="form-buttons">
                        <button type="button" onclick="fecharEdicao()">
                            Cancelar
                        </button>

                        <button type="submit">
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de tarefas pendentes -->
            <h2 id="titulo-pendentes">Faltam fazer</h2>

            <?php
            $contadorPendentes = 0;
            foreach ($tarefas as $tarefa):
                if ($tarefa["status"] == "Pendente"):
                    $contadorPendentes++;
            ?>

                <div class="task-card"
                    data-status="<?php echo $tarefa['status']; ?>"
                    data-prioridade="<?php echo $tarefa['prioridade']; ?>">

                    <div class="task-info">
                        <h3><?php echo htmlspecialchars($tarefa["titulo"]); ?></h3>
                        <p>
                            Prazo:
                            <?php
                            if (!empty($tarefa["data_prazo"])) {
                                echo date("d/m/Y", strtotime($tarefa["data_prazo"]));
                            } else {
                                echo "Sem prazo";
                            }
                            ?>
                        </p>
                    </div>

                    <span class="priority <?php
                        if ($tarefa["prioridade"] == "Alta") {
                            echo "high";
                        } elseif ($tarefa["prioridade"] == "Média") {
                            echo "medium";
                        } else {
                            echo "low";
                        }
                    ?>">
                        <?php echo htmlspecialchars($tarefa["prioridade"]); ?>
                    </span>

                    <div class="button">
                        <button class="btn-editar" onclick="editarTarefa(<?php echo $tarefa['id']; ?>)">
                            EDITAR
                        </button>

                        <button class="btn-excluir" onclick="excluirTarefa(<?php echo $tarefa['id']; ?>)">
                            APAGAR
                        </button>
                    </div>

                </div>

            <?php
                endif;
            endforeach;

            if ($contadorPendentes == 0):
            ?>
                <p>Nenhuma tarefa pendente por aqui!</p>
            <?php endif; ?>


            <!-- Lista de tarefas concluídas -->
            <h2 id="titulo-pendentes">Já feitas</h2>

            <?php
            $contadorConcluidas = 0;
            foreach ($tarefas as $tarefa):
                if ($tarefa["status"] == "Concluída"):
                    $contadorConcluidas++;
            ?>

                <div class="task-card"
                    data-status="<?php echo $tarefa['status']; ?>"
                    data-prioridade="<?php echo $tarefa['prioridade']; ?>">

                    <div class="task-info">
                        <h3><?php echo htmlspecialchars($tarefa["titulo"]); ?></h3>
                        <p>
                            Prazo:
                            <?php
                            if (!empty($tarefa["data_prazo"])) {
                                echo date("d/m/Y", strtotime($tarefa["data_prazo"]));
                            } else {
                                echo "Sem prazo";
                            }
                            ?>
                        </p>
                    </div>

                    <span class="priority" style="background-color: #166534; color: white;">
                        Feita
                    </span>

                        <button class="btn-excluir" onclick="excluirTarefa(<?php echo $tarefa['id']; ?>)">
                            APAGAR
                        </button>
                    </div>

                </div>

            <?php
                endif;
            endforeach;

            if ($contadorConcluidas == 0):
            ?>
                <p>Nenhuma tarefa concluída ainda.</p>
            <?php endif; ?>

        </section>

    </main>

    <script>
        // Abrir e fechar formulário de cadastro
        function abrirFormulario() {
            document.getElementById("formulario-tarefa").style.display = "block";
        }

        function fecharFormulario() {
            document.getElementById("formulario-tarefa").style.display = "none";
        }

        // Cadastrar tarefa
        document.getElementById("form-tarefa").addEventListener("submit", function(event) {
            event.preventDefault();
            const formulario = new FormData(this);

            fetch("api/tarefa.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    titulo: formulario.get("titulo"),
                    descricao: formulario.get("descricao"),
                    prioridade: formulario.get("prioridade"),
                    data_prazo: formulario.get("data_prazo")
                })
            })
            .then(resposta => resposta.json())
            .then(dados => {
                alert(dados.mensagem);
                location.reload();
            })
            .catch(erro => {
                console.error(erro);
                alert("Erro ao cadastrar tarefa!");
            });
        });

        // Carregar informações para editar
        function editarTarefa(id) {
            fetch("api/tarefa.php")
            .then(resposta => resposta.json())
            .then(tarefas => {
                const tarefa = tarefas.find(item => item.id == id);

                if (!tarefa) {
                    alert("Tarefa não encontrada!");
                    return;
                }

                document.getElementById("editar-id").value = tarefa.id;
                document.getElementById("editar-titulo").value = tarefa.titulo;
                document.getElementById("editar-descricao").value = tarefa.descricao || "";
                document.getElementById("editar-prioridade").value = tarefa.prioridade;
                document.getElementById("editar-data").value = tarefa.data_prazo || "";
                document.getElementById("editar-status").value = tarefa.status;

                document.getElementById("formulario-editar").style.display = "block";
            })
            .catch(erro => {
                console.log(erro);
                alert("Erro ao carregar tarefa!");
            });
        }

        // Fechar formulário de edição
        function fecharEdicao() {
            document.getElementById("formulario-editar").style.display = "none";
        }

        // Salvar alterações da tarefa
        document.getElementById("form-editar").addEventListener("submit", function(event) {
            event.preventDefault();
            const formulario = new FormData(this);
            const id = formulario.get("id");

            fetch("api/tarefa.php?id=" + id, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    titulo: formulario.get("titulo"),
                    descricao: formulario.get("descricao"),
                    prioridade: formulario.get("prioridade"),
                    data_prazo: formulario.get("data_prazo"),
                    status: formulario.get("status")
                })
            })
            .then(resposta => resposta.json())
            .then(dados => {
                alert(dados.mensagem);
                location.reload();
            })
            .catch(erro => {
                console.error(erro);
                alert("Erro ao editar tarefa!");
            });
        });

        // Excluir tarefa
        function excluirTarefa(id) {
            if (confirm("Tem certeza que deseja apagar essa tarefa?")) {
                fetch("api/tarefa.php?id=" + id, {
                    method: "DELETE"
                })
                .then(resposta => resposta.json())
                .then(dados => {
                    alert(dados.mensagem);
                    location.reload();
                })
                .catch(erro => {
                    console.error(erro);
                    alert("Erro ao excluir tarefa!");
                });
            }
        }

        // Filtrar tarefas
        const filtroStatus = document.getElementById("filtro-status");
        const filtroPrioridade = document.getElementById("filtro-prioridade");

        function filtrarTarefas() {
            const status = filtroStatus.value;
            const prioridade = filtroPrioridade.value;
            const tarefas = document.querySelectorAll(".task-card");

            tarefas.forEach(tarefa => {
                const statusTarefa = tarefa.dataset.status;
                const prioridadeTarefa = tarefa.dataset.prioridade;

                if (
                    (status === "" || status === statusTarefa) &&
                    (prioridade === "" || prioridade === prioridadeTarefa)
                ) {
                    tarefa.style.display = "flex";
                } else {
                    tarefa.style.display = "none";
                }
            });
        }

        filtroStatus.addEventListener("change", filtrarTarefas);
        filtroPrioridade.addEventListener("change", filtrarTarefas);
    </script>

</body>
</html>