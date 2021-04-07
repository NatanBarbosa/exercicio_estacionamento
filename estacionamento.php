<?php
//Conexão com o banco de dados
require "Conexao.php";

class Carro{
    public $placa;

    public function __construct($placa){
        $this->placa = $placa;
    }
}

class Vaga{
    public $numero_vaga;
    public $ocupado;
    public $carro_estacionado;
    public $entrada;
    public $saida;
    public $tempo_estacionado;
    public $preco;

    public $conexao;

    public function __construct(Conexao $conexao) {
        $this->conexao = $conexao->conectar();
    }

    public function ocupaVaga($numero_vaga, $placa_carro){
        //alterando valores do objeto vaga
        $this->numero_vaga = $numero_vaga;
        $this->ocupado = "ocupado";
        $this->carro_estacionado = $placa_carro;

        //colocando informações no banco de dados
        $query = "UPDATE vagas SET ocupado = 'ocupado', carro_estacionado = :carro_estacionado, entrada = CURRENT_TIMESTAMP WHERE numero_vaga = :numero_vaga";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':carro_estacionado', $this->carro_estacionado);
        $stmt->bindValue(':numero_vaga', $this->numero_vaga);
        $stmt->execute();

        //redirecionando
        header('Location: index.php');
    }

    public function desocuparVaga($numero_vaga){
        //alterando valores do objeto vaga
        $this->numero_vaga = $numero_vaga;

        //recuperando informações do banco de dados para gerar relatório/preço da vaga
        $query = "SELECT carro_estacionado, entrada, numero_vaga, CURRENT_TIMESTAMP as saida FROM vagas WHERE numero_vaga = :numero_vaga";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':numero_vaga', $this->numero_vaga);
        $stmt->execute();
        $info_vaga = $stmt->fetch(PDO::FETCH_OBJ);

        //Calculando tempo que a vaga foi ocupada
        $datafim = $info_vaga->saida;
        $datainicio = $info_vaga->entrada;

        $date_time  = new DateTime($datainicio);
        $diff = $date_time->diff(new DateTime($datafim));
        $info_vaga->tempo_estacionado = $diff->format('%H hora(s) e %i minuto(s)');

        //Calculando o preço do estacionamento
        /*
        1° hora = 5R$
        por hora = 10R$
        */
        if($diff->h == 0){
            $info_vaga->preco = 5;
        } else {
            $info_vaga->preco = $diff->h * 10;
        }

        echo "<h2>Relatório do carro estacionado</h2>";
        echo "<br> <strong>Placa do carro estacionado:</strong> " . $info_vaga->carro_estacionado;
        echo "<br> <strong>horário de entrada:</strong> " . $info_vaga->entrada;
        echo "<br> <strong>horário de saída:</strong> " . $info_vaga->saida;
        echo "<br> <strong>Vaga ocupada:</strong> " . $info_vaga->numero_vaga;
        echo "<br> <strong>Tempo de ocupação:</strong> " . $info_vaga->tempo_estacionado;
        echo "<br> <strong>Preço:</strong> " . $info_vaga->preco . "R$";

        echo "<br><br> <a href='index.php'>← liberar vaga e voltar à home</a>";

        //removendo carro da vaga no banco de dados
        $query = "UPDATE vagas SET ocupado = 'desocupado', carro_estacionado = null, entrada = null WHERE numero_vaga = :numero_vaga";
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':numero_vaga', $numero_vaga);
        $stmt->execute();
    }
}


$conexao = new Conexao();
$vaga = new Vaga($conexao);

if($_GET['acao'] === "ocupar"){
    $vaga_ocupada = $_GET['vaga_ocupada'];
    $placa_carro = $_GET['placa'];

    $vaga->ocupaVaga($vaga_ocupada, $placa_carro);
}

if($_GET['acao'] === 'desocupar'){
    $vaga_ocupada = $_GET['vaga_ocupada'];

    $vaga->desocuparVaga($vaga_ocupada);
}