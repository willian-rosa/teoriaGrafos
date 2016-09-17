<?php
include 'vendor/autoload.php';

$verteces = '';
$arestas = '';
$digrafo = false;

$grafo = null;

//if(isset($_POST['verteces'], $_POST['arestas'])){

    $verteces   = $_POST['verteces'];
    $arestas    = $_POST['arestas'];

    $digrafo    = !!$_POST['digrafo'];
    
    $verteces = '{1,2,3,4,5}';
    $arestas = '{(1,2,31)(1,5,32)(2,3,33)(2,5,34)(2,4,35)(3,4,36)(4,5,37)}';
    $digrafo = true;
    


    $grafo = new Grafo\Grafo($verteces, $arestas, $digrafo);

//}

?>

<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
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
        </style>
        <title>Grafos</title>
        
    </head>
    <body>

      <div class="content">
          
        <div class="well">
            <h1>Grafos</h1>
            
            <form action="#" method="post">
                  
                <div class="well">
                    <div class="form-group">
                        <label for="vertece">Verteces</label>
                        <input id="vertece" type="text" class="form-control" name="verteces" placeholder="{1,2,3,4,5}" value="<?php echo $verteces;?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="arestas">Arestas</label>
                        <input id="arestas" type="text" class="form-control" name="arestas" placeholder="{(1,2,15)(1,5,20)(2,3,5)(2,5,10)(2,4,32)(3,4,25)(4,5,8)}" value="<?php echo $arestas;?>">
                    </div>
                    <div class="form-group ">
                        <label for="digrafo">Digrafo</label>
                        <input id="digrafo" type="checkbox" class="col-md-1 checkbox" name="digrafo" value="1" <?php if($digrafo){echo 'checked';} ?>>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Enviar</button>
                    </div>
                    
                </div>
                  
            </form>
            
            <?php
            if($grafo){
                ?>
                <div class="well">

                    <h2>Lista de Adjacencia</h2>
                    <div class="well">
                        <table>
                            <?php
                            $listaAdjacencia =  $grafo->gerarListaDeAdjacencia();
                            foreach ($listaAdjacencia as $i => $verteces){
                                $vertece = $grafo->buscaVertecePorIndex($i);
                                ?>
                                <tr>
                                    <td class="cel la-cel" ><?php echo $vertece->getNome();?></td>
                                    <?php
                                    foreach ($verteces as $vertece){
                                        ?><td class="cel"><?php echo $vertece->getNome();?></td><?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>

                    <h2>Matriz de Adjacência</h2>
                    <div class="well">
                        <table>
                            <?php
                            $matrizAdjacencia =  $grafo->gerarMatrizDeAdjacencia();
                            ?>
                            <tr>
                                <td></td>
                                <?php
                                foreach ($matrizAdjacencia as $i => $linha){
                                    $vertece = $grafo->buscaVertecePorIndex($i);
                                    ?>
                                    <td class="cel col-ma"><?php echo $vertece->getNome();?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            
                            <?php
                            foreach ($matrizAdjacencia as $i => $linha){
                                $vertece = $grafo->buscaVertecePorIndex($i);
                                ?>
                                <tr>
                                    <td class="cel row-ma"><?php echo $vertece->getNome();?></td>
                                    <?php
                                    foreach ($linha as $valor){
                                        ?>
                                        <td class="cel"><?php echo $valor; ?> </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    
                    
                    <h2>Matriz de Incidência</h2>
                    <div class="well">
                        <table>
                            <?php
                            $matrizDeIncidencia =  $grafo->gerarMatrizDeIncidencia();
                            ?>
                            <tr>
                                <td></td>
                                <?php
                                foreach ($matrizDeIncidencia[0] as $j => $valor){
                                        $aresta = $grafo->buscaArestaPorIndex($j);
                                        ?>
                                        <td class="cel col-mi"><?php echo $aresta->getNome();?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            
                            <?php
                            foreach ($matrizDeIncidencia as $i => $linha){
                                $vertece = $grafo->buscaVertecePorIndex($i);
                                ?>
                                <tr>
                                    <td class="cel row-ma"><?php echo $vertece->getNome();?></td>
                                    <?php
                                    foreach ($linha as $valor){
                                        ?>
                                        <td class="cel"><?php echo $valor; ?> </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>

                    
                    <h2>Listas de Arestas</h2>
                    <div class="well">
                        <table>
                            <?php
                            $listaDeArestas =  $grafo->gerarListaAresta();
                            foreach ($listaDeArestas as $conjunto){
                                ?>
                                <tr>
                                    <?php
                                    foreach ($conjunto as $vertece){
                                        ?><td class="cel"><?php echo $vertece->getNome();?></td><?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                             ?><?php
                            ?>
                        </table>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
          
      </div>
    </body>
</html>


    
<?php
//
//
//$strVerteces   = '{1,2,3,4,5}';
//$strArestas    = '{(1,2)(1,5)(2,3)(2,5)(2,4)(3,4)(4,5)}';
//
//$digrafo = true;
//
//
//$grafo = new Grafo\Grafo($strVerteces, $strArestas, $digrafo);
////$listaAdjacencia =  $grafo->gerarListaDeAdjacencia();
////$matrizAdjacencia =  $grafo->gerarMatrizDeAdjacencia();
////$matrizDeIncidencia = $grafo->gerarMatrizDeIncidencia();
//
//$listaDeArestas = $grafo->gerarListaAresta();
//
//!ddd($listaDeArestas);
