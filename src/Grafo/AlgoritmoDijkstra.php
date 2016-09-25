<?php
namespace Grafo;

class AlgoritmoDijkstra {
    
    public static $inifito = 100000;
    
    private $tabela             = array();
    private $verteces           = array();
    private $matrizAdjacencia   = array();
    private $listaAdjacencia;
    private $verteceInicial;


    public function __construct(Vertece $verteceInicial, array $verteces, array $listaAdjacencia, array $matrizAdjacencia) {
        
        $this->verteceInicial   = $verteceInicial;
        $this->verteces         = $verteces;
        $this->listaAdjacencia  = $listaAdjacencia;
        $this->matrizAdjacencia = $matrizAdjacencia;
        
        $this->tabela           = $this->gerarTabela($verteceInicial);
        
        $this->popularTabela();
        
    }
    
    private function gerarTabela(Vertece $verteceInicial){

        $tabela = array();
        
        foreach ($this->verteces as $vertece){
            $tabela[] = $this->gerarLinhaTabela($vertece, $verteceInicial);
        }
        
        return $tabela;
        
    }
    
    private function gerarLinhaTabela(Vertece $verteceAtual, Vertece $verteceInicial){
        
        $linha = array();
        
        $linha['vertece']  = $verteceAtual;
        $linha['permanente'] = false;

        if($verteceInicial === $verteceAtual){
            $linha['distancia']  = 0;
            $linha['caminho']    = $verteceInicial;
        }else{
            $linha['distancia']  = static::$inifito;
            $linha['caminho']    = null;
        }
                
        return $linha;
        
    }
    
    private function buscaListaAdjacenteNaoPermanente(Vertece $vertece){

        
        $adjacentesNaoPermanente = array();
        
        //Caso o vertece não tenha adjacentes
        if(!isset($this->listaAdjacencia[$vertece->getIndex()])){
            return $adjacentesNaoPermanente;
        }
        
        $adjacentes = $this->listaAdjacencia[$vertece->getIndex()];
        
        
        foreach ($adjacentes as $adjacente){
            foreach ($this->tabela as $item){
                if($item['vertece']->getIndex() === $adjacente->getIndex() && $item['permanente'] === false){
                    $adjacentesNaoPermanente[] = $adjacente;
                }
            }
        }
        
        return $adjacentesNaoPermanente;
                
    }

    private function buscaPesoAresta(Vertece $vertece1, Vertece $vertece2){
        return $this->matrizAdjacencia[$vertece1->getIndex()][$vertece2->getIndex()];
    }
    
    /**
     * Busca vertece menor distancia que não estão permanente
     */
    private function buscaVerteceMenorDistanciaNaoPermanente(array $verteces){

        //Pega o primeiro vertece
        $verteceMenorDistancia = reset($verteces);
        $distancia = static::$inifito;
         
        foreach ($verteces as $vertece){
            
            $tabelaVertece = $this->tabela[$vertece->getIndex()];
            
            if($tabelaVertece['permanente'] === false &&  $tabelaVertece['distancia'] < $distancia){
                $verteceMenorDistancia = $vertece;
                $distancia = $tabelaVertece['distancia'];
            }
        }
        
        return $verteceMenorDistancia;
        
    }

    private function algoritmoDijkstra(Vertece $vertece){
        
        $listaAdjacente = $this->buscaListaAdjacenteNaoPermanente($vertece);
            
        foreach ($listaAdjacente as $adjacente){

            $distanciaAdjacente = $this->tabela[$adjacente->getIndex()]['distancia'];
            $distanciaVerteceInicial = $this->tabela[$vertece->getIndex()]['distancia'];

            $pesoAresta = $this->buscaPesoAresta($vertece, $adjacente);

            $somaPeso = $distanciaVerteceInicial + $pesoAresta;

            if($distanciaAdjacente > $somaPeso){
                $this->tabela[$adjacente->getIndex()]['distancia'] = $somaPeso;
                $this->tabela[$adjacente->getIndex()]['caminho'] = $vertece;
            }
        }
        
    }
    
    private function popularTabela(){
        
        $verteces = $this->verteces;
        
        while ($vertece = $this->buscaVerteceMenorDistanciaNaoPermanente($verteces)){
            
            $this->algoritmoDijkstra($vertece);
            
            //Atualiza tabela permanente
            $this->tabela[$vertece->getIndex()]['permanente'] = true;
            
            //Remove vertece da lista de vertece
            unset($verteces[$vertece->getIndex()]);
            
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