<?php
include 'vendor/autoload.php';

$vertices = '';
$arestas = '';
$digrafo = false;

$grafo = null;

if(isset($_POST['vertices'], $_POST['arestas'])){

    $vertices   = $_POST['vertices'];
    $arestas    = $_POST['arestas'];

    $digrafo    = !!$_POST['digrafo'];
    

    $grafo = new Grafo\Grafo($vertices, $arestas, $digrafo);

}


?>

<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="assets/bower_components/isteven-angular-multiselect/isteven-multi-select.css">
                
        <style>
            body{
                background-color: #c0d4df;
            }
            .content{
                width: 75%;
                margin: 50px auto;
            }
            .cel{
                background-color: #fff;
                border-right: 3px solid #f5f5f5;
                border-bottom: 3px solid #f5f5f5;
                height: 25px;
                text-align: center;
                width: 35px;
            }
            .cel-arvore{
                width: 150px;
            }
            .la-cel{
               background-color: #ef6522;
               width: 50px;
            }
            .col-ma{
               background-color: #ff8; 
            }
            .row-ma{
               background-color: #ef6522; 
            }
            .col-mi{
               width: 50px;
               background-color: #ff8;  
            }
            .input-interface{
                width: 80px;
            }
            .content-interface{
                float: left;
                width: 125px;
                margin-right: 15px;
                border-right: 1px solid #c0d4df;
            }
            .content-interface .btn{
                margin-top: 15px;
            }
             .clear{
                clear: both;
            }
            #painel-interface{
                display: none;
            }
            .nome-prateleira{
                text-align: center;
                font-weight: bold;
                background: #c0d4df;
            }
        </style>
        <title>Grafos</title>
        <script src="assets/three.min.js"></script>
    </head>
    <body ng-app="TeoriaGrafos">
        
        <div class="content" ng-controller="produtos">
         
        <div class="well">
            <h1>Grafos - Supermercado</h1>
            
            <div class="well">

                <div class="form-group">
                    <label for="vertice">Produto</label>
                    <input id="vertice" type="text" class="form-control" name="produto" placeholder="Arroz" ng-model="novoProduto.nome">
                </div>
                
                <div class="form-group">
                    <label for="vertice">Não pode estar na mesma gôndolas dos produtos:</label>
                    <div isteven-multi-select input-model="produtos" output-model="novoProduto.adjacentes" button-label="nome"
                        item-label="nome" tick-property="ticked"></div>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-success" ng-click="cadastrarProduto(novoProduto)">Enviar</button>
                </div>
            </div>
            
            <div class="well">
                <div class="row" style="margin-left: 10px;">
                   
                    <ul class="list-group col-md-4" ng-repeat="(key, prateleira) in prateleiras">
                        <li class="list-group-item nome-prateleira">Prateleira {{key+1}}</li>
                        <li class="list-group-item" ng-repeat="vertice in prateleira.vertices">{{vertice}}</li>
                    </ul>

                </div>
                
            </div>
        </div>
      </div>
        <script src="assets/bower_components/angular/angular.min.js"></script>
        <script src="assets/controller.js"></script>
        <script src="assets/bower_components/isteven-angular-multiselect/isteven-multi-select.js"></script>      
    </body>
</html>