<?php
include 'vendor/autoload.php';

$params = json_decode(file_get_contents('php://input'),true);

$retorno = array();

if($params){

    $vertices   = $params['vertices'];
    $arestas    = $params['arestas']?$params['arestas']:'';
    
    $grafo = new Grafo\Grafo($vertices, $arestas, false);
    
    $cores = $grafo->gerarAlgoritmoColorir();
    
    foreach ($cores as $cor){

        $corJson = array();
        $corJson['nome'] = $cor->getNome();
        
        $verticeJson = array();
        
        foreach ($cor->getVertices() as $vertice){
            $verticeJson[] = $vertice->getNome();
        }

        $corJson['vertices'] = $verticeJson;
        $retorno[] = $corJson;
    }
    
}

echo json_encode($retorno);
