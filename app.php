<?php

class Dashboard {

    public $data_inicio;
    public $data_fim;
    public $numeroVendas;
    public $totalVendas;

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }
}

// conecao banco

class Conexao {
    
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar(){
        try {

            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass"
            );

            // $conexao->exec('set charset set utf8');

            return $conexao;

        } catch (PDOException $e) {
            echo '<p>' . $e->getMessage() . '</p>';
        }
    }
}

class Bd {
    private $conexao;
    private $dashboard;

    public function __construct( Conexao $conexao, Dashboard $dashboard)
    {
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
    }

    public function get_venda() {
        $query = 'select 
                    count(*) as numero_vendas
                from
                    tb_vendas
                where
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;
    }

    public function gettotalvendas() {
        $query = 'select 
                    sum(total) as total_vendas
                from
                    tb_vendas
                where
                    data_venda between :data_inicio and :data_fim';

        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue(':data_inicio', $this->dashboard->__get('data_inicio'));
        $stmt->bindValue(':data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;
    }
}

// LOGICA
$dashboard = new Dashboard();

$conexao = new Conexao();

$competencia = explode('-', $_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];

$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


$dashboard->__set('data_inicio', $ano. '/' .$mes. '/' . '01');
$dashboard->__set('data_fim', $ano. '-' .$mes. '-' .$dias_do_mes);


$bd = new Bd($conexao, $dashboard);

$dashboard->__set('numeroVendas', $bd->get_venda());
$dashboard->__set('totalVendas', $bd->gettotalvendas());

// $numeroVendas = $dashboard->__get('numeroVendas');
// $valortotal = $dashboard->__get('totalVendas');

echo json_encode($dashboard);

// print_r('numero de vendas ' . $numeroVendas . ', ' . 'valor total ' . $valortotal);

?>
