<?php

namespace Grafo;

/**
 * Description of AlgoritmoKruskal
 *
 * @author willian
 */
class AlgoritmoKruskal {
    
    private $arestas = array();
    private $conjuntos = array();


    /**
     * 
     * @param array $vertices
     * @param array $arestas
     */
    public function __construct(array $vertices, array $arestas) {
        
        $this->arestas = $this->ordenacaoArestas($arestas);
        $this->conjuntos = $this->criarConjuntosVertices($vertices);
        
    }
    
    public function gerarArvoreCobertura(){
        
        $arvoreCobertura = array();
        
        foreach ($this->arestas as $aresta){
            
            $vertice1 = $aresta->getVertece1();
            $vertice2 = $aresta->getVertece2();
            
            if(!$this->verificaVerticesMesmoConjuntos($vertice1, $vertice2)){
                $arvoreCobertura[] = $aresta;
                $this->uniaoConjunto($vertice1, $vertice2);
            }
        }
        
        return $arvoreCobertura;
        
    }
    
    /**
     * 
     * @param \Grafo\Vertece $vertice1
     * @param \Grafo\Vertece $vertice2
     * @return boolean
     */
    protected function verificaVerticesMesmoConjuntos(Vertece $vertice1,Vertece $vertice2){
        
        $mesmoConjunto = false;
        
        $conjunto = reset($this->conjuntos);
        
        do {
            
            if(count($conjunto) > 1 &&
                    $this->verificaVerticeConjunto($conjunto, $vertice1) && 
                    $this->verificaVerticeConjunto($conjunto, $vertice2)){
                $mesmoConjunto = true;
            }
            
        } while (!$mesmoConjunto && $conjunto = next($this->conjuntos));
        
        return $mesmoConjunto;
        
    }
    
    protected function verificaVerticeConjunto(array $conjunto, Vertece $vertice){
        
        $encontradoVertice = false;
        
        $verticeConjunto = reset($conjunto);
        
        do {
            
            if($vertice === $verticeConjunto){
                $encontradoVertice = true;
            }
            
        } while (!$encontradoVertice && $verticeConjunto = next($conjunto));
        
        return $encontradoVertice;
    }

    protected function uniaoConjunto(Vertece $vertice1,Vertece $vertice2){
        
        $indexVertece1 = $this->buscaIndeceConjuntosVertices($vertice1);
        $indexVertece2 = $this->buscaIndeceConjuntosVertices($vertice2);
        
        $mergeVertice = array_merge($this->conjuntos[$indexVertece1], $this->conjuntos[$indexVertece2]);
        
        $this->conjuntos[] = $mergeVertice;
        
        unset($this->conjuntos[$indexVertece1]);
        unset($this->conjuntos[$indexVertece2]);
        
    }
    
    protected function buscaIndeceConjuntosVertices(Vertece $vertice){
        
        foreach ($this->conjuntos as $key => $conjunto){
            if($this->verificaVerticeConjunto($conjunto, $vertice)){
                return $key;
            }
            
        }
        
        
    }

    /**
     * 
     * @param Aresta[] $arestas
     * @return Aresta[] arestas prdenadas
     */
    public function ordenacaoArestas(array $arestas){
        
        $count = count($arestas);
        
        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count; $j++) {
                if($arestas[$i]->getPeso() < $arestas[$j]->getPeso()){
                    
                    $aresta = $arestas[$i];
                    
                    $arestas[$i] = $arestas[$j];
                    $arestas[$j] = $aresta;
                    
                }
            }
        }
        
        return $arestas;
        
    }
    
    /**
     * @param Vertece[] $vertices
     * @return array retorna array de vertices
     */
    public function criarConjuntosVertices(array $vertices){
        
        $verticesConjunto = array();
        
        foreach ($vertices as $vertice){
            $verticesConjunto[] = array($vertice);
        }
        
        return $verticesConjunto;
        
    }
    
}
