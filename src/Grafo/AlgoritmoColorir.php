<?php

namespace Grafo;

class AlgoritmoColorir{
    
    private $listaAdjacencia;
    private $vertices = array();
    private $cores = array();


    public function __construct($listaAdjacencia, array $vertices) {
        $this->listaAdjacencia  = $listaAdjacencia;
        $this->vertices         = $vertices;
    }
    
    /**
     * 
     * @param Vertice[] $vertices
     * @return Vertice|null
     */
    private function maiorNumeroAdjacentes($vertices){
        
        $verticeMaiorAdjacentes = null;
        
        
        foreach ($vertices as $vertice) {
            
            if(!$vertice->getCor()){
                
                if(\is_null($verticeMaiorAdjacentes)){
                    
                    $verticeMaiorAdjacentes = $vertice;
                    
                }elseif(count($verticeMaiorAdjacentes->getAdjacentes()) < count($vertice->getAdjacentes())){
                    
                    $verticeMaiorAdjacentes = $vertice;                
                    
                }
                
            }
            
        }
        
        return $verticeMaiorAdjacentes;
        
    }
    
    /**
     * 
     * @param \Grafo\Cor[] $cores
     * @param \Grafo\Cor $cor
     * @return int|null
     */
    private function buscaIndexArrayCor(array $cores, Cor $corVertice){
        
        foreach ($cores as $chave => $cor){
            if($cor->getNome() === $corVertice->getNome()){
                return $chave;
            }
        }
        
        ddd($cores, $corVertice);
            
        return null;
    }

        /**
     * 
     * @param Vertice[] $adjacentes
     * @return type
     */
    private function procurarCor(array $adjacentes){

        $cores = $this->cores;

        if($cores){
            
            foreach ($adjacentes as $adjacente){
                if($adjacente->getCor()){
                    $chaveCor = array_search($adjacente->getCor(), $this->cores);
                    //$chaveCor = $this->buscaIndexArrayCor($this->cores, $adjacente->getCor());
                    unset($cores[$chaveCor]);
                }
            }
            
            if($cores){
                return reset($cores); 
            }
            
        }

        return null;

    }
    
    private function criarCor(){
        
        $nomeCor = 'cor_'.(count($this->cores)+1);
        
        $novaCor = new Cor($nomeCor);
        
        $this->cores[] = $novaCor;
        
        return $novaCor;
        
    }

    
    private function colorirVerticeEmProfundidade(Vertice $vertice){
        
        
        if(!$vertice->getCor()){
            
            $cor = $this->procurarCor($vertice->getAdjacentes());
            
            if(!$cor){
                $cor = $this->criarCor();
                
            }
            
            $cor->addVertice($vertice);
            $vertice->setCor($cor);
            
            foreach ($vertice->getAdjacentes() as $verticeAdjacente){
                $this->colorirVerticeEmProfundidade($verticeAdjacente);
            }
            
        }
        
        
    }
    
    private function limpaVerticesCor(){
        
        $this->cores = array();
        
        foreach ($this->vertices as $vertice){
            $vertice->clearCor();
        }
    }


    public function buscarEmProfundidade(){
        
        $this->limpaVerticesCor();
        
        while ($vertice = $this->maiorNumeroAdjacentes($this->vertices)){
            $this->colorirVerticeEmProfundidade($vertice);
        }
        
        return $this->cores;
        
    }
    
    public function buscarEmLargura(){
        
        $this->limpaVerticesCor();
        $vertices       = array();
        
        while ($vertice = $this->maiorNumeroAdjacentes($this->vertices)){
            $this->colorirVerticeEmLargura($vertice);
            $vertices[] = $vertice;

            while (count($vertices)) {

                $veticeColorido = array_shift($vertices);

                foreach ($veticeColorido->getAdjacentes() as $verticeAdjacente){
                    if(!$verticeAdjacente->getCor()){
                        $this->colorirVerticeEmLargura($verticeAdjacente);
                        $vertices[] = $verticeAdjacente;
                    }
                }
            }
        }
        
        
        return $this->cores;
        
    }
    
    private function colorirVerticeEmLargura(Vertice $vertice){
        
        if(!$vertice->getCor()){
            
            $cor = $this->procurarCor($vertice->getAdjacentes());
            
            if(!$cor){
                $cor = $this->criarCor();
            }
            
            $cor->addVertice($vertice);
            $vertice->setCor($cor);
        }
    }
    
    public function buscar(){
        
        $coresBuscaEmProfundide = $this->buscarEmProfundidade();
        
        $coresBuscaEmLargura    = $this->buscarEmLargura(); 
        
        if(count($coresBuscaEmProfundide) < count($coresBuscaEmLargura)){
            return $this->buscarEmProfundidade();
        }
        
        return $coresBuscaEmLargura;
    }
    
}