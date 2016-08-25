<?php
include 'vendor/autoload.php';


$strVerteces   = '{1,2,3,4,5}';
$strArestas    = '{(1,2)(1,5)(2,3)(2,5)(2,4)(3,4)(4,5)}';

$digrafo = true;


$grafo = new Grafo\Grafo($strVerteces, $strArestas, $digrafo);
//$listaAdjacencia =  $grafo->gerarListaDeAdjacencia();
//$matrizAdjacencia =  $grafo->gerarMatrizDeAdjacencia();

$matrizDeIncidencia = $grafo->gerarMatrizDeIncidencia();

!ddd($matrizDeIncidencia);
