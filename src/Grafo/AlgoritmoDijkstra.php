<?php
namespace Grafo;

class AlgoritmoDijkstra {
    
    public static $inifito = 100000;
    
    private $tabela             = array();
    private $vertices           = array();
    private $matrizAdjacencia   = array();
    private $listaAdjacencia;
    private $verticeInicial;


    public function __construct(Vertice $verticeInicial, array $vertices, array $listaAdjacencia, array $matrizAdjacencia) {
        
        $this->verticeInicial   = $verticeInicial;
        $this->vertices         = $vertices;
        $this->listaAdjacencia  = $listaAdjacencia;
        $this->matrizAdjacencia = $matrizAdjacencia;
        
        $this->tabela           = $this->gerarTabela($verticeInicial);
        
        $this->popularTabela();
        
    }
    
    private function gerarTabela(Vertice $verticeInicial){

        $tabela = array();
        
        foreach ($this->vertices as $vertice){
            $tabela[] = $this->gerarLinhaTabela($vertice, $verticeInicial);
        }
        
        return $tabela;
        
    }
    
    private function gerarLinhaTabela(Vertice $verticeAtual, Vertice $verticeInicial){
        
        $linha = array();
        
        $linha['vertice']  = $verticeAtual;
        $linha['permanente'] = false;

        if($verticeInicial === $verticeAtual){
            $linha['distancia']  = 0;
            $linha['caminho']    = $verticeInicial;
        }else{
            $linha['distancia']  = static::$inifito;
            $linha['caminho']    = null;
        }
                
        return $linha;
        
    }
    
    private function buscaListaAdjacenteNaoPermanente(Vertice $vertice){

        
        $adjacentesNaoPermanente = array();
        
        //Caso o vertice não tenha adjacentes
        if(!isset($this->listaAdjacencia[$vertice->getIndex()])){
            return $adjacentesNaoPermanente;
        }
        
        $adjacentes = $this->listaAdjacencia[$vertice->getIndex()];
        
        
        foreach ($adjacentes as $adjacente){
            foreach ($this->tabela as $item){
                if($item['vertice']->getIndex() === $adjacente->getIndex() && $item['permanente'] === false){
                    $adjacentesNaoPermanente[] = $adjacente;
                }
            }
        }
        
        return $adjacentesNaoPermanente;
                
    }

    private function buscaPesoAresta(Vertice $vertice1, Vertice $vertice2){
        return $this->matrizAdjacencia[$vertice1->getIndex()][$vertice2->getIndex()];
    }
    
    /**
     * Busca vertice menor distancia que não estão permanente
     */
    private function buscaVerticeMenorDistanciaNaoPermanente(array $vertices){

        //Pega o primeiro vertice
        $verticeMenorDistancia = reset($vertices);
        $distancia = static::$inifito;
         
        foreach ($vertices as $vertice){
            
            $tabelaVertice = $this->tabela[$vertice->getIndex()];
            
            if($tabelaVertice['permanente'] === false &&  $tabelaVertice['distancia'] < $distancia){
                $verticeMenorDistancia = $vertice;
                $distancia = $tabelaVertice['distancia'];
            }
        }
        
        return $verticeMenorDistancia;
        
    }

    private function algoritmoDijkstra(Vertice $vertice){
        
        $listaAdjacente = $this->buscaListaAdjacenteNaoPermanente($vertice);
            
        foreach ($listaAdjacente as $adjacente){

            $distanciaAdjacente = $this->tabela[$adjacente->getIndex()]['distancia'];
            $distanciaVerticeInicial = $this->tabela[$vertice->getIndex()]['distancia'];

            $pesoAresta = $this->buscaPesoAresta($vertice, $adjacente);

            $somaPeso = $distanciaVerticeInicial + $pesoAresta;

            if($distanciaAdjacente > $somaPeso){
                $this->tabela[$adjacente->getIndex()]['distancia'] = $somaPeso;
                $this->tabela[$adjacente->getIndex()]['caminho'] = $vertice;
            }
        }
        
    }
    
    private function popularTabela(){
        
        $vertices = $this->vertices;
        
        while ($vertice = $this->buscaVerticeMenorDistanciaNaoPermanente($vertices)){
            
            $this->algoritmoDijkstra($vertice);
            
            //Atualiza tabela permanente
            $this->tabela[$vertice->getIndex()]['permanente'] = true;
            
            //Remove vertice da lista de vertice
            unset($vertices[$vertice->getIndex()]);
            
        }
    }
    
    public function gerlarLinhaDistancias(){
        
        $linhaDistancia = array();
        
        foreach ($this->tabela as $linha){
            $linhaDistancia[] = $linha['distancia'];
        }
        
        return $linhaDistancia;
        
    }
    
}