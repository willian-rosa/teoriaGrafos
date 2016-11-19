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
        </style>
        <title>Grafos</title>
        <script src="assets/three.min.js"></script>
    </head>
    <body>

      <div class="content">
          
        <div class="well">
            <h1>Grafos</h1>
            
            <form action="#" method="post">
                  
                <div class="well">
                    
                    <button onclick="showInterface()" class="btn btn-info" type="button">Desenhar</button>
                    <button onclick="preencherIlhas()" class="btn btn-info" type="button">Preencher dados com problema de ligações com as ilhas</button>
                    
                    <br>
                    
                    <div id="painel-interface">
                        <div class="form-group content-interface">
                            <label>Nome Vertice</label>
                            <input id="interface-vertice" type="text" class="form-control input-interface" placeholder="x1">
                            <button type="button" class="btn btn-success" onclick="newVertex()">+ Vertice</button>
                        </div>
                        
                        <div class="form-group content-interface">
                            <label>Valor Aresta</label>
                            <input id="interface-aresta" type="text" class="form-control input-interface" placeholder="5">
                            <button type="button" class="btn btn-success" onclick="newEdge()">+ Aresta</button>
                        </div>
                        
                        <div class="clear"></div>
                        
                        <div id="content-interface"></div>
                        
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label for="vertice">Vertices</label>
                        <input id="vertice" type="text" class="form-control" name="vertices" placeholder="{x1,x2,x3,x4,x5,x6}" value="<?php echo $vertices;?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="arestas">Arestas</label>
                        <input id="arestas" type="text" class="form-control" name="arestas" placeholder="{(x1,x2,1)(x2,x3,1)(x2,x5,1)(x3,x4,1)(x4,x5,1)(x5,x3,1)(x5,x2,1)(x5,x6,1)(x6,x1,1)}" value="<?php echo $arestas;?>">
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
                            foreach ($listaAdjacencia as $i => $vertices){
                                $vertice = $grafo->buscaVerticePorIndex($i);
                                ?>
                                <tr>
                                    <td class="cel la-cel" ><?php echo $vertice->getNome();?></td>
                                    <?php
                                    foreach ($vertices as $vertice){
                                        ?><td class="cel"><?php echo $vertice->getNome();?></td><?php
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
                                    $vertice = $grafo->buscaVerticePorIndex($i);
                                    ?>
                                    <td class="cel col-ma"><?php echo $vertice->getNome();?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            
                            <?php
                            foreach ($matrizAdjacencia as $i => $linha){
                                $vertice = $grafo->buscaVerticePorIndex($i);
                                ?>
                                <tr>
                                    <td class="cel row-ma"><?php echo $vertice->getNome();?></td>
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
                                $vertice = $grafo->buscaVerticePorIndex($i);
                                ?>
                                <tr>
                                    <td class="cel row-ma"><?php echo $vertice->getNome();?></td>
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
                                    foreach ($conjunto as $vertice){
                                        ?><td class="cel"><?php echo $vertice->getNome();?></td><?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                             ?><?php
                            ?>
                        </table>
                    </div>
                    
                    
                    
                    <h2>Matriz de Distância</h2>
                    <div class="well">
                        <table>
                            <?php
                            $matrizDistancia =  $grafo->gerarMatrizDistancia();
                            ?>
                            <tr>
                                <td></td>
                                <?php
                                foreach ($matrizDistancia as $i => $linha){
                                    $vertice = $grafo->buscaVerticePorIndex($i);
                                    ?>
                                    <td class="cel col-ma"><?php echo $vertice->getNome();?></td>
                                    <?php
                                }
                                ?>
                            </tr>
                            
                            <?php
                            foreach ($matrizDistancia as $i => $linha){
                                $vertice = $grafo->buscaVerticePorIndex($i);
                                ?>
                                <tr>
                                    <td class="cel row-ma"><?php echo $vertice->getNome();?></td>
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
                    
                    <?php
                    if($grafo->hasDigrafo()){
                        
                        $excentricidade = $grafo->gerarExcentricidade($matrizDistancia);
                        ?>
                        <h2>Saida</h2>
                        <div class="well">
                            <h3>Excentricidade</h3>
                            <table>
                                <?php
                                foreach ($excentricidade['saida'] as $i => $valor){
                                    $vertice = $grafo->buscaVerticePorIndex($i);
                                    ?>
                                    <tr>
                                        <td class="cel row-ma"><?php echo $vertice->getNome();?></td>
                                        <td class="cel"><?php echo $valor;?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <hr>
                            <div>raio(G) = <?php echo $grafo->buscarRaioSaida();?></div>
                            <div>Centro = {
                                <?php
                                foreach ($grafo->buscarCentroSaida() as $vertice){
                                    echo $vertice->getNome().',';
                                }
                                ?>
                                }
                            </div>
                        </div>
                        
                        
                        
                        <h2>Retorno</h2>
                        <div class="well">
                            <h3>Excentricidade</h3>
                            <table>
                                <?php
                                foreach ($excentricidade['retorno'] as $i => $valor){
                                    $vertice = $grafo->buscaVerticePorIndex($i);
                                    ?>
                                    <tr>
                                        <td class="cel row-ma"><?php echo $vertice->getNome();?></td>
                                        <td class="cel"><?php echo $valor;?></td>
                                    </tr>
                                    <?php
                                }
                                 ?>
                            </table>
                            <hr>
                            <div>raio(G) = <?php echo $grafo->buscarRaioRetorno();?></div>
                            <div>Centro = {
                                <?php
                                foreach ($grafo->buscarCentroRetorno() as $vertice){
                                    echo $vertice->getNome().',';
                                }
                                ?>
                                }
                            </div>
                        </div>
                        
                        
                        <?php
                        $matrizDistanciaT = $grafo->somaMatrizComMatrizTransposta($matrizDistancia);
                        $excentricidade = $grafo->gerarExcentricidade($matrizDistanciaT);
                        ?>
                        
                        <h2>Saida e Retorno</h2>
                        <div class="well">
                            <h3>Excentricidade</h3>
                            <table>
                                <?php
                                foreach ($excentricidade['saida'] as $i => $valor){
                                    $vertice = $grafo->buscaVerticePorIndex($i);
                                    ?>
                                    <tr>
                                        <td class="cel row-ma"><?php echo $vertice->getNome();?></td>
                                        <td class="cel"><?php echo $valor;?></td>
                                    </tr>
                                    <?php
                                }
                                 ?>
                            </table>
                            <hr>
                            <div>raio(G) = <?php echo $grafo->buscarRaioSaidaRetoro();?></div>
                            <div>Centro = {
                                <?php
                                foreach ($grafo->buscarCentroSaidaRetorno() as $vertice){
                                    echo $vertice->getNome().',';
                                }
                                ?>
                                }
                            </div>
                        </div>
                        
                        
                        
                        <?php
                    }else{
                         $excentricidade = $grafo->gerarExcentricidade($matrizDistancia);
                        ?>
                        <div class="well">
                            <h3>Excentricidade</h3>
                            <table>
                                <?php
                                foreach ($excentricidade['saida'] as $i => $valor){
                                    $vertice = $grafo->buscaVerticePorIndex($i);
                                    ?>
                                    <tr>
                                        <td class="cel row-ma"><?php echo $vertice->getNome();?></td>
                                        <td class="cel"><?php echo $valor;?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <hr>
                            <div>raio(G) = <?php echo $grafo->buscarRaioSaida();?></div>
                            <div>Centro = {
                                <?php
                                foreach ($grafo->buscarCentroSaida() as $vertice){
                                    echo $vertice->getNome().',';
                                }
                                ?>
                                }
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                        
                    <h2>Árvore de Cobertura</h2>
                    <div class="well">
                        <table>
                            <?php
                            $arvoreCobertura = $grafo->gerarArvoreDeCobertura();
                            foreach ($arvoreCobertura as $aresta){
                                ?>
                                <tr>
                                    <td class="cel cel-arvore">
                                        <?php echo $aresta->getNome();?> = 
                                        {<?php echo $aresta->getVertice1()->getNome().', '.$aresta->getVertice2()->getNome();?>} => 
                                        <?php echo $aresta->getPeso()   ;?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
          
      </div>
        <script src="assets/grafo.js"></script>
    </body>
</html>