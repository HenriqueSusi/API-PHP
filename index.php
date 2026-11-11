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

$progresso = 0;
if ($total > 0) {
    $progresso = ($concluidas / $total) * 100;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefafa - Minhas Tarefas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <main class="container">

        <header>
            <h1>Tarefafa</h1>
        </header>

        <section class="welcome">
            <h2>E aí! Tudo bem por aqui?</h2>
            <p>Dê uma olhada nas suas tarefas e no quanto já avançou.</p>
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
            <h2>Progresso geral</h2>
            <h3><?php echo round($progresso); ?>%</h3>

            <div class="barra-progresso">
                <div class="progresso" style="width: <?php echo $progresso; ?>%;"></div>
            </div>
        </section>

        <section class="tasks">
            <div class="tasks-header">
                <h2>Próximas para fazer</h2>
                <a href="tarefas.php" class="add-button">Ver todas</a>
            </div>

            <?php
            $contador = 0;
            foreach ($tarefas as $tarefa):
                if ($tarefa["status"] == "Pendente" && $contador < 5):
                    $contador++;
            ?>

                <div class="task-card">
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
                </div>

            <?php 
                endif;
            endforeach; 

            if ($contador == 0):
            ?>
                <p>Nenhuma tarefa pendente por enquanto!</p>
            <?php endif; ?>

        </section>

    </main>

</body>
</html>