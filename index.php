<?php
require "Conexao.php";
$conexao = new Conexao();
$conexao = $conexao->conectar();

$query = "SELECT * FROM vagas";
$stmt = $conexao->prepare($query);
$stmt->execute();
$vagas = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sistema estacionamento</title>

    <style>
        .vaga{
            width: 200px;
            height: 50px;
            background-color: lightblue;
            margin-bottom: 20px;
            border: 1px solid blue;
            cursor: pointer;
        }

        .carro{
            font-size: 40px;
        }
    </style>
</head>
<body>
    <h2>Vagas</h2>

    <?foreach ($vagas as $num => $vaga) {?>
        <div class="vaga" onclick="ocuparVaga(<?=$vaga->numero_vaga?>, this)" status="<?=$vaga->ocupado?>">
            <?if ($vaga->ocupado === "ocupado") {
                echo "<span class='carro'>🚗</span> <small>($vaga->carro_estacionado)</small>";
            }?>
        </div>
    <?}?>


    <br>
    <h2>Funcionamento</h2>

    <span>Clicar em uma vaga para ocupa-la com um carro</span> <br>
    <span>Clicar em uma vaga ocupada para desocupa-la</span>

<script src="interatividade.js"></script>

</body>
</html>